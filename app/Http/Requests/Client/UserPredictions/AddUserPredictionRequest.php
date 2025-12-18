<?php

namespace App\Http\Requests\Client\UserPredictions;

use App\Models\Prediction;
use App\Models\PredictionOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AddUserPredictionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prediction_option_id' => ['required', 'integer', Rule::exists(PredictionOption::class, 'id')],
            'comment.*' => ['nullable', 'array'],
            'comment.text' => ['nullable', 'string'],
            'comment.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webm', 'max:10000'],
        ];
    }
}
