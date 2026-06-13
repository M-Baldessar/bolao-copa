<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PredictionController extends Controller
{
    public function index(): View
    {
        $predictions = auth()->user()
            ->predictions()
            ->with(['match.homeTeam', 'match.awayTeam', 'match.group', 'bolaoGroup'])
            ->join('game_matches', 'predictions.match_id', '=', 'game_matches.id')
            ->orderByRaw('CASE WHEN game_matches.home_score IS NOT NULL THEN 1 ELSE 0 END')
            ->orderBy('game_matches.match_date')
            ->orderBy('predictions.match_id')
            ->select('predictions.*')
            ->get();

        return view('predictions.index', compact('predictions'));
    }
}
