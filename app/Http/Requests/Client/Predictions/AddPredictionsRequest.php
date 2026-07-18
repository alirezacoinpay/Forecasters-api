<?php

namespace App\Http\Requests\Client\Predictions;

use App\Models\PredictionOption;
use App\Models\Topic;
use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPredictionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'ends_at' => ['nullable', 'date'],
            'options' => ['nullable', 'array'],
        ];
    }
}
