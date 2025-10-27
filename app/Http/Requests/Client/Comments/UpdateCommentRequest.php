<?php

namespace App\Http\Requests\Client\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['nullable', 'integer', Rule::exists('questions', 'id')],
            'parent_id' => ['nullable', 'integer', Rule::exists('comments', 'id')],
            'text' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,image,doc', 'max:10000'],
        ];
    }
}
