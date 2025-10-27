<?php

namespace App\Http\Requests\Client\QuestionForwards;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class AllQuestionForwardsRequest extends FormRequest
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
