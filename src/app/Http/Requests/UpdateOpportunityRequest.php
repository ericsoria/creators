<?php

namespace App\Http\Requests;

use App\Models\Opportunity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['sometimes', 'required', 'integer', Rule::exists('campaigns', 'id')],
            'creator_id' => ['sometimes', 'required', 'integer', Rule::exists('creators', 'id')],
            'status' => ['sometimes', 'required', Rule::in(Opportunity::STATUSES)],
            'channel' => ['nullable', 'string', 'max:255'],
            'source_account' => ['nullable', 'string', 'max:255'],
            'message_template' => ['nullable', 'string'],
            'first_contacted_at' => ['nullable', 'date'],
            'last_contacted_at' => ['nullable', 'date'],
            'responded_at' => ['nullable', 'date'],
            'follow_up_count' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'rejection_reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'converted_to_collaboration_id' => ['nullable', 'integer'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $opportunity = $this->route('opportunity');
            $campaignId = $this->integer('campaign_id') ?: $opportunity?->campaign_id;
            $creatorId = $this->integer('creator_id') ?: $opportunity?->creator_id;

            if (! $opportunity || ! $campaignId || ! $creatorId) {
                return;
            }

            if (Opportunity::query()->activeForPair($campaignId, $creatorId)->whereKeyNot($opportunity->id)->exists()) {
                $validator->errors()->add('creator_id', 'An active opportunity already exists for this campaign and creator.');
            }
        });
    }
}
