<?php

namespace App\Models;

use Database\Factories\CreatorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'username', 'email', 'phone', 'bio', 'ugc_only', 'accepts_barter', 'status', 'rating', 'joined_at', 'last_active_at', 'notes'])]
class Creator extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_PAUSED = 'paused';

    public const STATUS_BLACKLISTED = 'blacklisted';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_PAUSED,
        self::STATUS_BLACKLISTED,
    ];

    /** @use HasFactory<CreatorFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'ugc_only' => 'boolean',
            'accepts_barter' => 'boolean',
            'joined_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'creator_city');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function socialAccounts(): MorphMany
    {
        return $this->morphMany(SocialAccount::class, 'accountable');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['city'] ?? null, fn (Builder $query, int $city) => $query->whereHas('cities', fn (Builder $query) => $query->whereKey($city)))
            ->when($filters['tag'] ?? null, fn (Builder $query, int $tag) => $query->whereHas('tags', fn (Builder $query) => $query->whereKey($tag)))
            ->when(array_key_exists('ugc_only', $filters), fn (Builder $query) => $query->where('ugc_only', $filters['ugc_only']))
            ->when(array_key_exists('accepts_barter', $filters), fn (Builder $query) => $query->where('accepts_barter', $filters['accepts_barter']))
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(fn (Builder $query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            });
    }
}
