<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Brand */
class BrandResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'industry' => $this->industry,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'status' => $this->status,
            'notes' => $this->notes,
            'cities' => CityResource::collection($this->whenLoaded('cities')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'social_accounts' => SocialAccountResource::collection($this->whenLoaded('socialAccounts')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
