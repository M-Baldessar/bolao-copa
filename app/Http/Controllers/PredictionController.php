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
            ->orderBy('match_id')
            ->get();

        return view('predictions.index', compact('predictions'));
    }
}
