<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveCreatorProspectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:creators,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:creators,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'ugc_only' => ['nullable', 'boolean'],
            'accepts_barter' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
