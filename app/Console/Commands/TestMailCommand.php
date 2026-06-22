<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email}';

    protected $description = 'Send a test email to verify SMTP credentials';

    public function handle(): int
    {
        $to = $this->argument('email');

        $this->line('Mailer : ' . config('mail.default'));
        $this->line('Host   : ' . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port'));
        $this->line('Scheme : ' . (config('mail.mailers.smtp.scheme') ?: '(none)'));
        $this->line('User   : ' . config('mail.mailers.smtp.username'));
        $this->line('From   : ' . config('mail.from.address'));
        $this->newLine();

        try {
            Mail::raw('Tes koneksi SMTP CareerLab AI berhasil ✅ — ' . now(), function ($m) use ($to) {
                $m->to($to)->subject('Tes SMTP CareerLab AI');
            });
            $this->info("✅ Email terkirim ke {$to}. Cek inbox / folder spam.");
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('❌ Gagal kirim: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
