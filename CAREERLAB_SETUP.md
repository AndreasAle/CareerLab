# CareerLab AI — Setup Guide

Career simulator SaaS (Laravel 12 + Blade + Tailwind + Alpine + MySQL).

## Status pembangunan

| Phase | Cakupan | Status |
|-------|---------|--------|
| 1 | Laravel + Breeze auth + role middleware (admin/coach/user) + layout public/dashboard/panel + landing + dashboard user | ✅ Selesai |
| 2 | 30 tabel migration + semua model + relasi + 7 seeder + `SubscriptionService` (helper limit) | ✅ Selesai |
| 3 | AI service layer (`AiService` + mock fallback + `ai_logs`) + `CareerPromptService` + ekstraksi teks PDF + **HRD Black Box CV Review** end-to-end | ✅ Selesai |
| 4 | **Interview Drama Simulator** end-to-end (6 mode HRD, chat, skor per jawaban, laporan akhir) | ✅ Selesai |
| 5 | **Job Match Reality Check + Red Flag Scanner + Toxic Workplace Detector** (single-shot, form→hasil) | ✅ Selesai |
| 6 | **Salary Negotiation Simulator + Rejection Autopsy + Social Media Audit + First 90 Days Plan** | ✅ Selesai |
| 7 | **Career Diagnosis Report PDF (DomPDF) + Application Tracker (CRUD+stats) + Template Library + Challenge 7 Hari** | ✅ Selesai |
| 8 | **Admin panel CRUD (Users/Plans/Templates/AI Prompts/Orders/Blog/Testimonials/AI Logs/Consultations) + Coach panel + Orders/payment manual → approve → subscription + Consultation booking** | ✅ Selesai |
| 9 | Polish UI, security pass, bug fixing akhir (opsional) | ⏳ Berikutnya |

> **Pembayaran manual sudah berfungsi penuh:** user pilih paket → order unpaid → upload bukti transfer → admin approve → langganan otomatis aktif. Integrasi gateway otomatis (Midtrans/Xendit) belum dibuat (struktur enum sudah siap).

Semua **prompt AI** untuk fitur 4–9 sudah di-seed ke tabel `ai_prompt_templates` dan siap dipakai.

## 1. Requirement
- PHP 8.2+, Composer 2.x, Node 18+, MySQL (XAMPP).

## 2. Setup .env
File `.env` sudah dikonfigurasi. Yang perlu kamu isi:
```env
DB_DATABASE=careerlab_ai
DB_USERNAME=root
DB_PASSWORD=

# OpenAI — isi key kamu untuk AI asli
OPENAI_API_KEY=sk-xxxx
OPENAI_MODEL=gpt-4o-mini
# Set false agar memakai OpenAI asli. Saat true (atau key kosong),
# semua fitur AI pakai mock JSON realistis (gratis, untuk testing).
AI_FALLBACK_MOCK=true
```

## 3. Instalasi
```bash
composer install
npm install
php artisan migrate:fresh --seed   # buat skema + data awal
npm run build                       # atau: npm run dev
php artisan serve
```

## 4. Akun default
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@careerlab.test | password |
| Coach | coach@careerlab.test | password |
| User | user@careerlab.test | password |

Login → otomatis diarahkan ke panel sesuai role (`/admin`, `/coach`, `/dashboard`).

## 5. Menjalankan Queue
Queue memakai database driver (untuk job AI/PDF di phase berikutnya):
```bash
php artisan queue:work
```
Saat ini CV Review dijalankan sinkron (tanpa queue) agar UX sederhana.

## 6. Testing manual (sudah lolos otomatis juga)
```bash
php artisan test --filter=SmokeTest
```
Skenario yang sudah diverifikasi:
- Landing, pricing, blog, login dapat diakses.
- User register/login → dashboard sesuai role.
- Upload CV (PDF **atau** tempel teks manual) → tersimpan privat di `storage/app/private/cv/{userId}`.
- Generate CV Review → hasil tersimpan ke `cv_reviews`, tampil dengan score ring, red flag, dll.
- User hanya bisa melihat data miliknya (403 untuk milik orang lain).
- Coach/Admin tidak bisa akses route user-only.

## 7. Arsitektur AI (mudah ganti provider)
- `app/Services/AI/AiService.php` — wrapper provider-agnostic, retry, logging ke `ai_logs`, validasi JSON, **mock fallback** anti-500.
- `app/Services/AI/CareerPromptService.php` — load prompt dari DB (admin bisa edit tanpa deploy ulang) + render `{{placeholder}}`.
- `app/Services/AI/CvReviewService.php` — contoh implementasi fitur (pola yang sama dipakai fitur lain di phase berikut).
- Guardrail prompt injection: teks CV/job desc diperlakukan sebagai **data**, bukan instruksi.

## 8. Keamanan yang sudah diterapkan
- Upload hanya PDF maks 5MB (`UploadCvRequest`), disimpan di disk privat.
- Otorisasi kepemilikan data di setiap controller.
- Middleware role-based (`role:admin|coach|user`) + cek akun aktif.
- Rate limit pada endpoint AI (`throttle:20,1`).
- API key hanya di server (`.env` / `config/services.php`).

## 9. Catatan fitur yang BELUM production
- **Payment**: struktur tabel `orders`/`subscriptions` sudah ada + enum Midtrans/Xendit/QRIS/manual. MVP masih **manual transfer**; integrasi gateway asli belum dibuat.
- **Queue/PDF**: job class & generate PDF report dibuat di phase 7.
- **Fitur 4–9**: controller/UI belum dibuat (prompt sudah siap di DB).
