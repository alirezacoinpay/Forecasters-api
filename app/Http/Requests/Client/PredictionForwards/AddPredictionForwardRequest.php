<?php

namespace App\Http\Requests\Client\PredictionForwards;

use App\Models\Prediction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AddPredictionForwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prediction_id' => ['required', 'integer', Rule::exists(Prediction::class, 'id')],
            'target' => ['required', 'string'],
        ];
    }
}
