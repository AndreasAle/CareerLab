<x-dashboard-layout title="Interview">
    <a href="{{ route('interview.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    {{-- Header --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <h2 class="font-bold text-slate-800">{{ $session->target_position }}</h2>
            <div class="mt-1 flex items-center gap-2">
                <span class="rounded-full bg-purple-100 px-2.5 py-0.5 text-[11px] font-semibold text-purple-700">{{ $modes[$session->hrd_mode] ?? $session->hrd_mode }}</span>
                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">{{ ucfirst($session->difficulty) }}</span>
            </div>
        </div>
        @if ($session->status !== 'completed')
            <div class="flex items-center gap-2">
                <a href="{{ route('interview.video', $session) }}" class="flex items-center gap-1.5 rounded-xl border-2 border-violet-200 bg-violet-50 px-3.5 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-100">
                    <x-icon name="play" class="h-4 w-4"/> Mode Video 🎥
                </a>
                <form method="POST" action="{{ route('interview.finish', $session) }}" onsubmit="return confirm('Selesaikan interview dan lihat laporan akhir?')">
                    @csrf
                    <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-4 py-2 text-sm font-semibold text-white">Selesaikan</button>
                </form>
            </div>
        @else
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Selesai · Skor {{ $session->final_score }}</span>
        @endif
    </div>

    {{-- Chat --}}
    <div class="space-y-4">
        @foreach ($messages as $m)
            @if ($m->sender === 'ai_hrd')
                <div class="flex gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-sm font-bold text-white">HR</div>
                    <div class="max-w-2xl rounded-2xl rounded-tl-sm border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm whitespace-pre-line">{{ $m->message }}</div>
                </div>
            @else
                <div class="flex flex-col items-end gap-1">
                    <div class="flex justify-end gap-3">
                        <div class="max-w-2xl rounded-2xl rounded-tr-sm bg-gradient-to-br from-slate-800 to-slate-900 px-4 py-3 text-sm text-white shadow-sm whitespace-pre-line">{{ $m->message }}</div>
                    </div>
                    @if ($m->score !== null)
                        <div class="mr-1 max-w-2xl rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                            <span class="font-semibold">Skor jawaban: {{ $m->score }}/100</span>
                            @if ($m->feedback)<p class="mt-0.5">{{ $m->feedback }}</p>@endif
                            @if (!empty($m->meta['better_answer_example']))
                                <p class="mt-1 text-emerald-700">💡 {{ $m->meta['better_answer_example'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

    {{-- Answer box --}}
    @if ($session->status !== 'completed')
        <form method="POST" action="{{ route('interview.message', $session) }}" x-data="{ loading: false }" @submit="loading = true"
              class="sticky bottom-4 mt-6 rounded-2xl border border-slate-200 bg-white p-3 shadow-lg">
            @csrf
            <textarea name="message" rows="2" required placeholder="Ketik jawaban kamu..."
                      class="w-full resize-none border-0 text-sm focus:ring-0"></textarea>
            <div class="flex items-center justify-between border-t border-slate-100 pt-2">
                <span class="text-xs text-slate-400">Jawab dengan struktur STAR biar makin kuat.</span>
                <button type="submit" :disabled="loading"
                        class="rounded-xl bg-gradient-to-r from-purple-500 to-blue-500 px-5 py-2 text-sm font-semibold text-white disabled:opacity-60">
                    <span x-show="!loading">Kirim</span>
                    <span x-show="loading" x-cloak>HRD mikir...</span>
                </button>
            </div>
        </form>
    @endif

    {{-- Final report --}}
    @if ($session->status === 'completed' && $session->report_data)
        @php $r = $session->report_data; @endphp
        <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-lg font-bold text-slate-800">📋 Laporan Akhir Interview</h3>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ([
                    'final_score' => 'Final', 'confidence_score' => 'Confidence', 'clarity_score' => 'Clarity',
                    'relevance_score' => 'Relevance', 'professionalism_score' => 'Profesional',
                ] as $k => $lbl)
                    <div class="flex flex-col items-center">
                        <x-score-ring :score="$r[$k] ?? 0" :label="$lbl" :size="92" />
                    </div>
                @endforeach
            </div>

            @if (!empty($r['summary']))
                <p class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-700">{{ $r['summary'] }}</p>
            @endif

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div>
                    <h4 class="mb-2 font-semibold text-emerald-700">💪 Kekuatan</h4>
                    <ul class="space-y-1 text-sm text-slate-600">
                        @foreach ($r['strengths'] ?? [] as $s)<li class="flex gap-2"><span class="text-emerald-500">✓</span>{{ $s }}</li>@endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="mb-2 font-semibold text-amber-700">⚠️ Yang Perlu Diperbaiki</h4>
                    <ul class="space-y-1 text-sm text-slate-600">
                        @foreach ($r['weaknesses'] ?? [] as $w)<li class="flex gap-2"><span class="text-amber-500">•</span>{{ $w }}</li>@endforeach
                    </ul>
                </div>
            </div>

            @if (!empty($r['recommended_practice']))
                <div class="mt-6">
                    <h4 class="mb-2 font-semibold text-blue-700">🎯 Latihan yang Direkomendasikan</h4>
                    <ul class="space-y-1 text-sm text-slate-600">
                        @foreach ($r['recommended_practice'] as $p)<li class="flex gap-2"><span class="text-blue-500">→</span>{{ $p }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <a href="{{ route('interview.index') }}" class="mt-6 inline-block rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Latihan Lagi</a>
        </div>
    @endif
</x-dashboard-layout>
