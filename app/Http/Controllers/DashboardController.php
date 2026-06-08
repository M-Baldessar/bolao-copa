<?php

namespace App\Http\Controllers;

use App\Models\BolaoGroup;
use App\Models\ChampionPick;
use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $totalMatches = GameMatch::where('stage', 'group')->count();

        // Bolões do usuário com a escolha de campeão carregada
        $myGroups = BolaoGroup::where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->withCount('members')
            ->with('members')
            ->get();

        // Progresso global: cada bolão precisa dos seus próprios 72 palpites
        $totalNeeded    = $totalMatches * max(1, $myGroups->count());
        $predictedCount = $user->predictions()
            ->whereHas('match', fn($q) => $q->where('stage', 'group'))
            ->count();
        $remainingCount = max(0, $totalNeeded - $predictedCount);

        // Ranking de cada bolão com posição e progresso do usuário logado
        $groupRankings = $myGroups->map(function ($group) use ($user, $totalMatches) {
            $ranking  = $group->buildRanking();
            $myIndex  = $ranking->search(fn($m) => $m['user']->id === $user->id);
            $predictedInGroup = $user->predictions()
                ->where('bolao_group_id', $group->id)
                ->whereHas('match', fn($q) => $q->where('stage', 'group'))
                ->count();
            return [
                'group'           => $group,
                'ranking'         => $ranking,
                'my_position'     => $myIndex !== false ? $myIndex + 1 : null,
                'my_points'       => $myIndex !== false ? $ranking[$myIndex]['points'] : 0,
                'predicted_count' => $predictedInGroup,
                'total_matches'   => $totalMatches,
            ];
        });

        // Escolhas de campeão do usuário, indexadas por bolao_group_id
        $championPicks = ChampionPick::where('user_id', $user->id)
            ->with('team')
            ->get()
            ->keyBy('bolao_group_id');

        // Todos os times para o modal de seleção
        $teams = Team::orderBy('name')->get();

        // Status de bloqueio e data limite
        $champLocked  = ChampionPick::isLocked();
        $champLockDate = ChampionPick::lockDate();

        return view('dashboard', compact(
            'totalMatches',
            'totalNeeded',
            'predictedCount',
            'remainingCount',
            'myGroups',
            'groupRankings',
            'championPicks',
            'teams',
            'champLocked',
            'champLockDate'
        ));
    }
}
