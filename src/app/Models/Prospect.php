<?php

namespace App\Models;

use Database\Factories\ProspectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['prospect_type', 'platform', 'handle', 'profile_url', 'name', 'city_name', 'country_name', 'category', 'status', 'contacted_at', 'responded_at', 'approved_at', 'rejection_reason', 'notes', 'source'])]
class Prospect extends Model
{
    public const TYPE_CREATOR = 'creator';

    public const TYPE_BRAND = 'brand';

    public const TYPES = [
        self::TYPE_CREATOR,
        self::TYPE_BRAND,
    ];

    public const STATUS_DISCOVERED = 'discovered';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_FOLLOW_UP = 'follow_up';

    public const STATUS_INTERESTED = 'interested';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_GHOSTED = 'ghosted';

    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_DISCOVERED,
        self::STATUS_CONTACTED,
        self::STATUS_FOLLOW_UP,
        self::STATUS_INTERESTED,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_GHOSTED,
        self::STATUS_ARCHIVED,
    ];

    /** @use HasFactory<ProspectFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
            'responded_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<Prospect>  $query
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['prospect_type'] ?? null, fn (Builder $query, string $type) => $query->where('prospect_type', $type))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['platform'] ?? null, fn (Builder $query, string $platform) => $query->where('platform', $platform))
            ->when($filters['category'] ?? null, fn (Builder $query, string $category) => $query->where('category', $category))
            ->when($filters['source'] ?? null, fn (Builder $query, string $source) => $query->where('source', $source))
            ->when($filters['contacted_at'] ?? null, fn (Builder $query, string $date) => $query->whereDate('contacted_at', '>=', $date))
            ->when($filters['responded_at'] ?? null, fn (Builder $query, string $date) => $query->whereDate('responded_at', '>=', $date));
    }
}
