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
            $duration = $apiMatch['score']['duration'] ?? 'REGULAR';

            if ($duration === 'PENALTY_SHOOTOUT') {
                // fullTime inclui pênaltis; o bolão usa apenas tempo normal + prorrogação
                $homeScore = ($apiMatch['score']['regularTime']['home'] ?? 0)
                           + ($apiMatch['score']['extraTime']['home']   ?? 0);
                $awayScore = ($apiMatch['score']['regularTime']['away'] ?? 0)
                           + ($apiMatch['score']['extraTime']['away']   ?? 0);
            } else {
                $homeScore = $apiMatch['score']['fullTime']['home'] ?? null;
                $awayScore = $apiMatch['score']['fullTime']['away'] ?? null;
            }

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

            // Determina vencedor por pênaltis (para placar empatado)
            $winnerTeamId = null;
            if ($duration === 'PENALTY_SHOOTOUT') {
                $apiWinner    = $apiMatch['score']['winner'] ?? null;
                $winnerTeamId = match ($apiWinner) {
                    'HOME_TEAM' => $homeTeam->id,
                    'AWAY_TEAM' => $awayTeam->id,
                    default     => null,
                };
            }

            // Busca a partida local
            $match = GameMatch::where('home_team_id', $homeTeam->id)
                ->where('away_team_id', $awayTeam->id)
                ->first();

            if (! $match) {
                $notFound++;
                continue;
            }

            // Monta apenas os campos que precisam ser atualizados
            $updates = [];
            if ($match->home_score === null) {
                $updates['home_score'] = $homeScore;
                $updates['away_score'] = $awayScore;
            }
            if ($winnerTeamId && $match->winner_team_id === null) {
                $updates['winner_team_id'] = $winnerTeamId;
            }

            if (empty($updates)) {
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $label = isset($updates['home_score'])
                    ? "{$homeTeam->name} {$homeScore} × {$awayScore} {$awayTeam->name}"
                    : "{$homeTeam->name} vs {$awayTeam->name}";
                $this->line("  [dry-run] {$label}" . ($winnerTeamId ? " (vencedor pênaltis)" : ''));
                $updated++;
                continue;
            }

            $match->update($updates);

            $this->info("  ✓ {$homeTeam->flag_emoji} {$homeTeam->name} {$homeScore} × {$awayScore} {$awayTeam->name} {$awayTeam->flag_emoji}"
                . ($winnerTeamId ? " (pênaltis)" : ''));
            Log::info('Match result synced', [
                'match_id'       => $match->id,
                'home'           => $homeTeam->name,
                'away'           => $awayTeam->name,
                'home_score'     => $homeScore,
                'away_score'     => $awayScore,
                'winner_team_id' => $winnerTeamId,
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
