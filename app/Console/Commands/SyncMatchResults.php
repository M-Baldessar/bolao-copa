<?php

namespace App\Console\Commands;

use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncMatchResults extends Command
{
    protected $signature = 'matches:sync-results
                            {--dry-run : Mostra o que seria atualizado sem salvar}';

    protected $description = 'Sincroniza resultados das partidas com a API football-data.org';

    public function handle(): int
    {
        $this->info('🔄 Sincronizando resultados com football-data.org...');

        $response = Http::withHeaders([
            'X-Auth-Token' => config('services.football_data.token'),
        ])->get(config('services.football_data.base_url') . '/competitions/' . config('services.football_data.competition') . '/matches', [
            'status' => 'FINISHED',
        ]);

        // Verifica rate limit ANTES de processar (conforme recomendado pela API)
        $remaining = (int) ($response->header('X-Requests-Available-Minute') ?? 10);
        if ($remaining < 2) {
            $this->warn("⚠️  Rate limit atingido ({$remaining} req restantes). Tente novamente em breve.");
            return self::FAILURE;
        }

        if ($response->failed()) {
            $this->error("❌ Erro na API: HTTP {$response->status()}");
            Log::error('football-data API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return self::FAILURE;
        }

        $apiMatches = $response->json('matches', []);
        $this->info("📋 " . count($apiMatches) . " partidas finalizadas encontradas na API.");

        $updated = 0;
        $skipped = 0;
        $notFound = 0;

        foreach ($apiMatches as $apiMatch) {
            $homeScore = $apiMatch['score']['fullTime']['home'] ?? null;
            $awayScore = $apiMatch['score']['fullTime']['away'] ?? null;

            // Ignora se placar não está disponível
            if ($homeScore === null || $awayScore === null) {
                $skipped++;
                continue;
            }

            // Busca times pelo código FIFA (tla = three-letter abbreviation)
            $homeTla = $apiMatch['homeTeam']['tla'] ?? null;
            $awayTla = $apiMatch['awayTeam']['tla'] ?? null;

            $homeTeam = Team::where('code', $homeTla)->first();
            $awayTeam = Team::where('code', $awayTla)->first();

            if (! $homeTeam || ! $awayTeam) {
                $this->warn("⚠️  Times não encontrados: {$apiMatch['homeTeam']['name']} ({$homeTla}) vs {$apiMatch['awayTeam']['name']} ({$awayTla})");
                $notFound++;
                continue;
            }

            // Busca a partida local que ainda não tem resultado
            $match = GameMatch::where('home_team_id', $homeTeam->id)
                ->where('away_team_id', $awayTeam->id)
                ->whereNull('home_score')
                ->first();

            if (! $match) {
                // Partida já tem resultado ou não foi cadastrada
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("  [dry-run] {$homeTeam->name} {$homeScore} × {$awayScore} {$awayTeam->name}");
                $updated++;
                continue;
            }

            $match->update([
                'home_score' => $homeScore,
                'away_score' => $awayScore,
            ]);

            $this->info("  ✓ {$homeTeam->flag_emoji} {$homeTeam->name} {$homeScore} × {$awayScore} {$awayTeam->name} {$awayTeam->flag_emoji}");
            Log::info('Match result synced', [
                'match_id'   => $match->id,
                'home'       => $homeTeam->name,
                'away'       => $awayTeam->name,
                'home_score' => $homeScore,
                'away_score' => $awayScore,
            ]);

            $updated++;
        }

        $this->newLine();
        $this->info("✅ Sincronização concluída:");
        $this->table(
            ['Status', 'Quantidade'],
            [
                ['Atualizadas', $updated],
                ['Ignoradas (já tinham resultado)', $skipped],
                ['Times não encontrados', $notFound],
                ['Requests restantes (este minuto)', $remaining],
            ]
        );

        return self::SUCCESS;
    }
}
