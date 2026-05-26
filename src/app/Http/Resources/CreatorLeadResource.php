<?php

namespace App\Http\Resources;

use App\Models\CreatorLead;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CreatorLead */
class CreatorLeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'platform' => $this->platform,
            'handle' => $this->handle,
            'profile_url' => $this->profile_url,
            'name' => $this->name,
            'city_name' => $this->city_name,
            'country_name' => $this->country_name,
            'niche' => $this->niche,
            'status' => $this->status,
            'contacted_at' => $this->contacted_at?->toISOString(),
            'responded_at' => $this->responded_at?->toISOString(),
            'approved_at' => $this->approved_at?->toISOString(),
            'rejection_reason' => $this->rejection_reason,
            'notes' => $this->notes,
            'source' => $this->source,
        ];
    }
}
