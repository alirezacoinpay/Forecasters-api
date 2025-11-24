<?php

namespace App\Http\Requests\Client\Feed;

use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;

class FeedsRequest extends FormRequest
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
