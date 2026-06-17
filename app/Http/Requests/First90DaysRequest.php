<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class First90DaysRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'position' => ['required', 'string', 'max:150'],
            'industry' => ['required', 'string', 'max:150'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'main_concern' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'position.required' => 'Isi posisi barumu dulu.',
            'industry.required' => 'Isi industri tempat kamu kerja.',
        ];
    }
}
