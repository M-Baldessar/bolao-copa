<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $fillable = ['user_id', 'bolao_group_id', 'match_id', 'home_score', 'away_score'];

    protected $casts = [
        'home_score' => 'integer',
        'away_score' => 'integer',
    ];

    public function bolaoGroup(): BelongsTo
    {
        return $this->belongsTo(BolaoGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function result(): string
    {
        $m = $this->match;

        if ($m->home_score === null) {
            return 'pending';
        }

        $pH = $this->home_score;
        $pA = $this->away_score;
        $rH = $m->home_score;
        $rA = $m->away_score;

        // Placar exato
        if ($pH === $rH && $pA === $rA) {
            return 'exact';
        }

        $actualOutcome    = $rH <=> $rA; // 1=home vence, -1=away vence, 0=empate
        $predictedOutcome = $pH <=> $pA;

        // Empate: acertou que seria empate mas com outro placar
        if ($actualOutcome === 0 && $predictedOutcome === 0) {
            return 'draw';
        }

        // Acertou o vencedor
        if ($actualOutcome !== 0 && $predictedOutcome === $actualOutcome) {
            // Placar do vencedor (15 pts) — acertou o gol do time vencedor
            if ($actualOutcome > 0 && $pH === $rH) return 'winner_score';
            if ($actualOutcome < 0 && $pA === $rA) return 'winner_score';

            // Vencedor + diferença de gols (12 pts)
            if (($pH - $pA) === ($rH - $rA)) return 'goal_diff';

            // Vencedor + placar do perdedor (10 pts) — acertou o gol do time perdedor
            if ($actualOutcome > 0 && $pA === $rA) return 'loser_score';
            if ($actualOutcome < 0 && $pH === $rH) return 'loser_score';

            // Apenas vencedor certo (8 pts)
            return 'correct_winner';
        }

        return 'wrong';
    }

    public function points(): int
    {
        return match ($this->result()) {
            'exact'          => 20,
            'winner_score'   => 15,
            'goal_diff'      => 12,
            'loser_score'    => 10,
            'correct_winner' => 8,
            'draw'           => 8,
            default          => 0,
        };
    }
}
