<?php

namespace App\Http\Resources;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SocialAccount */
class SocialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'accountable_type' => $this->accountable_type,
            'accountable_id' => $this->accountable_id,
            'platform' => $this->platform,
            'handle' => $this->handle,
            'url' => $this->url,
            'is_primary' => $this->is_primary,
        ];
    }
}
