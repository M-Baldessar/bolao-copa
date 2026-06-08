<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'code', 'flag_emoji', 'strength', 'group_id', 'position', 'points', 'played', 'won', 'drawn', 'lost', 'goals_for', 'goals_against'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'away_team_id');
    }
}
