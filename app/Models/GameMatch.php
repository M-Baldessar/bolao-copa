<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMatch extends Model
{
    protected $table = 'game_matches';

    const STAGE_LABELS = [
        'group'        => 'Fase de Grupos',
        'round_of_32'  => '16-avos de Final',
        'round_of_16'  => 'Oitavas de Final',
        'quarterfinal' => 'Quartas de Final',
        'semifinal'    => 'Semifinal',
        'third_place'  => '3º Lugar',
        'final'        => 'Final',
    ];

    protected $fillable = [
        'group_id',
        'home_team_id',
        'away_team_id',
        'match_number',
        'stage',
        'match_date',
        'home_score',
        'away_score',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function userPrediction(): HasOne
    {
        return $this->hasOne(Prediction::class, 'match_id')
                    ->where('user_id', auth()->id());
    }
}
