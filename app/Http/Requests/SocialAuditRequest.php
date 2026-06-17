<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SocialAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'linkedin_bio' => ['nullable', 'string', 'max:2000'],
            'instagram_bio' => ['nullable', 'string', 'max:2000'],
            'tiktok_bio' => ['nullable', 'string', 'max:2000'],
            'portfolio_url' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:100'],
            'target_role' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $any = collect(['linkedin_bio', 'instagram_bio', 'tiktok_bio', 'portfolio_url'])
                ->contains(fn ($f) => filled($this->input($f)));
            if (! $any) {
                $v->errors()->add('linkedin_bio', 'Isi minimal satu bio atau link portfolio untuk diaudit.');
            }
        });
    }
}
