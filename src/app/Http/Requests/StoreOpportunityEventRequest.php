<?php

namespace App\Http\Requests;

use App\Models\Opportunity;
use App\Models\OpportunityEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOpportunityEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(OpportunityEvent::TYPES)],
            'old_status' => ['nullable', Rule::in(Opportunity::STATUSES)],
            'new_status' => ['nullable', Rule::in(Opportunity::STATUSES)],
            'message' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
