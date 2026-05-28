<?php

namespace App\Http\Resources;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Opportunity */
class OpportunityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'creator_id' => $this->creator_id,
            'status' => $this->status,
            'channel' => $this->channel,
            'source_account' => $this->source_account,
            'message_template' => $this->message_template,
            'first_contacted_at' => $this->first_contacted_at?->toISOString(),
            'last_contacted_at' => $this->last_contacted_at?->toISOString(),
            'responded_at' => $this->responded_at?->toISOString(),
            'follow_up_count' => $this->follow_up_count,
            'rejection_reason' => $this->rejection_reason,
            'notes' => $this->notes,
            'assigned_to' => $this->assigned_to,
            'converted_to_collaboration_id' => $this->converted_to_collaboration_id,
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
            'creator' => new CreatorResource($this->whenLoaded('creator')),
            'assigned_user' => new UserResource($this->whenLoaded('assignedUser')),
            'events' => OpportunityEventResource::collection($this->whenLoaded('events')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
