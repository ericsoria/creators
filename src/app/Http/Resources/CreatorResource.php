<?php

namespace App\Http\Resources;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Creator */
class CreatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'ugc_only' => $this->ugc_only,
            'accepts_barter' => $this->accepts_barter,
            'status' => $this->status,
            'rating' => $this->rating,
            'joined_at' => $this->joined_at?->toISOString(),
            'last_active_at' => $this->last_active_at?->toISOString(),
            'notes' => $this->notes,
            'cities' => CityResource::collection($this->whenLoaded('cities')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'social_accounts' => SocialAccountResource::collection($this->whenLoaded('socialAccounts')),
        ];
    }
}
