<?php

namespace App\Http\Requests\Client\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user()->id)],
        ];
    }
}
