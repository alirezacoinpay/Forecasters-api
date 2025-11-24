<?php

namespace App\Http\Requests\Client\QuestionForwards;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AddQuestionForwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', Rule::exists(Question::class, 'id')],
            'target' => ['required', 'string'],
        ];
    }
}
