<?php

namespace App\Services\AI;

use App\Models\User;

class SocialAuditService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    /**
     * Returns the decoded audit result (not persisted — MVP).
     *
     * @param  array<string,mixed>  $profile
     * @return array<string,mixed>
     */
    public function audit(User $user, array $profile): array
    {
        $rendered = $this->prompts->render('social_audit', [
            'social_profile_data' => $profile,
        ], [
            'system' => 'Kamu adalah personal branding coach untuk job seeker. Audit profil sosial media berdasarkan input manual user. Jangan melakukan scraping. Berikan saran yang aman dan profesional.',
            'user' => "Data sosial media:\n{{social_profile_data}}\n\nJSON: personal_branding_score, summary, problems[], improvements[], linkedin_bio_suggestion, instagram_bio_suggestion, portfolio_highlight_ideas[], before_apply_checklist[].",
        ]);

        return $this->ai->chatJson(
            featureKey: 'social_audit',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($profile),
        );
    }

    protected function mock(array $profile): array
    {
        $hasLinkedin = ! empty($profile['linkedin_bio']);
        $hasPortfolio = ! empty($profile['portfolio_url']);
        $score = 50 + ($hasLinkedin ? 15 : 0) + ($hasPortfolio ? 15 : 0);

        $problems = [];
        if (! $hasLinkedin) $problems[] = 'Bio LinkedIn kosong / belum jelas menunjukkan value.';
        if (! $hasPortfolio) $problems[] = 'Belum ada link portfolio yang menonjolkan karya.';
        if (! empty($profile['username']) && preg_match('/(galau|alay|\d{4,})/i', (string) $profile['username'])) {
            $problems[] = 'Username terkesan kurang profesional.';
            $score -= 10;
        }
        if (empty($problems)) $problems[] = 'Profil sudah lumayan, tinggal dipertajam.';

        return [
            'personal_branding_score' => max(20, min(95, $score)),
            'summary' => 'Personal branding kamu sudah ada fondasinya, tapi belum maksimal menjual value ke HRD.',
            'problems' => $problems,
            'improvements' => [
                'Lengkapi headline LinkedIn dengan peran + value + target',
                'Pin 2-3 konten/karya terbaik di profil',
                'Pastikan foto profil rapi & profesional',
            ],
            'linkedin_bio_suggestion' => ($profile['target_role'] ?? 'Profesional') . ' | Membantu tim mencapai hasil lewat ' . ($profile['skill'] ?? 'keahlian utama') . '. Terbuka untuk peluang baru.',
            'instagram_bio_suggestion' => '🎯 ' . ($profile['target_role'] ?? 'Aspiring Professional') . ' · Belajar & berkarya · Portfolio di link 👇',
            'portfolio_highlight_ideas' => [
                'Studi kasus 1 proyek terbaik dengan hasil terukur',
                'Before-after dari karya kamu',
                'Testimoni singkat dari mentor/klien',
            ],
            'before_apply_checklist' => [
                'LinkedIn updated & headline jelas',
                'Foto profil profesional',
                'Tidak ada konten sensitif yang publik',
                'Username profesional',
                'Link portfolio aktif',
            ],
        ];
    }
}
