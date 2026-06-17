<?php

namespace App\Services\AI;

use App\Models\AiPromptTemplate;

/**
 * Loads prompt templates from the database (admin-editable, no redeploy)
 * and renders {{placeholders}} with provided values.
 */
class CareerPromptService
{
    /**
     * @return array{system: string, user: string}
     */
    public function render(string $key, array $vars = [], array $fallback = []): array
    {
        $template = AiPromptTemplate::where('key', $key)->where('is_active', true)->first();

        $system = $template->system_prompt ?? ($fallback['system'] ?? '');
        $user = $template->user_prompt_template ?? ($fallback['user'] ?? '');

        return [
            'system' => $this->interpolate($system, $vars),
            'user' => $this->interpolate($user, $vars),
        ];
    }

    protected function interpolate(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        // Remove any leftover unfilled placeholders.
        return preg_replace('/\{\{\s*\w+\s*\}\}/', '', $text);
    }
}
