<?php

namespace App\Http\Requests;

use App\Models\Opportunity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListOpportunitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign' => ['nullable', 'integer', Rule::exists('campaigns', 'id')],
            'creator' => ['nullable', 'integer', Rule::exists('creators', 'id')],
            'status' => ['nullable', Rule::in(Opportunity::STATUSES)],
            'channel' => ['nullable', 'string', 'max:255'],
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'responded' => ['nullable', 'boolean'],
            'first_contacted_at' => ['nullable', 'date'],
            'last_contacted_at' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
