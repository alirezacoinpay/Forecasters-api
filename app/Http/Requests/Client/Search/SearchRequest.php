<?php

namespace App\Http\Requests\Client\Search;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    use HasIndexRules;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->mergeRules([
            'search' => ['nullable', 'string', 'max:255'],
            'tag_id' => ['nullable', 'integer', Rule::exists('tags', 'id')],
        ]);
    }
}
