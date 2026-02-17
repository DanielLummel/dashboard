<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
        return [
            'rounding_minutes' => ['required', 'integer', 'in:5,10,15,30'],
            'week_start' => ['required', 'string', 'in:Mon,Sun'],
            'timezone' => ['required', 'string', 'timezone'],
        ];
    }
}
