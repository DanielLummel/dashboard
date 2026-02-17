<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSnippetRequest extends FormRequest
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
            'project_id' => [
                'nullable',
                'integer',
                Rule::exists('projects', 'id')->where('user_id', $userId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:64'],
            'code' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
