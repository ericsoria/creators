<?php

namespace App\Models;

use Database\Factories\OpportunityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['campaign_id', 'creator_id', 'status', 'channel', 'source_account', 'message_template', 'first_contacted_at', 'last_contacted_at', 'responded_at', 'follow_up_count', 'rejection_reason', 'notes', 'assigned_to', 'converted_to_collaboration_id'])]
class Opportunity extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_FOLLOW_UP = 'follow_up';

    public const STATUS_INTERESTED = 'interested';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_GHOSTED = 'ghosted';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_CONTACTED,
        self::STATUS_FOLLOW_UP,
        self::STATUS_INTERESTED,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
        self::STATUS_GHOSTED,
        self::STATUS_EXPIRED,
        self::STATUS_CANCELLED,
    ];

    public const TERMINAL_STATUSES = [
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
        self::STATUS_GHOSTED,
        self::STATUS_EXPIRED,
        self::STATUS_CANCELLED,
    ];

    /** @use HasFactory<OpportunityFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'first_contacted_at' => 'datetime',
            'last_contacted_at' => 'datetime',
            'responded_at' => 'datetime',
            'follow_up_count' => 'integer',
        ];
    }

    /** @return BelongsTo<Campaign, $this> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /** @return BelongsTo<Creator, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /** @return BelongsTo<User, $this> */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** @return HasMany<OpportunityEvent, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(OpportunityEvent::class);
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, self::TERMINAL_STATUSES, true);
    }

    /** @param Builder<Opportunity> $query */
    public function scopeActiveForPair(Builder $query, int $campaignId, int $creatorId): void
    {
        $query
            ->where('campaign_id', $campaignId)
            ->where('creator_id', $creatorId)
            ->whereNotIn('status', self::TERMINAL_STATUSES);
    }

    /** @param Builder<Opportunity> $query */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['campaign'] ?? null, fn (Builder $query, int $campaign) => $query->where('campaign_id', $campaign))
            ->when($filters['creator'] ?? null, fn (Builder $query, int $creator) => $query->where('creator_id', $creator))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['channel'] ?? null, fn (Builder $query, string $channel) => $query->where('channel', $channel))
            ->when($filters['assigned_to'] ?? null, fn (Builder $query, int $user) => $query->where('assigned_to', $user))
            ->when(array_key_exists('responded', $filters), fn (Builder $query) => $filters['responded'] ? $query->whereNotNull('responded_at') : $query->whereNull('responded_at'))
            ->when($filters['first_contacted_at'] ?? null, fn (Builder $query, string $date) => $query->whereDate('first_contacted_at', '>=', $date))
            ->when($filters['last_contacted_at'] ?? null, fn (Builder $query, string $date) => $query->whereDate('last_contacted_at', '<=', $date));
    }
}
