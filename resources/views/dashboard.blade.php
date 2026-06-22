<x-dashboard-layout title="Dashboard">
    @php
        $hour = now()->format('H');
        $greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
        $firstName = explode(' ', $user->name)[0];

        // area chart geometry
        $max = max(1, $series->max('value'));
        $w = 360; $h = 150; $pad = 12;
        $n = max(1, $series->count());
        $step = $n > 1 ? ($w - 2 * $pad) / ($n - 1) : 0;
        $pts = $series->values()->map(fn ($p, $i) => [
            'x' => round($pad + $i * $step, 1),
            'y' => round($h - $pad - ($p['value'] / $max) * ($h - 2.6 * $pad), 1),
            'label' => $p['label'], 'value' => $p['value'],
        ]);
        $line = $pts->map(fn ($p, $i) => ($i === 0 ? 'M' : 'L') . $p['x'] . ' ' . $p['y'])->implode(' ');
        $area = $line . ' L' . $pts->last()['x'] . ' ' . ($h - $pad) . ' L' . $pts->first()['x'] . ' ' . ($h - $pad) . ' Z';

        // tiny sparkline (shared, decorative) 80x24
        $sMax = max(1, $series->max('value'));
        $spark = $series->values()->map(fn ($p, $i) => round(6 + $i * (68 / max(1,$n-1)), 1) . ',' . round(20 - ($p['value'] / $sMax) * 16, 1))->implode(' ');
    @endphp

    {{-- ===================== HERO ===================== --}}
    <div class="cl-rise relative mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-950 text-white">
        {{-- dotted pattern + blobs --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle,_rgba(255,255,255,0.10)_1px,_transparent_1px)] [background-size:18px_18px] opacity-60"></div>
        <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-24 right-1/3 h-72 w-72 rounded-full bg-violet-500/30 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>

        <div class="relative grid items-center gap-6 p-6 sm:p-8 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur">
                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-2 w-2 animate-ping rounded-full bg-emerald-400 opacity-75"></span><span class="relative h-2 w-2 rounded-full bg-emerald-400"></span></span>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $greet }}, {{ $firstName }}! 👋</h2>
                <p class="mt-2 max-w-md text-sm text-white/70">
                    @if ($readiness > 0)
                        Career Readiness kamu <span class="font-semibold text-emerald-300">{{ $readiness }}/100</span> — terus dorong biar makin siap ketemu HRD.
                    @else
                        Mulai perjalanan kariermu: upload CV untuk lihat cara HRD membaca profilmu.
                    @endif
                </p>

                {{-- mini stat pills --}}
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach ([['Readiness', $readiness, 'emerald'], ['CV Score', $cvScore, 'blue'], ['Interview', $interviewScore, 'violet']] as [$lbl, $val, $clr])
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 backdrop-blur">
                            <p class="text-xl font-bold text-{{ $clr }}-300"
                               x-data="{ n: 0 }" x-init="let t=Date.now();(function s(){let p=Math.min(1,(Date.now()-t)/1200); n=Math.round(p*{{ $val }}); if(p<1)requestAnimationFrame(s)})()" x-text="n">0</p>
                            <p class="text-[11px] text-white/50">{{ $lbl }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('cv.index') }}" class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 transition hover:scale-[1.03]">
                        <x-icon name="doc" class="h-4 w-4 text-indigo-600"/> Upload CV
                    </a>
                    <a href="{{ route('interview.index') }}" class="flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold ring-1 ring-white/20 backdrop-blur transition hover:bg-white/20">
                        <x-icon name="chat" class="h-4 w-4 text-violet-300"/> Latihan Interview
                    </a>
                    <a href="{{ route('career-report.index') }}" class="flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold ring-1 ring-white/20 backdrop-blur transition hover:bg-white/20">
                        <x-icon name="spark" class="h-4 w-4 text-amber-300"/> Generate Report
                    </a>
                </div>
            </div>

            {{-- person vector illustration --}}
            <div class="relative lg:col-span-5">
                <x-hero-illustration class="mx-auto max-w-sm" />
            </div>
        </div>
    </div>

    {{-- ===================== STAT CARDS ===================== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($statCards as $i => $card)
            @php $sparkColor = ['cv'=>'#10b981','interview'=>'#8b5cf6','jobmatch'=>'#3b82f6','apply'=>'#f59e0b'][$card['key']] ?? '#6366f1'; @endphp
            <div class="cl-rise group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" style="animation-delay: {{ $i*70 }}ms">
                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-gradient-to-br {{ $card['grad'] }} opacity-10 blur-xl transition group-hover:opacity-25"></div>
                <div class="flex items-center justify-between">
                    <div class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $card['grad'] }} text-white shadow-sm"><x-icon :name="$card['icon']" /></div>
                    @if ($card['month'] > 0)
                        <span class="flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-600"><x-icon name="up" class="h-3 w-3"/> +{{ $card['month'] }}</span>
                    @endif
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-800" x-data="{ n: 0 }" x-init="let t=Date.now();(function s(){let p=Math.min(1,(Date.now()-t)/1200); n=Math.round(p*{{ $card['total'] }}); if(p<1)requestAnimationFrame(s)})()" x-text="n">0</p>
                <div class="mt-1 flex items-end justify-between">
                    <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                    <svg viewBox="0 0 80 24" class="h-6 w-20" preserveAspectRatio="none"><polyline points="{{ $spark }}" fill="none" stroke="{{ $sparkColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- ===== Left: chart + funnel + feed ===== --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- activity chart --}}
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Aktivitas Karier</h3>
                        <p class="text-xs text-slate-400">CV · Interview · Job Match · Report (6 bulan)</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-slate-800">{{ $seriesTotal }}</p>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $activityTrend >= 0 ? 'text-emerald-600' : 'text-rose-500' }}"><x-icon :name="$activityTrend >= 0 ? 'up' : 'down'" class="h-3 w-3"/> {{ abs($activityTrend) }}% vs bln lalu</span>
                    </div>
                </div>
                <div class="relative">
                    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="w-full" preserveAspectRatio="none" style="height: 190px">
                        <defs>
                            <linearGradient id="dArea" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/><stop offset="100%" stop-color="#6366f1" stop-opacity="0"/></linearGradient>
                            <linearGradient id="dStroke" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#10b981"/><stop offset="50%" stop-color="#6366f1"/><stop offset="100%" stop-color="#a855f7"/></linearGradient>
                        </defs>
                        @foreach ([0.25,0.5,0.75] as $g)<line x1="{{ $pad }}" y1="{{ $pad + $g*($h-2.6*$pad) }}" x2="{{ $w-$pad }}" y2="{{ $pad + $g*($h-2.6*$pad) }}" stroke="#f1f5f9" stroke-width="1"/>@endforeach
                        <path d="{{ $area }}" fill="url(#dArea)" class="cl-fade"/>
                        <path d="{{ $line }}" fill="none" stroke="url(#dStroke)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" pathLength="1" class="cl-line"/>
                        @foreach ($pts as $idx => $p)<circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" fill="#fff" stroke="#6366f1" stroke-width="2.5" class="cl-pop" style="animation-delay: {{ 800 + $idx*120 }}ms"><title>{{ $p['label'] }}: {{ $p['value'] }}</title></circle>@endforeach
                    </svg>
                    <div class="mt-1 flex justify-between px-1 text-[11px] font-medium text-slate-400">@foreach ($pts as $p)<span>{{ $p['label'] }}</span>@endforeach</div>
                </div>
            </div>

            {{-- funnel --}}
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:200ms">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Funnel Lamaran</h3>
                    <a href="{{ route('applications.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Kelola →</a>
                </div>
                <div class="mb-4 flex h-3 overflow-hidden rounded-full bg-slate-100">
                    @foreach ($funnel as $f)@if ($f['count'] > 0)<div class="cl-bar h-full" style="width: {{ round($f['count']/$appTotal*100) }}%; background: {{ $f['color'] }}"></div>@endif @endforeach
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($funnel as $f)
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background: {{ $f['color'] }}"></span><span class="text-xs text-slate-500">{{ $f['label'] }}</span></div>
                            <p class="mt-1 text-xl font-bold text-slate-800">{{ $f['count'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- feed --}}
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:260ms">
                <h3 class="mb-4 font-semibold text-slate-800">Aktivitas Terbaru</h3>
                @php $fc = ['emerald'=>'bg-emerald-100 text-emerald-600','purple'=>'bg-violet-100 text-violet-600','blue'=>'bg-blue-100 text-blue-600','amber'=>'bg-amber-100 text-amber-600']; @endphp
                @forelse ($feed as $idx => $item)
                    <a href="{{ $item['url'] }}" class="group flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-slate-50">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg {{ $fc[$item['color']] }}"><x-icon :name="$item['icon']" class="h-4 w-4"/></span>
                        <div class="min-w-0 flex-1"><p class="text-sm font-medium text-slate-700">{{ $item['title'] }}</p><p class="truncate text-xs text-slate-400">{{ $item['desc'] }}</p></div>
                        <span class="text-xs text-slate-400">{{ $item['at']->diffForHumans(null, true) }}</span>
                        <x-icon name="chevron" class="h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500"/>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-slate-400">Belum ada aktivitas. Mulai dari upload CV ya! 🚀</p>
                @endforelse
            </div>
        </div>

        {{-- ===== Right column ===== --}}
        <div class="space-y-6">
            {{-- score rings --}}
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:160ms">
                <h3 class="mb-4 font-semibold text-slate-800">Skor Kesiapan</h3>
                <div class="flex items-center justify-around">
                    <x-score-ring :score="$cvScore" label="CV" :size="84" />
                    <x-score-ring :score="$atsScore" label="ATS" :size="84" />
                    <x-score-ring :score="$interviewScore" label="Interview" :size="84" />
                </div>
            </div>

            {{-- plan --}}
            <div class="cl-rise relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 p-6 text-white shadow-sm" style="--reveal-delay:220ms">
                <div class="absolute inset-0 cl-shimmer opacity-40"></div>
                <div class="absolute -right-8 -bottom-10 h-28 w-28 rounded-full bg-white/10 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div><p class="text-xs text-white/70">Paket Aktif</p><h3 class="text-lg font-bold">{{ $plan->name }}</h3></div>
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/15"><x-icon name="star" class="h-5 w-5"/></span>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach (['cv_review' => 'CV Review', 'interview' => 'Interview', 'job_match' => 'Job Match'] as $k => $lbl)
                            <div class="flex items-center justify-between text-sm"><span class="text-white/80">{{ $lbl }}</span><span class="font-bold">{{ $limits[$k] === PHP_INT_MAX ? '∞' : $limits[$k] }} <span class="text-xs font-normal text-white/60">sisa</span></span></div>
                        @endforeach
                    </div>
                    <a href="{{ route('pricing') }}" class="mt-4 block rounded-xl bg-white/15 py-2 text-center text-sm font-semibold ring-1 ring-white/25 backdrop-blur transition hover:bg-white/25">Upgrade Plan</a>
                </div>
            </div>

            {{-- challenge --}}
            @if ($challenge)
                <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:280ms">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="flex items-center gap-2 font-semibold text-slate-800"><x-icon name="bolt" class="h-4 w-4 text-amber-500"/> Challenge 7 Hari</h3>
                        <span class="text-sm font-bold text-emerald-600">{{ $challengePercent }}%</span>
                    </div>
                    <div class="mb-4 h-2 overflow-hidden rounded-full bg-slate-100"><div class="cl-bar h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ $challengePercent }}%"></div></div>
                    <div class="space-y-1.5">
                        @foreach ($challenge->tasks->take(4) as $task)
                            @php $done = $completedTaskIds->contains($task->id); @endphp
                            <a href="{{ route('challenge.index') }}" class="flex items-center gap-2 text-sm">
                                <span class="grid h-5 w-5 place-items-center rounded-full text-[10px] {{ $done ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}">{{ $done ? '✓' : $task->day_number }}</span>
                                <span class="{{ $done ? 'text-slate-400 line-through' : 'text-slate-600' }}">{{ $task->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- upcoming --}}
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:340ms">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-slate-800"><x-icon name="calendar" class="h-4 w-4 text-blue-500"/> Konsultasi</h3>
                @forelse ($upcoming as $b)
                    <div class="mb-2 rounded-xl bg-slate-50 p-3"><p class="text-sm font-medium text-slate-700">{{ $b->topic }}</p><p class="text-xs text-slate-400">{{ optional($b->scheduled_at)->isoFormat('D MMM Y · HH:mm') }}</p></div>
                @empty
                    <p class="py-3 text-center text-sm text-slate-400">Belum ada jadwal.</p>
                    <a href="{{ route('consultation.index') }}" class="block rounded-xl bg-slate-900 py-2 text-center text-sm font-semibold text-white hover:bg-slate-700">Book Konsultasi</a>
                @endforelse
            </div>
        </div>
    </div>
</x-dashboard-layout>
