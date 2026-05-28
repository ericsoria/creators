<?php

namespace App\Http\Requests;

use App\Models\Prospect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListProspectsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prospect_type' => ['nullable', Rule::in(Prospect::TYPES)],
            'status' => ['nullable', Rule::in(Prospect::STATUSES)],
            'platform' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'contacted_at' => ['nullable', 'date'],
            'responded_at' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
