<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UploadCvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB
            'manual_text' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if (! $this->hasFile('cv_file') && blank($this->input('manual_text'))) {
                $v->errors()->add('cv_file', 'Upload file PDF atau tempel teks CV kamu dulu ya.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'cv_file.mimes' => 'CV harus berupa file PDF.',
            'cv_file.max' => 'Ukuran CV maksimal 5MB.',
        ];
    }
}
