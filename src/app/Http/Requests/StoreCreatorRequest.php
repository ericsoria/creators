<?php

namespace App\Http\Requests;

use App\Models\Creator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:creators,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:creators,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'ugc_only' => ['nullable', 'boolean'],
            'accepts_barter' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(Creator::STATUSES)],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'joined_at' => ['nullable', 'date'],
            'last_active_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'city_ids' => ['sometimes', 'array'],
            'city_ids.*' => ['integer', Rule::exists('cities', 'id')],
            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => ['integer', Rule::exists('tags', 'id')],
        ];
    }
}
