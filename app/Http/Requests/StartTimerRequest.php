<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartTimerRequest extends FormRequest
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
                'required',
                'integer',
                Rule::exists('projects', 'id')->where('user_id', $userId),
            ],
            'task_label' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
