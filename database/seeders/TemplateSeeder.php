<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'title' => 'CV Summary - Fresh Graduate',
                'type' => 'cv',
                'description' => 'Ringkasan profil singkat untuk fresh graduate.',
                'content' => "Fresh graduate {jurusan} yang antusias di bidang {bidang}. Terbiasa {skill utama} melalui proyek kuliah dan magang. Mencari peran {posisi} untuk memberi dampak nyata sambil terus berkembang.",
                'is_premium' => false,
            ],
            [
                'title' => 'Email Lamaran Profesional',
                'type' => 'email_lamaran',
                'description' => 'Template email melamar yang rapi dan sopan.',
                'content' => "Yth. Tim Rekrutmen {Perusahaan},\n\nSaya {Nama} tertarik melamar posisi {Posisi} yang Bapak/Ibu publikasikan. Dengan latar belakang {latar belakang}, saya yakin dapat berkontribusi pada {kebutuhan perusahaan}.\n\nTerlampir CV saya. Terima kasih atas waktu dan pertimbangannya.\n\nHormat saya,\n{Nama}\n{Kontak}",
                'is_premium' => false,
            ],
            [
                'title' => 'Follow Up HR Setelah Interview',
                'type' => 'follow_up_hr',
                'description' => 'Pesan follow up sopan setelah interview.',
                'content' => "Halo {Nama HR}, terima kasih atas kesempatan interview untuk posisi {Posisi} kemarin. Saya semakin tertarik dengan peran ini setelah berdiskusi. Jika ada informasi lanjutan terkait proses seleksi, saya akan senang menerimanya. Terima kasih!",
                'is_premium' => false,
            ],
            [
                'title' => 'Jawaban "Kelemahan Kamu Apa?"',
                'type' => 'interview_answer',
                'description' => 'Framework menjawab pertanyaan kelemahan.',
                'content' => "Salah satu hal yang sedang saya kembangkan adalah {kelemahan ringan yang relevan}. Saya menyadarinya saat {situasi}, dan sejak itu saya {langkah perbaikan konkret}. Hasilnya, {perbaikan terukur}.",
                'is_premium' => true,
            ],
            [
                'title' => 'LinkedIn Bio Career-Friendly',
                'type' => 'linkedin_bio',
                'description' => 'Bio LinkedIn yang menjual value.',
                'content' => "{Peran} | Membantu {target} mencapai {hasil} lewat {keahlian}. Terbuka untuk peluang di {industri}.",
                'is_premium' => true,
            ],
            [
                'title' => 'Alasan Resign Profesional',
                'type' => 'resign_reason',
                'description' => 'Reframe alasan resign agar tidak jadi red flag.',
                'content' => "Saya mencari lingkungan kerja yang lebih terstruktur dan mendukung pengembangan profesional saya, serta peran yang lebih sesuai dengan arah karier jangka panjang saya.",
                'is_premium' => false,
            ],
            [
                'title' => 'Script Negosiasi Gaji',
                'type' => 'salary_negotiation',
                'description' => 'Kerangka menjawab offering gaji.',
                'content' => "Terima kasih atas penawarannya. Berdasarkan pengalaman saya di {bidang} dan kontribusi yang bisa saya berikan, saya berharap di kisaran {angka}. Apakah ada ruang untuk mendekati angka tersebut?",
                'is_premium' => true,
            ],
        ];

        foreach ($templates as $t) {
            Template::updateOrCreate(['title' => $t['title']], $t + ['is_active' => true]);
        }
    }
}
