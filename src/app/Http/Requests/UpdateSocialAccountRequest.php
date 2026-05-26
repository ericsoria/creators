<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['sometimes', 'required', 'string', 'max:255'],
            'handle' => ['sometimes', 'required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
