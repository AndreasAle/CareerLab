<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'cv_upload_id' => ['nullable', 'integer', 'exists:cv_uploads,id'],
            'job_title' => ['required', 'string', 'max:150'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'job_description' => ['required', 'string', 'min:30', 'max:20000'],
        ];
    }

    public function messages(): array
    {
        return [
            'job_description.required' => 'Paste deskripsi lowongannya dulu ya.',
            'job_description.min' => 'Deskripsi lowongan terlalu pendek untuk dianalisis.',
        ];
    }
}
