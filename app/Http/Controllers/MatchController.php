<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Group;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function index(Request $request): View
    {
        $groupFilter = $request->query('group');
        $stageFilter = $request->query('stage');

        $query = GameMatch::with(['homeTeam', 'awayTeam', 'group'])
            ->orderByRaw('CASE WHEN home_score IS NOT NULL THEN 1 ELSE 0 END')
            ->orderBy('match_date')
            ->orderBy('match_number');

        if ($groupFilter) {
            $query->whereHas('group', fn ($q) => $q->where('name', $groupFilter));
        } elseif ($stageFilter) {
            $query->where('stage', $stageFilter);
        }

        $matches = $query->get();
        $groups  = Group::orderBy('name')->get();
        $knockoutStages = collect(GameMatch::STAGE_LABELS)->except('group');

        return view('matches.index', compact('matches', 'groups', 'groupFilter', 'stageFilter', 'knockoutStages'));
    }

    public function store(Request $request, GameMatch $match): RedirectResponse
    {
        if ($match->home_score !== null) {
            return back()->with('error', 'Não é possível alterar o palpite de uma partida com resultado já registrado.');
        }

        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:20'],
            'away_score' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        Prediction::updateOrCreate(
            ['user_id' => auth()->id(), 'match_id' => $match->id],
            ['home_score' => $validated['home_score'], 'away_score' => $validated['away_score']]
        );

        return back()->with(
            'success',
            "Palpite para {$match->homeTeam->name} x {$match->awayTeam->name} salvo!"
        );
    }
}
