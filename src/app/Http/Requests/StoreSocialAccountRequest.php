<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\Creator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSocialAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accountable_type' => ['required', Rule::in([Creator::class, Brand::class])],
            'accountable_id' => ['required', 'integer'],
            'platform' => ['required', 'string', 'max:255'],
            'handle' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
