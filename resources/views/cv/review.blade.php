<x-dashboard-layout title="Hasil CV Review">
    <a href="{{ route('cv.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali ke daftar CV</a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <h2 class="truncate text-lg font-bold text-slate-800">{{ $cv->original_filename }}</h2>
                <p class="text-xs text-slate-400">Diunggah {{ $cv->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Run form --}}
        <form method="POST" action="{{ route('cv.review.run', $cv) }}" x-data="{ loading: false }" @submit="loading = true" class="mt-5 border-t border-slate-100 pt-5">
            @csrf
            @if (blank($cv->extracted_text))
                <label class="block text-sm font-medium text-slate-700">Teks CV (tempel manual)</label>
                <textarea name="manual_text" rows="6" required class="mt-1 mb-4 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tempel isi CV kamu di sini..."></textarea>
            @endif
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700">Target Posisi</label>
                    <input type="text" name="target_position" required value="{{ old('target_position', $review->target_position ?? auth()->user()->target_position) }}"
                           placeholder="cth: Junior Backend Developer"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <button type="submit" :disabled="loading"
                        class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-6 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
                    <span x-show="!loading">{{ $review ? 'Review Ulang' : 'Mulai Review' }}</span>
                    <span x-show="loading" x-cloak>HRD lagi baca CV kamu...</span>
                </button>
            </div>
        </form>
    </div>

    @if ($review)
        {{-- Scores --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
                <x-score-ring :score="$review->score" label="Overall Score" />
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
                <x-score-ring :score="$review->ats_score" label="ATS Score" :size="120" />
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white shadow-sm">
                <h3 class="text-sm font-semibold text-white/70">Yang HRD Lihat Pertama</h3>
                <p class="mt-2 text-sm leading-relaxed">{{ $review->hrd_first_impression }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            {{-- Strengths --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-emerald-700">💪 Kekuatan CV</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($review->strengths ?? [] as $s)
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> {{ $s }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Weaknesses --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-amber-700">⚠️ Kenapa CV Kamu Bisa Dilewati</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($review->weaknesses ?? [] as $w)
                        <li class="flex gap-2"><span class="text-amber-500">•</span> {{ $w }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Red flags --}}
        @if (!empty($review->red_flags))
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-semibold text-red-700">🚩 Red Flag</h3>
                <div class="space-y-3">
                    @foreach ($review->red_flags as $flag)
                        @php
                            $lvl = $flag['risk_level'] ?? 'low';
                            $badge = ['low' => 'bg-emerald-100 text-emerald-700', 'medium' => 'bg-amber-100 text-amber-700', 'high' => 'bg-red-100 text-red-700'][$lvl] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-slate-800">{{ $flag['title'] ?? '-' }}</p>
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase {{ $badge }}">{{ $lvl }}</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600">{{ $flag['explanation'] ?? '' }}</p>
                            @if (!empty($flag['fix']))
                                <p class="mt-2 text-sm text-emerald-700">💡 {{ $flag['fix'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Improvements + missing keywords --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 font-semibold text-blue-700">🔧 Yang Harus Kamu Perbaiki</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($review->improvement_suggestions ?? [] as $i)
                        <li class="flex gap-2"><span class="text-blue-500">→</span> {{ $i }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 font-semibold text-purple-700">🔑 Missing Keywords</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($review->missing_keywords ?? [] as $kw)
                        <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700">{{ $kw }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Rewritten summary --}}
        @if ($review->rewritten_summary)
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                <h3 class="mb-2 font-semibold text-emerald-800">✨ Contoh Summary CV yang Lebih Baik</h3>
                <p class="text-sm leading-relaxed text-emerald-900">{{ $review->rewritten_summary }}</p>
            </div>
        @endif

        {{-- CTAs --}}
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('pricing') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white">Generate Career Diagnosis Report</a>
            <a href="{{ route('cv.index') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Latihan Interview dari CV ini</a>
        </div>
    @endif
</x-dashboard-layout>
