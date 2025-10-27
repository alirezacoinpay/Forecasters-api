<?php

namespace App\Http\Requests\Client\Categories;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class AllCategoriesRequest extends FormRequest
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
