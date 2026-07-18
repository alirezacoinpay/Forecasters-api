<?php

namespace App\Http\Requests\Client\UserProfiles;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', Rule::file()],
        ];
    }
}
