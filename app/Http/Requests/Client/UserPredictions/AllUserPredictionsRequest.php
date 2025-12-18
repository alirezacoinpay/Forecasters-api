<?php

namespace App\Http\Requests\Client\UserPredictions;

use App\Models\Prediction;
use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllUserPredictionsRequest extends FormRequest
{
    use HasIndexRules;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->mergeRules([
            'prediction_id' => ['nullable', 'integer', Rule::exists(Prediction::class, 'id')],
        ]);
    }
}
