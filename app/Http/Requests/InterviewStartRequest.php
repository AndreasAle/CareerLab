<?php

namespace App\Http\Requests;

use App\Services\AI\InterviewService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterviewStartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'target_position' => ['required', 'string', 'max:150'],
            'hrd_mode' => ['required', Rule::in(array_keys(InterviewService::HRD_MODES))],
            'difficulty' => ['required', Rule::in(array_keys(InterviewService::DIFFICULTIES))],
        ];
    }

    public function messages(): array
    {
        return [
            'target_position.required' => 'Isi dulu posisi yang mau kamu latih.',
        ];
    }
}
