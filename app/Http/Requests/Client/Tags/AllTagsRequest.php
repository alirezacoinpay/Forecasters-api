<?php

namespace App\Http\Requests\Client\Tags;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class AllTagsRequest extends FormRequest
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
