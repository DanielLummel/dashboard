<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTimeEntryRequest extends FormRequest
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
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'tags' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $hasEnd = (bool) $this->input('end_at');
            $hasDuration = (bool) $this->input('duration_minutes');

            if (! $hasEnd && ! $hasDuration) {
                $validator->errors()->add('end_at', 'Bitte Endzeit oder Dauer in Minuten angeben.');
            }
        });
    }
}
