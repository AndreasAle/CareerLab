<?php

namespace App\Http\Requests;

use App\Services\AI\SalaryNegotiationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalarySimulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'target_position' => ['required', 'string', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'expected_salary' => ['nullable', 'string', 'max:100'],
            'offered_salary' => ['nullable', 'string', 'max:100'],
            'scenario' => ['required', Rule::in(array_keys(SalaryNegotiationService::SCENARIOS))],
            'user_answer' => ['required', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_answer.required' => 'Tulis dulu jawaban kamu menghadapi offering-nya.',
        ];
    }
}
