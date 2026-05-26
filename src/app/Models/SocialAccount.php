<?php

namespace App\Models;

use Database\Factories\SocialAccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['accountable_type', 'accountable_id', 'platform', 'handle', 'url', 'is_primary'])]
class SocialAccount extends Model
{
    /** @use HasFactory<SocialAccountFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['platform'] ?? null, fn (Builder $query, string $platform) => $query->where('platform', $platform))
            ->when($filters['accountable_type'] ?? null, fn (Builder $query, string $type) => $query->where('accountable_type', $type))
            ->when($filters['accountable_id'] ?? null, fn (Builder $query, int $id) => $query->where('accountable_id', $id));
    }
}
