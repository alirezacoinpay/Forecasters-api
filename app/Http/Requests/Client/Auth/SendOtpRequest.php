<?php

namespace App\Http\Requests\Client\Auth;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
        ];
    }
}
