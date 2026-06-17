<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Dina, 23', 'Fresh Graduate', 'Baru sadar CV-ku selama ini ngebosenin buat HRD. Setelah diperbaiki pakai saran CareerLab, aku dipanggil 3 interview dalam 2 minggu!', 5],
            ['Rafi, 25', 'Career Switcher', 'Interview Drama Simulator-nya bikin aku nggak grogi lagi. Mode HRD Galak beneran ngelatih mental.', 5],
            ['Sasa, 22', 'Job Seeker', 'Toxic Workplace Detector nyelametin aku dari lowongan yang ternyata red flag banget.', 4],
            ['Bagas, 24', 'Junior Developer', 'Salary simulator-nya ngebantu aku nego naik 1.5 juta dari offering pertama.', 5],
        ];

        foreach ($items as [$name, $role, $content, $rating]) {
            Testimonial::updateOrCreate(
                ['user_name' => $name, 'content' => $content],
                ['role' => $role, 'rating' => $rating, 'is_active' => true]
            );
        }
    }
}
