<?php

namespace App\Http\Requests\Client\QuestionForwards;

use Illuminate\Foundation\Http\FormRequest;


class AddQuestionForwardRequest extends FormRequest
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
