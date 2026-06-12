<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
        'is_public',
        'invite_code',
        'status',
        'points_exact_score',
        'points_winner',
        'points_draw_hit',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'points_exact_score' => 'integer',
            'points_winner' => 'integer',
            'points_draw_hit' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Pool $pool) {
            if (empty($pool->slug)) {
                $pool->slug = Str::slug($pool->name);
            }
            if (empty($pool->invite_code)) {
                $pool->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    // -----------------------------------------------------------
    // Relacionamentos
    // -----------------------------------------------------------

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pool_members')
                    ->withPivot(['role', 'total_points'])
                    ->withTimestamps();
    }

    public function guesses(): HasMany
    {
        return $this->hasMany(Guess::class);
    }

    // -----------------------------------------------------------
    // Ranking
    // -----------------------------------------------------------

    public function getRanking()
    {
        return $this->members()
                    ->orderByPivot('total_points', 'desc')
                    ->get();
    }
}

// -----------------------------------------------------------
// Group Model
// -----------------------------------------------------------

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = ['name', 'label'];

    public function matches(): HasMany
    {
        return $this->hasMany(Match::class);
    }
}
