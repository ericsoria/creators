<?php

namespace App\Actions;

use App\Models\Opportunity;
use App\Models\OpportunityEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcceptOpportunityAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Opportunity $opportunity, User $user, array $data = []): Opportunity
    {
        if ($opportunity->isTerminal()) {
            throw ValidationException::withMessages([
                'status' => 'Terminal opportunities cannot be accepted again.',
            ]);
        }

        return DB::transaction(function () use ($opportunity, $user, $data): Opportunity {
            $oldStatus = $opportunity->status;

            $opportunity->forceFill([
                'status' => Opportunity::STATUS_ACCEPTED,
                'responded_at' => $opportunity->responded_at ?? now(),
            ])->save();

            $opportunity->events()->create([
                'type' => OpportunityEvent::TYPE_ACCEPTED,
                'old_status' => $oldStatus,
                'new_status' => Opportunity::STATUS_ACCEPTED,
                'message' => $data['message'] ?? null,
                'metadata' => $data['metadata'] ?? null,
                'created_by' => $user->id,
            ]);

            return $opportunity->refresh();
        });
    }
}
