<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Match extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'api_id',
        'competition_id',
        'season',
        'home_team_id',
        'away_team_id',
        'stage',
        'group_id',
        'match_date',
        'status',
        'home_score',
        'away_score',
        'home_penalties',
        'away_penalties',
        'winner',
        'venue',
        'matchday',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
            'home_score' => 'integer',
            'away_score' => 'integer',
            'home_penalties' => 'integer',
            'away_penalties' => 'integer',
            'matchday' => 'integer',
        ];
    }

    // -----------------------------------------------------------
    // Relacionamentos
    // -----------------------------------------------------------

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function guesses(): HasMany
    {
        return $this->hasMany(Guess::class);
    }

    // -----------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------

    public function scopeFinished($query)
    {
        return $query->where('status', 'FINISHED');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'SCHEDULED')
                     ->where('match_date', '>=', now())
                     ->orderBy('match_date');
    }

    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }

    // -----------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------

    public function isFinished(): bool
    {
        return $this->status === 'FINISHED';
    }

    public function isLocked(): bool
    {
        // Palpite é bloqueado 1 hora antes do jogo
        return now()->greaterThan($this->match_date->subHour());
    }

    public function getResultLabel(): string
    {
        if (!$this->isFinished()) return '-';

        $home = $this->home_score;
        $away = $this->away_score;

        if ($this->home_penalties !== null) {
            return "{$home} ({$this->home_penalties}) x ({$this->away_penalties}) {$away}";
        }

        return "{$home} x {$away}";
    }

    public function getStageLabel(): string
    {
        return match($this->stage) {
            'GROUP_STAGE'    => 'Fase de Grupos',
            'ROUND_OF_16'   => 'Oitavas de Final',
            'QUARTER_FINALS' => 'Quartas de Final',
            'SEMI_FINALS'   => 'Semifinal',
            'THIRD_PLACE'   => 'Terceiro Lugar',
            'FINAL'          => 'Final',
            default          => $this->stage,
        };
    }
}
