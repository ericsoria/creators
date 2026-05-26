<?php

namespace App\Actions;

use App\Models\Creator;
use App\Models\CreatorLead;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApproveCreatorLeadAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(CreatorLead $lead, array $attributes = []): Creator
    {
        if ($lead->status === CreatorLead::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'creator_lead' => ['Creator lead is already approved.'],
            ]);
        }

        return DB::transaction(function () use ($lead, $attributes): Creator {
            $creator = Creator::query()->create([
                'name' => $attributes['name'] ?? $lead->name ?? $lead->handle,
                'username' => $attributes['username'] ?? $lead->handle,
                'email' => $attributes['email'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'bio' => $attributes['bio'] ?? null,
                'ugc_only' => $attributes['ugc_only'] ?? false,
                'accepts_barter' => $attributes['accepts_barter'] ?? true,
                'status' => Creator::STATUS_ACTIVE,
                'joined_at' => now(),
                'notes' => $attributes['notes'] ?? $lead->notes,
            ]);

            $creator->socialAccounts()->create([
                'platform' => $lead->platform,
                'handle' => $lead->handle,
                'url' => $lead->profile_url,
                'is_primary' => true,
            ]);

            $lead->update([
                'status' => CreatorLead::STATUS_APPROVED,
                'approved_at' => now(),
            ]);

            return $creator->load(['cities', 'tags', 'socialAccounts']);
        });
    }
}
