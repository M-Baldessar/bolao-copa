<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\Group;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $matchNumber = 1;

        foreach (Group::with('teams')->orderBy('name')->get() as $group) {
            $teams = $group->teams->values();
            $count = count($teams);

            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    GameMatch::create([
                        'group_id'     => $group->id,
                        'home_team_id' => $teams[$i]->id,
                        'away_team_id' => $teams[$j]->id,
                        'match_number' => $matchNumber++,
                        'match_date'   => null,
                        'home_score'   => null,
                        'away_score'   => null,
                    ]);
                }
            }
        }
    }
}
