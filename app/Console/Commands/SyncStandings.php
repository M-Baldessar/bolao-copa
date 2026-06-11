<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncStandings extends Command
{
    protected $signature = 'matches:sync-standings
                            {--dry-run : Mostra o que seria atualizado sem salvar}';

    protected $description = 'Sincroniza a classificação dos grupos com a API football-data.org';

    public function handle(): int
    {
        $this->info('📊 Sincronizando classificação dos grupos...');

        $response = Http::withHeaders([
            'X-Auth-Token' => config('services.football_data.token'),
        ])->get(config('services.football_data.base_url') . '/competitions/' . config('services.football_data.competition') . '/standings');

        $remaining = (int) ($response->header('X-Requests-Available-Minute') ?? 10);
        if ($remaining < 2) {
            $this->warn("⚠️  Rate limit atingido ({$remaining} req restantes). Tente novamente em breve.");
            return self::FAILURE;
        }

        if ($response->failed()) {
            $this->error("❌ Erro na API: HTTP {$response->status()}");
            return self::FAILURE;
        }

        $standings = $response->json('standings', []);
        $updated   = 0;
        $skipped   = 0;

        foreach ($standings as $standing) {
            if (($standing['type'] ?? '') !== 'TOTAL') continue;

            $groupLetter = trim(str_replace(['GROUP_', 'Group'], '', $standing['group'] ?? ''));
            $group = Group::where('name', $groupLetter)->first();

            if (! $group) {
                $skipped++;
                continue;
            }

            foreach ($standing['table'] as $row) {
                $tla  = $row['team']['tla'] ?? null;
                $team = Team::where('code', $tla)->where('group_id', $group->id)->first();

                if (! $team) {
                    $this->warn("⚠️  Time não encontrado: {$row['team']['name']} ({$tla})");
                    $skipped++;
                    continue;
                }

                if ($this->option('dry-run')) {
                    $this->line("  [dry-run] {$team->flag_emoji} {$team->name} — Pos: {$row['position']} Pts: {$row['points']} J:{$row['playedGames']} V:{$row['won']} E:{$row['draw']} D:{$row['lost']} GP:{$row['goalsFor']} GC:{$row['goalsAgainst']}");
                    $updated++;
                    continue;
                }

                $team->update([
                    'position'      => $row['position'],
                    'points'        => $row['points'],
                    'played'        => $row['playedGames'],
                    'won'           => $row['won'],
                    'drawn'         => $row['draw'],
                    'lost'          => $row['lost'],
                    'goals_for'     => $row['goalsFor'],
                    'goals_against' => $row['goalsAgainst'],
                ]);

                $updated++;
            }
        }

        $this->newLine();
        $this->info("✅ Classificação sincronizada:");
        $this->table(
            ['Status', 'Quantidade'],
            [
                ['Atualizadas', $updated],
                ['Ignoradas',   $skipped],
                ['Requests restantes', $remaining],
            ]
        );

        return self::SUCCESS;
    }
}
