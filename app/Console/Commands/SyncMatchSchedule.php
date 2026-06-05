<?php

namespace App\Console\Commands;

use App\Models\GameMatch;
use App\Models\Group;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncMatchSchedule extends Command
{
    protected $signature = 'matches:sync-schedule
                            {--dry-run : Mostra o que seria importado sem salvar}
                            {--list   : Lista os dados brutos da API para debug}';

    protected $description = 'Importa a tabela de jogos da Copa 2026 via football-data.org';

    /**
     * Mapeamento dos estágios da API para os estágios do sistema.
     */
    private array $stageMap = [
        'GROUP_STAGE'    => 'group',
        'LAST_32'        => 'round_of_32',  // Copa 2026 usa LAST_32
        'LAST_16'        => 'round_of_16',  // Copa 2026 usa LAST_16
        'ROUND_OF_32'    => 'round_of_32',
        'ROUND_OF_16'    => 'round_of_16',
        'QUARTER_FINALS' => 'quarterfinal',
        'SEMI_FINALS'    => 'semifinal',
        'THIRD_PLACE'    => 'third_place',
        'FINAL'          => 'final',
    ];

    /**
     * Mapeamento de códigos TLA da API que diferem do nosso banco.
     * Chave = código da API, Valor = código no TeamSeeder.
     */
    private array $codeAliases = [
        'CZE' => 'REP', // República Tcheca
        'BIH' => 'BOS', // Bósnia
        'SAU' => 'KSA', // Arábia Saudita
        'DZA' => 'ALG', // Argélia
        'CIV' => 'CIV', // Costa do Marfim (igual)
        'URY' => 'URU', // Uruguai
    ];

    public function handle(): int
    {
        $this->info('📅 Importando tabela de jogos da Copa 2026...');

        $response = Http::withHeaders([
            'X-Auth-Token' => config('services.football_data.token'),
        ])->get(config('services.football_data.base_url') . '/competitions/' . config('services.football_data.competition') . '/matches');

        // Verifica rate limit
        $remaining = (int) ($response->header('X-Requests-Available-Minute') ?? 10);
        if ($remaining < 2) {
            $this->warn("⚠️  Rate limit atingido. Tente novamente em breve.");
            return self::FAILURE;
        }

        if ($response->failed()) {
            $this->error("❌ Erro na API: HTTP {$response->status()} — {$response->body()}");
            return self::FAILURE;
        }

        $apiMatches = $response->json('matches', []);
        $this->info("📋 " . count($apiMatches) . " partidas encontradas na API.");

        // Modo --list: exibe dados brutos para diagnóstico
        if ($this->option('list')) {
            $this->newLine();
            $this->table(
                ['#', 'Data (UTC)', 'Estágio API', 'Grupo API', 'Casa (TLA)', 'Visitante (TLA)'],
                collect($apiMatches)->map(fn ($m, $i) => [
                    $i + 1,
                    $m['utcDate'] ?? '—',
                    $m['stage'] ?? '—',
                    $m['group'] ?? '—',
                    $m['homeTeam']['tla'] ?? '—',
                    $m['awayTeam']['tla'] ?? '—',
                ])
            );
            return self::SUCCESS;
        }

        $created  = 0;
        $skipped  = 0;
        $notFound = [];
        $matchNumber = GameMatch::max('match_number') ?? 0;

        foreach ($apiMatches as $apiMatch) {
            $apiStage = $apiMatch['stage'] ?? null;
            $stage    = $this->stageMap[$apiStage] ?? null;

            if (! $stage) {
                $this->warn("⚠️  Estágio desconhecido: {$apiStage}");
                $skipped++;
                continue;
            }

            $homeTla  = $apiMatch['homeTeam']['tla'] ?? null;
            $awayTla  = $apiMatch['awayTeam']['tla'] ?? null;
            $homeName = $apiMatch['homeTeam']['name'] ?? null;
            $awayName = $apiMatch['awayTeam']['name'] ?? null;

            // Ignora silenciosamente jogos com times ainda não definidos (ex: oitavas/quartas TBD)
            if (! $homeTla || ! $awayTla) {
                $skipped++;
                continue;
            }

            // Resolve times
            $homeTeam = $this->resolveTeam($homeTla, $homeName);
            $awayTeam = $this->resolveTeam($awayTla, $awayName);

            if (! $homeTeam || ! $awayTeam) {
                $notFound[] = "{$homeName} vs {$awayName} [{$homeTla} × {$awayTla}]";
                $skipped++;
                continue;
            }

            // Verifica se a partida já existe
            $exists = GameMatch::where('home_team_id', $homeTeam->id)
                ->where('away_team_id', $awayTeam->id)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Resolve grupo (apenas para fase de grupos)
            $groupId = null;
            if ($stage === 'group' && isset($apiMatch['group'])) {
                // API retorna "GROUP_A", "GROUP_B", etc.
                $groupLetter = str_replace('GROUP_', '', $apiMatch['group']);
                $group = Group::where('name', $groupLetter)->first();
                $groupId = $group?->id;
            }

            // Data: converte UTC para horário de Brasília (UTC-3)
            $matchDate = null;
            if (! empty($apiMatch['utcDate'])) {
                $matchDate = \Carbon\Carbon::parse($apiMatch['utcDate'])
                    ->setTimezone('America/Sao_Paulo');
            }

            $matchNumber++;

            if ($this->option('dry-run')) {
                $this->line("  [dry-run] #{$matchNumber} {$homeTeam->flag_emoji} {$homeTeam->name} × {$awayTeam->name} {$awayTeam->flag_emoji} — {$stage} — " . ($matchDate?->format('d/m/Y H:i') ?? '?'));
                $created++;
                continue;
            }

            GameMatch::create([
                'match_number' => $matchNumber,
                'stage'        => $stage,
                'group_id'     => $groupId,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'match_date'   => $matchDate,
            ]);

            $this->info("  ✓ #{$matchNumber} {$homeTeam->flag_emoji} {$homeTeam->name} × {$awayTeam->name} {$awayTeam->flag_emoji}");
            $created++;
        }

        $this->newLine();
        $this->info("✅ Importação concluída:");
        $this->table(
            ['Status', 'Quantidade'],
            [
                ['Criadas',   $created],
                ['Ignoradas (já existiam ou sem dados)', $skipped],
                ['Times não encontrados', count($notFound)],
            ]
        );

        if (! empty($notFound)) {
            $this->newLine();
            $this->warn("Times não encontrados no banco (ajuste os códigos no TeamSeeder ou no array \$codeAliases do comando):");
            foreach ($notFound as $pair) {
                $this->line("  • {$pair}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Resolve um time pelo código TLA da API, com fallback por nome.
     */
    private function resolveTeam(?string $tla, ?string $name): ?Team
    {
        if (! $tla) return null;

        // Tenta o código direto
        $team = Team::where('code', $tla)->first();
        if ($team) return $team;

        // Tenta aliases conhecidos
        $aliased = $this->codeAliases[$tla] ?? null;
        if ($aliased) {
            $team = Team::where('code', $aliased)->first();
            if ($team) return $team;
        }

        // Fallback: busca por nome (parcial, case-insensitive)
        if ($name) {
            $team = Team::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
            if ($team) return $team;
        }

        return null;
    }
}
