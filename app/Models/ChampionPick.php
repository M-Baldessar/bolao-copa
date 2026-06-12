<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChampionPick extends Model
{
    protected $fillable = ['user_id', 'bolao_group_id', 'team_id', 'runner_up_team_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bolaoGroup(): BelongsTo
    {
        return $this->belongsTo(BolaoGroup::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function runnerUp(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'runner_up_team_id');
    }

    public static function isLocked(): bool
    {
        return now()->gte(static::lockDate());
    }

    public static function lockDate(): ?\Carbon\Carbon
    {
        $deadline = config('bolao.champion_pick_deadline');

        if ($deadline) {
            return \Carbon\Carbon::parse($deadline);
        }

        return GameMatch::whereNotNull('match_date')
            ->orderBy('match_date')
            ->first()?->match_date;
    }

    /**
     * 100 pts se acertou o campeão (vencedor da final).
     */
    public function points(): int
    {
        $final = GameMatch::where('stage', 'final')
            ->whereNotNull('home_score')
            ->first();

        if (! $final) {
            return 0;
        }

        if ($final->home_score > $final->away_score) {
            $championId = $final->home_team_id;
        } elseif ($final->away_score > $final->home_score) {
            $championId = $final->away_team_id;
        } else {
            return 0;
        }

        return $this->team_id === $championId ? 100 : 0;
    }

    /**
     * 50 pts se acertou o vice-campeão (perdedor da final).
     */
    public function runnerUpPoints(): int
    {
        if (! $this->runner_up_team_id) {
            return 0;
        }

        $final = GameMatch::where('stage', 'final')
            ->whereNotNull('home_score')
            ->first();

        if (! $final) {
            return 0;
        }

        if ($final->home_score > $final->away_score) {
            $runnerUpId = $final->away_team_id;
        } elseif ($final->away_score > $final->home_score) {
            $runnerUpId = $final->home_team_id;
        } else {
            return 0;
        }

        return $this->runner_up_team_id === $runnerUpId ? 50 : 0;
    }
}
