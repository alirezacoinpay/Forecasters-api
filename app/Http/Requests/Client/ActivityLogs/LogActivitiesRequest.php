<?php
namespace App\Http\Requests\Client\ActivityLogs;

use App\Enums\ActivityAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LogActivitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', Rule::in(ActivityAction::notStacks())],
            'subject_id' => ['nullable', 'integer'],
            'subject_type' => ['nullable', 'string', 'max:100'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
