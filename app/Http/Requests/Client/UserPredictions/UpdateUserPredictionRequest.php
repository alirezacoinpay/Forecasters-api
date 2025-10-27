<?php

namespace App\Http\Requests\Client\UserPredictions;

use App\Models\QuestionOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserPredictionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_option_id' => ['required', 'integer', Rule::exists(QuestionOption::class, 'id')],

        ];
    }
}
