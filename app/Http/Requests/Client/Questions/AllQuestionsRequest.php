<?php

namespace App\Http\Requests\Client\Questions;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class AllQuestionsRequest extends FormRequest
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
