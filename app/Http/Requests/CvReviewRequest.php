<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CvReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'target_position' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'target_position.required' => 'Isi dulu target posisi yang kamu incar.',
        ];
    }
}
