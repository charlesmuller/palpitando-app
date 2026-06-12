<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'pool_id',
        'home_score_guess',
        'away_score_guess',
        'points_earned',
        'is_calculated',
    ];

    protected function casts(): array
    {
        return [
            'home_score_guess' => 'integer',
            'away_score_guess' => 'integer',
            'points_earned'    => 'integer',
            'is_calculated'    => 'boolean',
        ];
    }

    // -----------------------------------------------------------
    // Relacionamentos
    // -----------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(Match::class);
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class);
    }

    // -----------------------------------------------------------
    // Cálculo de pontos
    // -----------------------------------------------------------

    public function calculatePoints(): int
    {
        $match = $this->match;
        $pool  = $this->pool;

        if (!$match->isFinished()) return 0;

        $realHome = $match->home_score;
        $realAway = $match->away_score;
        $guessHome = $this->home_score_guess;
        $guessAway = $this->away_score_guess;

        // Placar exato
        if ($guessHome === $realHome && $guessAway === $realAway) {
            return $pool->points_exact_score;
        }

        // Acertou o empate
        if ($realHome === $realAway && $guessHome === $guessAway) {
            return $pool->points_draw_hit;
        }

        // Acertou o vencedor
        $realWinner  = $realHome > $realAway ? 'home' : ($realAway > $realHome ? 'away' : 'draw');
        $guessWinner = $guessHome > $guessAway ? 'home' : ($guessAway > $guessHome ? 'away' : 'draw');

        if ($realWinner === $guessWinner && $realWinner !== 'draw') {
            return $pool->points_winner;
        }

        return 0;
    }
}
