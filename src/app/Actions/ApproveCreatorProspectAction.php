<?php

namespace App\Actions;

use App\Models\Creator;
use App\Models\Prospect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApproveCreatorProspectAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(Prospect $prospect, array $attributes = []): Creator
    {
        if ($prospect->prospect_type !== Prospect::TYPE_CREATOR) {
            throw ValidationException::withMessages([
                'prospect' => ['Prospect is not a creator prospect.'],
            ]);
        }

        if ($prospect->status === Prospect::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'prospect' => ['Prospect is already approved.'],
            ]);
        }

        return DB::transaction(function () use ($prospect, $attributes): Creator {
            $creator = Creator::query()->create([
                'name' => $attributes['name'] ?? $prospect->name ?? $prospect->handle,
                'username' => $attributes['username'] ?? $prospect->handle,
                'email' => $attributes['email'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'bio' => $attributes['bio'] ?? null,
                'ugc_only' => $attributes['ugc_only'] ?? false,
                'accepts_barter' => $attributes['accepts_barter'] ?? true,
                'status' => Creator::STATUS_ACTIVE,
                'joined_at' => now(),
                'notes' => $attributes['notes'] ?? $prospect->notes,
            ]);

            if ($prospect->platform && $prospect->handle) {
                $creator->socialAccounts()->create([
                    'platform' => $prospect->platform,
                    'handle' => $prospect->handle,
                    'url' => $prospect->profile_url,
                    'is_primary' => true,
                ]);
            }

            $prospect->update([
                'status' => Prospect::STATUS_APPROVED,
                'approved_at' => now(),
            ]);

            return $creator->load(['cities', 'tags', 'socialAccounts']);
        });
    }
}
