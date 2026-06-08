<?php

namespace App\Services;

class PredictionGenerator
{
    /**
     * Gera um palpite realista baseado na força dos dois times.
     * Força varia de 1 (muito fraco) a 100 (favorito mundial).
     */
    public static function generate(int $homeStrength, int $awayStrength): array
    {
        $diff = $homeStrength - $awayStrength; // -100 a +100

        // Probabilidade base de vitória do mandante + leve vantagem de campo (~5%)
        $homeWinProb = 0.52 + ($diff / 100) * 0.38;
        $homeWinProb = min(0.88, max(0.12, $homeWinProb));

        // Probabilidade de empate: maior quando os times são equilibrados
        $drawProb = max(0.08, 0.26 - abs($diff) / 350);

        $awayWinProb = max(0.04, 1.0 - $homeWinProb - $drawProb);

        // Normaliza para garantir que soma = 1
        $total = $homeWinProb + $drawProb + $awayWinProb;
        $homeWinProb /= $total;
        $drawProb    /= $total;
        // $awayWinProb não precisa ser recalculado pois usaremos o complemento

        // Sorteia o resultado
        $rand = mt_rand(0, 9999) / 10000.0;
        if ($rand < $homeWinProb) {
            $outcome = 'home';
        } elseif ($rand < $homeWinProb + $drawProb) {
            $outcome = 'draw';
        } else {
            $outcome = 'away';
        }

        // Teto de gols por partida baseado na média de força das equipes
        $avg = ($homeStrength + $awayStrength) / 2.0;
        $maxWin  = $avg >= 78 ? 4 : ($avg >= 60 ? 3 : 2);
        $maxLoss = max(0, $maxWin - 1);

        switch ($outcome) {
            case 'home':
                $home = mt_rand(1, $maxWin);
                $away = mt_rand(0, min($home - 1, $maxLoss));
                break;

            case 'away':
                $away = mt_rand(1, $maxWin);
                $home = mt_rand(0, min($away - 1, $maxLoss));
                break;

            default: // draw
                // Empates mais comuns: 0×0, 1×1 — 2×2 mais raro
                $weights = [0 => 30, 1 => 50, 2 => 20];
                $score = self::weightedRandom($weights);
                $home = $away = $score;
                break;
        }

        return ['home_score' => $home, 'away_score' => $away];
    }

    private static function weightedRandom(array $weights): int
    {
        $total = array_sum($weights);
        $rand  = mt_rand(1, $total);
        $cumul = 0;
        foreach ($weights as $value => $weight) {
            $cumul += $weight;
            if ($rand <= $cumul) {
                return $value;
            }
        }
        return array_key_last($weights);
    }
}
