<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => '7 Hal yang Dilihat HRD di 10 Detik Pertama Baca CV',
                'excerpt' => 'Ternyata HRD nggak baca CV kamu kata per kata. Ini yang mereka lihat duluan.',
                'content' => "HRD rata-rata hanya menghabiskan 6-10 detik untuk satu CV...\n\n1. Posisi terakhir & relevansi\n2. Durasi kerja\n3. Nama perusahaan sebelumnya\n4. Pendidikan\n5. Keyword posisi\n6. Format & kerapian\n7. Pencapaian terukur.\n\nFokus perbaiki bagian ini dulu sebelum yang lain.",
            ],
            [
                'title' => 'Cara Jawab "Kenapa Resign dari Tempat Lama?" Tanpa Jadi Red Flag',
                'excerpt' => 'Jawaban jujur belum tentu aman. Begini cara reframe-nya.',
                'content' => "Hindari menjelekkan tempat kerja lama. Fokus ke arah depan: pengembangan, tanggung jawab lebih besar, lingkungan yang lebih cocok...",
            ],
            [
                'title' => 'Tanda Lowongan Kerja Toxic yang Sering Diabaikan Fresh Graduate',
                'excerpt' => 'Gaji "kompetitif" tanpa angka? Itu salah satu sinyalnya.',
                'content' => "Beberapa tanda yang perlu diklarifikasi: job desc terlalu luas, gaji tidak transparan, unpaid trial, jam kerja tidak wajar...",
            ],
        ];

        foreach ($posts as $p) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'content' => $p['content'],
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }
    }
}
