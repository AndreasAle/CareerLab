<?php

namespace App\Http\Requests;

use App\Services\AI\RejectionAutopsyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RejectionAutopsyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'rejection_type' => ['required', Rule::in(array_keys(RejectionAutopsyService::TYPES))],
            'story' => ['required', 'string', 'min:20', 'max:6000'],
            'target_position' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'story.required' => 'Ceritakan dulu proses kegagalannya ya.',
            'story.min' => 'Ceritanya terlalu singkat untuk dianalisis.',
        ];
    }
}
