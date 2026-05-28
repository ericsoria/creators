<?php

namespace App\Http\Requests;

use App\Models\Prospect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProspectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prospect_type' => ['sometimes', 'required', Rule::in(Prospect::TYPES)],
            'platform' => ['sometimes', 'required', 'string', 'max:255'],
            'handle' => ['sometimes', 'required', 'string', 'max:255'],
            'profile_url' => ['nullable', 'url', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'city_name' => ['nullable', 'string', 'max:255'],
            'country_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(Prospect::STATUSES)],
            'contacted_at' => ['nullable', 'date'],
            'responded_at' => ['nullable', 'date'],
            'rejection_reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
        ];
    }
}
