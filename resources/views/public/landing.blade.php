<x-public-layout title="CareerLab AI">
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-emerald-50 via-white to-purple-50"></div>
        <div class="mx-auto max-w-7xl px-4 py-20 text-center lg:px-8 lg:py-28">
            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700">
                🚀 Career Simulator untuk Gen Z & Fresh Graduate
            </span>
            <h1 class="mx-auto mt-6 max-w-3xl text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                Latihan Masuk Kerja <span class="bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-600 bg-clip-text text-transparent">Sebelum Ketemu HRD</span> Beneran.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600">
                Upload CV, cek cara HRD membaca profil kamu, latihan interview, deteksi red flag, dan siapkan lamaran kerja dengan CareerLab AI.
            </p>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 hover:opacity-95">Cek CV Gratis</a>
                <a href="#fitur" class="rounded-xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Lihat Fitur</a>
            </div>
        </div>
    </section>

    {{-- Pain points --}}
    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="grid gap-5 md:grid-cols-4">
            @foreach ([
                ['📭', 'CV sudah dikirim tapi tidak dipanggil?'],
                ['😰', 'Bingung jawab pertanyaan HRD?'],
                ['💸', 'Takut salah nego gaji?'],
                ['🔁', 'Tidak tahu kenapa sering gagal?'],
            ] as [$emoji, $text])
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <div class="text-3xl">{{ $emoji }}</div>
                    <p class="mt-3 text-sm font-medium text-slate-700">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section id="fitur" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-slate-900">Semua yang kamu butuh buat siap kerja</h2>
                <p class="mt-3 text-slate-600">7 fitur AI yang ngebantu kamu dari CV sampai 90 hari pertama kerja.</p>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['HRD Black Box CV Review', 'Lihat cara HRD baca CV kamu di 10-30 detik pertama, lengkap dengan ATS score.', 'from-emerald-500 to-emerald-600'],
                    ['Interview Drama Simulator', 'Latihan interview lawan AI HRD: mode friendly, strict, sampai galak mode.', 'from-blue-500 to-blue-600'],
                    ['Red Flag Scanner', 'Deteksi red flag di profil kamu sebelum HRD yang menemukannya.', 'from-purple-500 to-purple-600'],
                    ['Job Match Reality Check', 'Paste lowongan, cek match score CV kamu, dan haruskah kamu apply.', 'from-teal-500 to-teal-600'],
                    ['Toxic Workplace Detector', 'Baca tanda lowongan toxic & pertanyaan aman buat ditanya ke HR.', 'from-rose-500 to-rose-600'],
                    ['Salary Negotiation Simulator', 'Latihan nego gaji tanpa terlihat pasrah atau terlalu agresif.', 'from-amber-500 to-orange-600'],
                ] as [$title, $desc, $grad])
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                        <div class="mb-4 h-11 w-11 rounded-xl bg-gradient-to-br {{ $grad }}"></div>
                        <h3 class="font-semibold text-slate-800">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="mx-auto max-w-7xl px-4 py-20 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-slate-900">Cara Kerjanya</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-5">
            @foreach ([
                ['1', 'Upload CV'], ['2', 'Pilih target posisi'], ['3', 'Dapat diagnosis'], ['4', 'Latihan interview'], ['5', 'Apply lebih PD'],
            ] as [$n, $label])
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-blue-500 text-lg font-bold text-white">{{ $n }}</div>
                    <p class="mt-3 text-sm font-medium text-slate-700">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Preview result --}}
    <section class="bg-slate-900 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <h2 class="text-center text-3xl font-bold">Contoh Hasil Diagnosis</h2>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Career Readiness', '72'], ['CV Score', '68'], ['ATS Score', '62'], ['Interview Ready', '75'],
                ] as [$label, $score])
                    <div class="rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10">
                        <div class="text-4xl font-extrabold bg-gradient-to-r from-emerald-400 to-blue-400 bg-clip-text text-transparent">{{ $score }}</div>
                        <p class="mt-2 text-sm text-white/70">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="harga" class="mx-auto max-w-7xl px-4 py-20 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-slate-900">Harga yang ramah kantong job seeker</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($plans->whereIn('slug', ['free', 'starter', 'pro', 'serious']) as $plan)
                <div class="flex flex-col rounded-2xl border bg-white p-6 shadow-sm {{ $plan->slug === 'pro' ? 'border-emerald-400 ring-2 ring-emerald-400' : 'border-slate-200' }}">
                    @if ($plan->slug === 'pro')<span class="mb-2 inline-block rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-semibold text-emerald-700">Paling Populer</span>@endif
                    <h3 class="font-bold text-slate-800">{{ $plan->name }}</h3>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $plan->priceFormatted() }}</p>
                    <ul class="mt-4 flex-1 space-y-2 text-sm text-slate-600">
                        @foreach (($plan->features ?? []) as $f)
                            <li class="flex gap-2"><span class="text-emerald-500">✓</span> {{ $f }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" class="mt-6 rounded-xl bg-slate-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-slate-700">Pilih {{ $plan->name }}</a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <h2 class="text-center text-3xl font-bold text-slate-900">Kata mereka yang udah nyoba</h2>
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($testimonials as $t)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="text-amber-400">{!! str_repeat('★', $t->rating) !!}</div>
                            <p class="mt-3 text-sm text-slate-600">"{{ $t->content }}"</p>
                            <p class="mt-4 text-sm font-semibold text-slate-800">{{ $t->user_name }}</p>
                            <p class="text-xs text-slate-400">{{ $t->role }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    <section class="mx-auto max-w-3xl px-4 py-20 lg:px-8">
        <h2 class="text-center text-3xl font-bold text-slate-900">FAQ</h2>
        <div class="mt-10 space-y-3" x-data="{ open: null }">
            @foreach ([
                ['Apakah data CV saya aman?', 'Aman. Data CV hanya digunakan untuk analisis career di platform ini dan disimpan secara privat.'],
                ['Apakah AI menjamin saya diterima kerja?', 'Tidak. CareerLab AI membantu kamu lebih siap, tapi tidak membuat klaim pasti diterima kerja.'],
                ['Apakah ada versi gratis?', 'Ada. Kamu bisa coba 1x CV Review, 1x Job Match, dan 1x Interview secara gratis.'],
            ] as $i => [$q, $a])
                <div class="rounded-2xl border border-slate-200 bg-white">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-medium text-slate-800">
                        {{ $q }}
                        <span x-text="open === {{ $i }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === {{ $i }}" x-cloak class="px-5 pb-4 text-sm text-slate-600">{{ $a }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 px-8 py-14 text-center text-white">
            <h2 class="text-3xl font-bold">Siap bikin HRD nangkep value kamu?</h2>
            <p class="mt-3 text-white/85">Mulai gratis hari ini. Nggak perlu kartu kredit.</p>
            <a href="{{ route('register') }}" class="mt-6 inline-block rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-slate-900 hover:bg-slate-100">Cek CV Gratis Sekarang</a>
        </div>
    </section>
</x-public-layout>
