<?php

namespace App\Http\Requests;

use App\Models\ApplicationTracker;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'job_source' => ['nullable', 'string', 'max:255'],
            'applied_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(ApplicationTracker::STATUSES)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'follow_up_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Isi nama perusahaan.',
            'position.required' => 'Isi posisi yang dilamar.',
        ];
    }
}
