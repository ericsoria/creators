<?php

namespace App\Models;

use Database\Factories\OpportunityEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['opportunity_id', 'type', 'old_status', 'new_status', 'message', 'metadata', 'created_by'])]
class OpportunityEvent extends Model
{
    public const TYPE_CONTACTED = 'contacted';

    public const TYPE_FOLLOW_UP_SENT = 'follow_up_sent';

    public const TYPE_CREATOR_REPLIED = 'creator_replied';

    public const TYPE_ACCEPTED = 'accepted';

    public const TYPE_REJECTED = 'rejected';

    public const TYPE_GHOSTED = 'ghosted';

    public const TYPE_NOTE = 'note';

    public const TYPES = [
        self::TYPE_CONTACTED,
        self::TYPE_FOLLOW_UP_SENT,
        self::TYPE_CREATOR_REPLIED,
        self::TYPE_ACCEPTED,
        self::TYPE_REJECTED,
        self::TYPE_GHOSTED,
        self::TYPE_NOTE,
    ];

    /** @use HasFactory<OpportunityEventFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /** @return BelongsTo<Opportunity, $this> */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
