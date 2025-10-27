<?php

namespace App\Http\Requests\Client\QuestionForwards;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionForwardRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
