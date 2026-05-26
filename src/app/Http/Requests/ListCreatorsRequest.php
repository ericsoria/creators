<?php

namespace App\Http\Requests;

use App\Models\Creator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListCreatorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(Creator::STATUSES)],
            'city' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'tag' => ['nullable', 'integer', Rule::exists('tags', 'id')],
            'ugc_only' => ['nullable', 'boolean'],
            'accepts_barter' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
