<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Group;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        $groups = Group::with('teams')->orderBy('name')->get();

        $allKnockout = GameMatch::whereIn('stage', ['round_of_32', 'round_of_16', 'quarterfinal', 'semifinal', 'third_place', 'final'])
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('match_number')
            ->get()
            ->groupBy('stage');

        $r32Indexed = $allKnockout->get('round_of_32', collect())->keyBy('match_number');
        $r16Real    = $allKnockout->get('round_of_16', collect());
        $qfReal     = $allKnockout->get('quarterfinal', collect());
        $sfReal     = $allKnockout->get('semifinal', collect());

        // Ordem correta do chaveamento: cada par de R32 que se enfrenta em cada Oitavas
        // Esq: Oitavas 1→#75,#78 · Oitavas 2→#73,#76 · Oitavas 3→#84,#83 · Oitavas 4→#82,#81
        // Dir: Oitavas 5→#74,#77 · Oitavas 6→#79,#80 · Oitavas 7→#87,#86 · Oitavas 8→#85,#88
        $r32Left  = collect([75, 78, 73, 76, 84, 83, 82, 81])->map(fn ($n) => $r32Indexed->get($n));
        $r32Right = collect([74, 77, 79, 80, 87, 86, 85, 88])->map(fn ($n) => $r32Indexed->get($n));

        // Cada round é projetado a partir do anterior: usa partida real se existir,
        // senão monta slot sintético com o vencedor projetado do par anterior.
        $r16Left  = $this->buildNextRound($r32Left,  $r16Real->take(4)->values());
        $r16Right = $this->buildNextRound($r32Right, $r16Real->skip(4)->values());
        $qfLeft   = $this->buildNextRound($r16Left,  $qfReal->take(2)->values());
        $qfRight  = $this->buildNextRound($r16Right, $qfReal->skip(2)->values());
        $sfLeft   = $this->buildNextRound($qfLeft,   $sfReal->take(1)->values());
        $sfRight  = $this->buildNextRound($qfRight,  $sfReal->skip(1)->values());

        $bracket = [
            'left'  => ['r32' => $r32Left,  'r16' => $r16Left,  'qf' => $qfLeft,  'sf' => $sfLeft],
            'right' => ['r32' => $r32Right, 'r16' => $r16Right, 'qf' => $qfRight, 'sf' => $sfRight],
            'final'      => $allKnockout->get('final', collect())->first(),
            'thirdPlace' => $allKnockout->get('third_place', collect())->first(),
        ];

        return view('groups.index', compact('groups', 'bracket'));
    }

    /**
     * Monta os slots do próximo round: usa a partida real (banco) quando disponível;
     * caso contrário, projeta o vencedor de cada par do round atual.
     */
    private function buildNextRound(Collection $currentRound, Collection $realNextRound): Collection
    {
        $slots  = (int) ceil($currentRound->count() / 2);
        $result = collect();

        for ($i = 0; $i < $slots; $i++) {
            if ($realNextRound->has($i)) {
                $result->push($realNextRound->get($i));
                continue;
            }

            $teamA = $this->resolveWinner($currentRound->get($i * 2));
            $teamB = $this->resolveWinner($currentRound->get($i * 2 + 1));

            $result->push($teamA || $teamB
                ? (object) ['homeTeam' => $teamA, 'awayTeam' => $teamB, 'home_score' => null, 'away_score' => null, 'projected' => true]
                : null
            );
        }

        return $result;
    }

    /**
     * Retorna o time vencedor de uma partida (real ou projetada).
     * Slots projetados não têm placar, então retornam null — sem projeção em cascata além disso.
     */
    private function resolveWinner(mixed $match): mixed
    {
        if (! $match || $match->home_score === null || $match->away_score === null) {
            return null;
        }

        if ($match->home_score > $match->away_score) return $match->homeTeam;
        if ($match->away_score > $match->home_score) return $match->awayTeam;

        return null;
    }
}
