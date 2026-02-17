<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'content_markdown' => ['required', 'string'],
            'is_favorite' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'project_ids' => ['nullable', 'array'],
            'project_ids.*' => [
                'integer',
                Rule::exists('projects', 'id')->where('user_id', $userId),
            ],
        ];
    }
}
