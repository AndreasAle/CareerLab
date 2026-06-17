<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedFlagScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'cv_upload_id' => ['nullable', 'integer', 'exists:cv_uploads,id'],
            'target_position' => ['required', 'string', 'max:150'],
            'current_status' => ['nullable', 'string', 'max:100'],
            'experience' => ['nullable', 'string', 'max:1000'],
            'work_gap' => ['nullable', 'string', 'max:1000'],
            'resign_reason' => ['nullable', 'string', 'max:1000'],
            'career_target' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'target_position.required' => 'Isi target posisi kamu dulu.',
        ];
    }
}
