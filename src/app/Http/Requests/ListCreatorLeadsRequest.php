<?php

namespace App\Http\Requests;

use App\Models\CreatorLead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListCreatorLeadsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(CreatorLead::STATUSES)],
            'platform' => ['nullable', 'string', 'max:255'],
            'niche' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'contacted_at' => ['nullable', 'date'],
            'responded_at' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
