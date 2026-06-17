<x-dashboard-layout title="Hasil Job Match">
    <a href="{{ route('job-match.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    @php
        $applyBadge = ['yes' => 'bg-emerald-100 text-emerald-700', 'maybe' => 'bg-amber-100 text-amber-700', 'no' => 'bg-red-100 text-red-700'][$check->should_apply];
        $applyLabel = ['yes' => 'Apply 👍', 'maybe' => 'Pertimbangkan', 'no' => 'Sebaiknya jangan'][$check->should_apply];
        $raw = json_decode($check->ai_raw_response, true) ?? [];
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
            <x-score-ring :score="$check->match_score" label="Match Score" />
            <span class="mt-3 rounded-full px-3 py-1 text-xs font-semibold {{ $applyBadge }}">{{ $applyLabel }}</span>
        </div>
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-blue-600 to-emerald-600 p-6 text-white shadow-sm">
            <h2 class="font-bold">{{ $check->job_title }}{{ $check->company_name ? ' · ' . $check->company_name : '' }}</h2>
            <p class="mt-2 text-sm text-white/90">{{ $raw['summary'] ?? '' }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-emerald-700">✓ Skill yang Cocok</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($check->matched_skills ?? [] as $s)
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">{{ $s }}</span>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-amber-700">✗ Skill yang Kurang</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($check->missing_skills ?? [] as $s)
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">{{ $s }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-purple-700">🔑 Keyword Wajib</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($check->required_keywords ?? [] as $kw)
                    <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700">{{ $kw }}</span>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-blue-700">🔧 Saran Perbaikan CV</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($check->recommended_cv_changes ?? [] as $c)
                    <li class="flex gap-2"><span class="text-blue-500">→</span>{{ $c }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    @if (!empty($raw['interview_risks']))
        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-red-700">⚠️ Risiko Kalau Tetap Apply</h3>
            <ul class="space-y-1 text-sm text-red-800">
                @foreach ($raw['interview_risks'] as $r)<li class="flex gap-2"><span>•</span>{{ $r }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if ($check->suggested_cv_summary)
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-emerald-800">✨ Summary CV yang Lebih Cocok untuk Lowongan Ini</h3>
            <p class="text-sm text-emerald-900">{{ $check->suggested_cv_summary }}</p>
        </div>
    @endif

    <a href="{{ route('cv.index') }}" class="mt-6 inline-block rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Perbaiki CV untuk lowongan ini</a>
</x-dashboard-layout>
