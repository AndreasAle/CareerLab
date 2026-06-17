<x-dashboard-layout title="Hasil Red Flag Scan">
    <a href="{{ route('red-flag.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    @php $badge = ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-red-100 text-red-700'][$scan->risk_level]; @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
            <x-score-ring :score="$scan->score" label="Red Flag Score" />
            <span class="mt-3 rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $badge }}">Risiko {{ $scan->risk_level }}</span>
        </div>
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-rose-500 to-purple-600 p-6 text-white shadow-sm flex items-center">
            <p class="text-sm text-white/90">Ini bukan penilaian buruk soal kamu — ini peta apa yang mungkin dilihat HRD, lengkap dengan cara memperbaikinya secara profesional.</p>
        </div>
    </div>

    {{-- Red flags --}}
    <div class="mt-6 space-y-3">
        @foreach ($scan->candidate_red_flags ?? [] as $flag)
            @php $lvl = $flag['risk_level'] ?? 'low'; $b = ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-red-100 text-red-700'][$lvl] ?? 'bg-slate-100 text-slate-600'; @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-slate-800">🚩 {{ $flag['title'] ?? '-' }}</p>
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase {{ $b }}">{{ $lvl }}</span>
                </div>
                @if (!empty($flag['why_it_matters']))<p class="mt-1 text-sm text-slate-600">{{ $flag['why_it_matters'] }}</p>@endif
                @if (!empty($flag['safe_explanation']))<p class="mt-2 text-sm text-blue-700">🗣️ Cara jelasin aman: {{ $flag['safe_explanation'] }}</p>@endif
                @if (!empty($flag['fix_action']))<p class="mt-1 text-sm text-emerald-700">💡 {{ $flag['fix_action'] }}</p>@endif
            </div>
        @endforeach
    </div>

    {{-- Professional reframes --}}
    @if (!empty($scan->explanation))
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-emerald-800">✨ Versi Profesional</h3>
            <div class="space-y-3">
                @foreach ($scan->explanation as $r)
                    <div class="rounded-xl bg-white p-3 text-sm">
                        <p class="text-slate-400 line-through">{{ $r['original_issue'] ?? '' }}</p>
                        <p class="mt-1 font-medium text-emerald-800">→ {{ $r['better_wording'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action plan --}}
    @if (!empty($scan->safe_fix_suggestions))
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-blue-700">🎯 Action Plan</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($scan->safe_fix_suggestions as $a)<li class="flex gap-2"><span class="text-blue-500">→</span>{{ $a }}</li>@endforeach
            </ul>
        </div>
    @endif
</x-dashboard-layout>
