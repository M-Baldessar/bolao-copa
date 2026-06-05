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

        $totalMatches   = GameMatch::count();
        $predictedCount = $user->predictions()->count();
        $remainingCount = $totalMatches - $predictedCount;

        // Bolões do usuário com a escolha de campeão carregada
        $myGroups = BolaoGroup::where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->withCount('members')
            ->get();

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
            'predictedCount',
            'remainingCount',
            'myGroups',
            'championPicks',
            'teams',
            'champLocked',
            'champLockDate'
        ));
    }
}
