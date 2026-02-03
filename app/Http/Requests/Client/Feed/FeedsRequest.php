<?php

namespace App\Http\Requests\Client\Feed;

use App\Models\Tag;
use App\Models\Topic;
use App\Traits\HasIndexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'topic_id' => ['nullable', 'integer', Rule::exists(Topic::class, 'id')],
            'tag_id' => ['nullable', 'integer', Rule::exists(Tag::class, 'id')],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
