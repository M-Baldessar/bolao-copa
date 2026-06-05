<?php

namespace App\Http\Controllers;

use App\Models\BolaoGroup;
use App\Models\ChampionPick;
use App\Models\Team;
use Illuminate\Http\Request;

class ChampionPickController extends Controller
{
    public function store(Request $request)
    {
        if (ChampionPick::isLocked()) {
            return back()->with('error', 'As escolhas de campeão foram encerradas antes da primeira partida.');
        }

        $validated = $request->validate([
            'bolao_group_id'    => 'required|exists:bolao_groups,id',
            'team_id'           => 'required|exists:teams,id',
            'runner_up_team_id' => 'nullable|exists:teams,id|different:team_id',
        ]);

        $user       = auth()->user();
        $bolaoGroup = BolaoGroup::findOrFail($validated['bolao_group_id']);

        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (! $isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        ChampionPick::updateOrCreate(
            [
                'user_id'        => $user->id,
                'bolao_group_id' => $validated['bolao_group_id'],
            ],
            [
                'team_id'           => $validated['team_id'],
                'runner_up_team_id' => $validated['runner_up_team_id'] ?? null,
            ]
        );

        $team     = Team::find($validated['team_id']);
        $runnerUp = isset($validated['runner_up_team_id'])
            ? Team::find($validated['runner_up_team_id'])
            : null;

        $msg = "{$team->flag_emoji} {$team->name} (campeão)";
        if ($runnerUp) {
            $msg .= " e {$runnerUp->flag_emoji} {$runnerUp->name} (vice) escolhidos";
        } else {
            $msg .= " escolhida como campeã";
        }
        $msg .= " no bolão \"{$bolaoGroup->name}\"!";

        return back()->with('success', $msg);
    }
}
