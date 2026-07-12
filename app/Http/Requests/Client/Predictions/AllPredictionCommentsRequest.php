<?php

namespace App\Http\Requests\Client\Predictions;

use App\Models\PredictionOption;
use App\Models\Topic;
use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllPredictionCommentsRequest extends FormRequest
{
    use HasIndexRules;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return $this->mergeRules([

        ]);
    }
}
