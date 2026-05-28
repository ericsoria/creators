<?php

namespace App\Http\Resources;

use App\Models\OpportunityEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OpportunityEvent */
class OpportunityEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'opportunity_id' => $this->opportunity_id,
            'type' => $this->type,
            'old_status' => $this->old_status,
            'new_status' => $this->new_status,
            'message' => $this->message,
            'metadata' => $this->metadata,
            'created_by' => $this->created_by,
            'opportunity' => new OpportunityResource($this->whenLoaded('opportunity')),
            'created_user' => new UserResource($this->whenLoaded('createdBy')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
