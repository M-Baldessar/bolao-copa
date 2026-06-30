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
            ->with(['homeTeam', 'awayTeam', 'winner'])
            ->orderBy('match_number')
            ->get()
            ->groupBy('stage');

        $r32All  = $allKnockout->get('round_of_32', collect());
        $r16Real = $allKnockout->get('round_of_16', collect());
        $qfReal  = $allKnockout->get('quarterfinal', collect());
        $sfReal  = $allKnockout->get('semifinal', collect());

        // Busca por código dos times — imune à diferença de match_number entre ambientes
        $f = fn (string $a, string $b) => $r32All->first(
            fn ($m) => ($m->homeTeam->code === $a && $m->awayTeam->code === $b)
                    || ($m->homeTeam->code === $b && $m->awayTeam->code === $a)
        );

        // Chaveamento correto (par de R32 que se enfrenta em cada Oitavas)
        // Esq: Oitavas 1→GER×PAR / FRA×SWE · 2→RSA×CAN / NED×MAR
        //      Oitavas 3→POR×CRO / ESP×AUT  · 4→USA×BIH / BEL×SEN
        // Dir: Oitavas 5→BRA×JPN / CIV×NOR  · 6→MEX×ECU / ENG×COD
        //      Oitavas 7→ARG×CPV / AUS×EGY  · 8→SUI×ALG / COL×GHA
        $r32Left = collect([
            $f('GER', 'PAR'), $f('FRA', 'SWE'),
            $f('RSA', 'CAN'), $f('NED', 'MAR'),
            $f('POR', 'CRO'), $f('ESP', 'AUT'),
            $f('USA', 'BIH'), $f('BEL', 'SEN'),
        ]);

        $r32Right = collect([
            $f('BRA', 'JPN'), $f('CIV', 'NOR'),
            $f('MEX', 'ECU'), $f('ENG', 'COD'),
            $f('ARG', 'CPV'), $f('AUS', 'EGY'),
            $f('SUI', 'ALG'), $f('COL', 'GHA'),
        ]);

        // Cada round é projetado a partir do anterior: busca a partida real pelo time
        // vencedor em vez de pelo índice — imune à ordem de inserção no banco.
        $r16Left  = $this->buildNextRound($r32Left,  $r16Real);
        $r16Right = $this->buildNextRound($r32Right, $r16Real);
        $qfLeft   = $this->buildNextRound($r16Left,  $qfReal);
        $qfRight  = $this->buildNextRound($r16Right, $qfReal);
        $sfLeft   = $this->buildNextRound($qfLeft,   $sfReal);
        $sfRight  = $this->buildNextRound($qfRight,  $sfReal);

        $bracket = [
            'left'  => ['r32' => $r32Left,  'r16' => $r16Left,  'qf' => $qfLeft,  'sf' => $sfLeft],
            'right' => ['r32' => $r32Right, 'r16' => $r16Right, 'qf' => $qfRight, 'sf' => $sfRight],
            'final'      => $allKnockout->get('final', collect())->first(),
            'thirdPlace' => $allKnockout->get('third_place', collect())->first(),
        ];

        return view('groups.index', compact('groups', 'bracket'));
    }

    /**
     * Monta os slots do próximo round: busca a partida real pelo time vencedor do par
     * anterior (lookup por team ID); caso não exista, projeta o vencedor como placeholder.
     */
    private function buildNextRound(Collection $currentRound, Collection $realNextRound): Collection
    {
        $slots  = (int) ceil($currentRound->count() / 2);
        $result = collect();

        for ($i = 0; $i < $slots; $i++) {
            $teamA = $this->resolveWinner($currentRound->get($i * 2));
            $teamB = $this->resolveWinner($currentRound->get($i * 2 + 1));

            // Procura a partida real que contém um ou ambos os vencedores deste par
            if ($teamA || $teamB) {
                $realMatch = $realNextRound->first(function ($m) use ($teamA, $teamB) {
                    $ids = array_filter([$m->homeTeam?->id, $m->awayTeam?->id]);
                    if ($teamA && $teamB) {
                        return in_array($teamA->id, $ids) && in_array($teamB->id, $ids);
                    }
                    return in_array(($teamA ?? $teamB)->id, $ids);
                });

                if ($realMatch) {
                    $result->push($realMatch);
                    continue;
                }
            }

            $result->push($teamA || $teamB
                ? (object) ['homeTeam' => $teamA, 'awayTeam' => $teamB, 'home_score' => null, 'away_score' => null, 'projected' => true]
                : null
            );
        }

        return $result;
    }

    /**
     * Retorna o time vencedor de uma partida.
     * Para jogos que foram a pênaltis (placar empatado), usa winner_team_id.
     */
    private function resolveWinner(mixed $match): mixed
    {
        if (! $match || $match->home_score === null || $match->away_score === null) {
            return null;
        }

        if ($match->home_score > $match->away_score) return $match->homeTeam;
        if ($match->away_score > $match->home_score) return $match->awayTeam;

        // Empate no tempo normal + prorrogação: verificar vencedor nos pênaltis
        return $match->winner ?? null;
    }
}
