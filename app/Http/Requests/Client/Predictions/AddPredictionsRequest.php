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
            'text' => ['nullable', 'string'],
            'topic_id' => ['required', Rule::exists(Topic::class, 'id')],
            'starts_at' => ['nullable', Rule::date()->format("Y-m-d H:i")],
            'ends_at' => ['nullable', 'date'],
            'options' => ['nullable', 'array'],
        ];
    }
}
