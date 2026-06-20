<x-public-layout title="CareerLab AI">
    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute right-0 top-20 h-96 w-96 rounded-full bg-violet-200/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-emerald-100/50 blur-3xl"></div>
        </div>

        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 lg:grid-cols-2 lg:px-8 lg:py-24">
            {{-- left --}}
            <div class="reveal">
                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50/70 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-2 w-2 animate-ping rounded-full bg-indigo-400 opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-indigo-500"></span></span>
                    Career Simulator bertenaga AI
                </span>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                    Siapkan kariermu, <br class="hidden sm:block">
                    <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent">taklukkan HRD</span> hari ini
                </h1>
                <p class="mt-5 max-w-lg text-lg leading-relaxed text-slate-500">
                    Upload CV, lihat cara HRD membaca profilmu dalam 10 detik, latihan interview, deteksi red flag, dan apply lebih percaya diri.
                </p>

                {{-- search-style CTA --}}
                <form action="{{ route('free.cv') }}" method="GET" class="mt-8 flex flex-col gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-200/60 sm:flex-row sm:items-center">
                    <div class="flex flex-1 items-center gap-2 px-3">
                        <x-icon name="target" class="h-5 w-5 text-slate-400"/>
                        <input type="text" name="position" placeholder="Posisi yang kamu incar (cth: Backend Developer)" class="w-full border-0 py-2.5 text-sm placeholder:text-slate-400 focus:ring-0">
                    </div>
                    <button class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.02]">
                        <x-icon name="spark" class="h-4 w-4"/> Cek CV Gratis
                    </button>
                </form>

                <div class="mt-5">
                    <p class="text-xs font-medium text-slate-400">Langsung coba, tanpa daftar:</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach (['CV Review','Interview','Job Match','Red Flag','Salary Nego'] as $chip)
                            <a href="{{ route('free.cv') }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600">{{ $chip }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-6">
                    <div><p class="text-2xl font-bold">10+</p><p class="text-xs text-slate-400">Fitur AI</p></div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div><p class="text-2xl font-bold">6</p><p class="text-xs text-slate-400">Mode HRD</p></div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div><p class="text-2xl font-bold">Gen Z</p><p class="text-xs text-slate-400">Friendly</p></div>
                </div>
            </div>

            {{-- right: product mock in shaped frame --}}
            <div class="reveal relative" style="--reveal-delay:150ms">
                <div class="absolute inset-6 -z-10 cl-blob bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 opacity-90"></div>
                <div class="relative rounded-[2rem] bg-white/90 p-5 shadow-2xl ring-1 ring-black/5 backdrop-blur">
                    {{-- mock header --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 text-white"><x-icon name="doc" class="h-4 w-4"/></div>
                            <div><p class="text-sm font-semibold">CV — Backend Developer</p><p class="text-[11px] text-slate-400">Dianalisis oleh AI HRD</p></div>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-600">Selesai</span>
                    </div>
                    {{-- score row --}}
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        @foreach ([['Overall','72','from-emerald-400 to-emerald-600'],['ATS','68','from-blue-400 to-blue-600'],['Interview','75','from-violet-400 to-purple-600']] as [$lbl,$val,$grad])
                            <div class="rounded-xl border border-slate-100 bg-white p-3 text-center">
                                <div class="mx-auto mb-1 grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br {{ $grad }} text-xs font-bold text-white">{{ $val }}</div>
                                <p class="text-[11px] text-slate-400">{{ $lbl }}</p>
                            </div>
                        @endforeach
                    </div>
                    {{-- bars --}}
                    <div class="mt-4 space-y-3">
                        @foreach ([['Kekuatan CV',82,'#10b981'],['Keyword match',64,'#6366f1'],['Kejelasan impact',58,'#a855f7']] as [$lbl,$w,$c])
                            <div>
                                <div class="mb-1 flex justify-between text-[11px]"><span class="text-slate-500">{{ $lbl }}</span><span class="font-semibold">{{ $w }}%</span></div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="cl-bar h-full rounded-full" style="width:{{ $w }}%;background:{{ $c }}"></div></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- floating cards --}}
                <div class="cl-float absolute -left-4 top-12 hidden rounded-2xl border border-slate-100 bg-white p-3 shadow-xl sm:block">
                    <div class="flex items-center gap-2">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-emerald-100 text-emerald-600"><x-icon name="check-c" class="h-5 w-5"/></div>
                        <div><p class="text-xs font-bold">Dipanggil interview</p><p class="text-[10px] text-slate-400">Peluang naik 2×</p></div>
                    </div>
                </div>
                <div class="cl-float absolute -bottom-4 right-2 hidden rounded-2xl border border-slate-100 bg-white p-3 shadow-xl sm:block" style="animation-delay:1.5s">
                    <div class="flex items-center gap-2">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-violet-100 text-violet-600"><x-icon name="chat" class="h-5 w-5"/></div>
                        <div><p class="text-xs font-bold">Mock interview</p><p class="text-[10px] text-slate-400">6 mode HRD</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TOOLS MARQUEE ============ --}}
    <section class="border-y border-slate-100 bg-slate-50/70 py-10">
        <p class="mb-6 text-center text-xs font-semibold uppercase tracking-widest text-slate-400">Semua alat yang kamu butuh dari CV sampai 90 hari pertama</p>
        <div class="cl-marquee-track relative overflow-hidden [mask-image:linear-gradient(90deg,transparent,black_12%,black_88%,transparent)]">
            <div class="cl-marquee flex w-max gap-4">
                @php $tools = [['doc','CV Review','emerald'],['chat','Interview','violet'],['target','Job Match','blue'],['shield','Red Flag','rose'],['fire','Toxic Detector','amber'],['graph','Salary Nego','emerald'],['compass','Rejection','indigo'],['users','Social Audit','violet'],['rocket','First 90 Days','blue'],['spark','Career Report','purple']]; @endphp
                @foreach (array_merge($tools, $tools) as [$ic,$name,$clr])
                    <div class="flex shrink-0 items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-{{ $clr }}-50 text-{{ $clr }}-600"><x-icon :name="$ic" class="h-4 w-4"/></span>
                        <span class="whitespace-nowrap text-sm font-semibold text-slate-700">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PAIN POINTS ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-20 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold sm:text-4xl">Pernah ngerasain ini?</h2>
            <p class="mt-3 text-slate-500">Kamu nggak sendirian. CareerLab AI bantu selesaikan satu per satu.</p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-4">
            @foreach ([['mail','CV dikirim tapi nggak dipanggil?','rose'],['face','Bingung jawab pertanyaan HRD?','violet'],['graph','Takut salah nego gaji?','amber'],['compass','Nggak tahu kenapa sering gagal?','blue']] as $i => [$ic,$text,$clr])
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md" style="--reveal-delay:{{ $i*80 }}ms">
                    <div class="mx-auto mb-4 grid h-12 w-12 place-items-center rounded-xl bg-{{ $clr }}-50 text-{{ $clr }}-600"><x-icon :name="$ic" class="h-6 w-6"/></div>
                    <p class="text-sm font-medium text-slate-700">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ FEATURES (listing-card style) ============ --}}
    <section id="fitur" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="reveal flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-sm font-semibold text-indigo-600">Fitur Unggulan</span>
                    <h2 class="mt-1 text-3xl font-bold sm:text-4xl">Satu platform, semua kebutuhan kariermu</h2>
                </div>
                <a href="{{ route('register') }}" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-600 sm:inline-flex">
                    Coba semua fitur <x-icon name="arrow" class="h-4 w-4"/>
                </a>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        ['doc','HRD Black Box CV Review','Lihat cara HRD baca CV kamu di 10-30 detik pertama, lengkap ATS score.','emerald','Paling populer'],
                        ['chat','Interview Drama Simulator','Latihan lawan AI HRD: friendly, strict, sampai galak mode.','violet','6 mode HRD'],
                        ['target','Job Match Reality Check','Paste lowongan, cek match score, dan haruskah kamu apply.','blue',null],
                        ['shield','Red Flag Scanner','Deteksi red flag di profilmu sebelum HRD menemukannya.','rose',null],
                        ['fire','Toxic Workplace Detector','Baca tanda lowongan toxic & pertanyaan aman buat HR.','amber',null],
                        ['graph','Salary Negotiation','Latihan nego gaji tanpa terlihat pasrah atau agresif.','indigo',null],
                    ];
                @endphp
                @foreach ($features as $i => [$ic,$title,$desc,$clr,$tag])
                    <div class="reveal group flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl" style="--reveal-delay:{{ $i*70 }}ms">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="grid h-12 w-12 place-items-center rounded-xl bg-{{ $clr }}-50 text-{{ $clr }}-600 transition group-hover:scale-110"><x-icon :name="$ic" class="h-6 w-6"/></div>
                            @if ($tag)<span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-600">{{ $tag }}</span>@endif
                        </div>
                        <h3 class="font-bold text-slate-800">{{ $title }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{{ $desc }}</p>
                        <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 transition group-hover:gap-2.5">Coba fitur <x-icon name="arrow-ur" class="h-4 w-4"/></a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-20 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="text-sm font-semibold text-indigo-600">Cara Kerja</span>
            <h2 class="mt-1 text-3xl font-bold sm:text-4xl">Siap apply hanya dalam 5 langkah</h2>
        </div>
        <div class="mt-14 grid gap-6 md:grid-cols-5">
            @foreach ([['doc','Upload CV'],['target','Pilih target posisi'],['spark','Dapat diagnosis AI'],['chat','Latihan interview'],['rocket','Apply lebih PD']] as $i => [$ic,$label])
                <div class="reveal relative text-center" style="--reveal-delay:{{ $i*90 }}ms">
                    @if (!$loop->last)<div class="absolute left-1/2 top-7 hidden h-px w-full bg-gradient-to-r from-indigo-200 to-transparent md:block"></div>@endif
                    <div class="relative mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/25">
                        <x-icon :name="$ic" class="h-6 w-6"/>
                        <span class="absolute -right-1 -top-1 grid h-6 w-6 place-items-center rounded-full bg-white text-xs font-bold text-indigo-600 shadow">{{ $i+1 }}</span>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-700">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ PREVIEW RESULT ============ --}}
    <section class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold sm:text-4xl">Diagnosis karier yang jelas & actionable</h2>
                <p class="mt-3 text-white/60">Bukan cuma skor — kamu dapat tahu persis apa yang harus diperbaiki.</p>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([['Career Readiness','72','emerald'],['CV Score','68','blue'],['ATS Score','62','violet'],['Interview Ready','75','purple']] as $i => [$lbl,$score,$clr])
                    <div class="reveal rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10 transition hover:bg-white/10" style="--reveal-delay:{{ $i*80 }}ms">
                        <div class="text-5xl font-extrabold bg-gradient-to-br from-emerald-300 to-violet-300 bg-clip-text text-transparent">{{ $score }}</div>
                        <p class="mt-2 text-sm text-white/60">{{ $lbl }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PRICING TEASER ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-20 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="text-sm font-semibold text-indigo-600">Harga</span>
            <h2 class="mt-1 text-3xl font-bold sm:text-4xl">Ramah kantong job seeker</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($plans->whereIn('slug', ['free','starter','pro','serious']) as $i => $plan)
                <div class="reveal flex flex-col rounded-2xl border bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl {{ $plan->slug === 'pro' ? 'border-indigo-400 ring-2 ring-indigo-400' : 'border-slate-200' }}" style="--reveal-delay:{{ $i*70 }}ms">
                    @if ($plan->slug === 'pro')<span class="mb-2 inline-flex w-fit items-center gap-1 rounded-full bg-indigo-600 px-3 py-0.5 text-xs font-semibold text-white"><x-icon name="star" class="h-3 w-3"/> Populer</span>@endif
                    <h3 class="font-bold text-slate-800">{{ $plan->name }}</h3>
                    <p class="mt-2 text-3xl font-extrabold">{{ $plan->priceFormatted() }}</p>
                    <ul class="mt-4 flex-1 space-y-2.5 text-sm text-slate-600">
                        @foreach (array_slice($plan->features ?? [], 0, 4) as $f)
                            <li class="flex gap-2"><x-icon name="check-c" class="h-4 w-4 shrink-0 text-emerald-500"/> {{ $f }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('pricing') }}" class="mt-6 rounded-xl px-4 py-2.5 text-center text-sm font-semibold {{ $plan->slug === 'pro' ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white' : 'bg-slate-900 text-white hover:bg-slate-700' }}">Pilih {{ $plan->name }}</a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="reveal mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold sm:text-4xl">Kata mereka yang udah nyoba</h2>
                </div>
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($testimonials as $i => $t)
                        <div class="reveal flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:{{ $i*70 }}ms">
                            <x-icon name="quote" class="h-7 w-7 text-indigo-200"/>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $t->content }}</p>
                            <div class="mt-4 flex items-center gap-3 border-t border-slate-100 pt-4">
                                <div class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">{{ strtoupper(substr($t->user_name,0,1)) }}</div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $t->user_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $t->role }}</p>
                                </div>
                                <span class="ml-auto text-amber-400">{!! str_repeat('★', $t->rating) !!}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FAQ ============ --}}
    <section class="mx-auto max-w-3xl px-4 py-20 lg:px-8">
        <div class="reveal text-center">
            <h2 class="text-3xl font-bold sm:text-4xl">Pertanyaan umum</h2>
        </div>
        <div class="reveal mt-10 space-y-3" x-data="{ open: 0 }">
            @foreach ([
                ['Apakah data CV saya aman?','Aman. Data CV hanya digunakan untuk analisis career di platform ini dan disimpan secara privat.'],
                ['Apakah AI menjamin saya diterima kerja?','Tidak. CareerLab AI membantu kamu lebih siap, tapi tidak membuat klaim pasti diterima kerja.'],
                ['Apakah ada versi gratis?','Ada. Kamu bisa coba CV Review, Job Match, dan Interview secara gratis tanpa kartu kredit.'],
                ['Pembayarannya gimana?','Untuk MVP via transfer manual — pilih paket, upload bukti, dan admin akan mengaktifkan langgananmu.'],
            ] as $i => [$q,$a])
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                        <span class="text-sm font-semibold text-slate-800">{{ $q }}</span>
                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-slate-100 transition" :class="open === {{ $i }} ? 'rotate-180 bg-indigo-100 text-indigo-600' : ''"><x-icon name="chevron-d" class="h-4 w-4"/></span>
                    </button>
                    <div x-show="open === {{ $i }}" x-cloak x-transition.duration.300ms class="px-5 pb-4 text-sm leading-relaxed text-slate-500">{{ $a }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="mx-auto max-w-7xl px-4 pb-24 lg:px-8">
        <div class="reveal relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 px-6 py-16 text-center text-white sm:px-12">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-12 left-10 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold sm:text-4xl">Siap bikin HRD nangkep value kamu?</h2>
                <p class="mx-auto mt-3 max-w-md text-white/80">Mulai gratis hari ini. Tanpa kartu kredit, langsung bisa cek CV.</p>
                <a href="{{ route('free.cv') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-slate-900 shadow-xl transition hover:scale-[1.03]">
                    <x-icon name="spark" class="h-4 w-4 text-indigo-600"/> Cek CV Gratis Sekarang
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
