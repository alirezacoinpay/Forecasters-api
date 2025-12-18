<?php

namespace App\Http\Requests\Client\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AddCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prediction_id' => ['required', 'integer', Rule::exists('predictions', 'id')],
            'parent_id' => ['nullable', 'integer', Rule::exists('comments', 'id')],
            'text' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:image', 'max:10000'],
        ];
    }
}
