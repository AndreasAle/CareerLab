<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToxicJobScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'job_title' => ['nullable', 'string', 'max:150'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'job_description' => ['required', 'string', 'min:20', 'max:20000'],
        ];
    }

    public function messages(): array
    {
        return [
            'job_description.required' => 'Paste lowongan atau cerita interview-nya dulu ya.',
        ];
    }
}
