<x-dashboard-layout title="Hasil Toxic Detector">
    <a href="{{ route('toxic-job.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    @php
        $raw = json_decode($scan->ai_raw_response, true) ?? [];
        $badge = ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-red-100 text-red-700'][$scan->risk_level];
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
            <x-score-ring :score="$scan->toxicity_score" label="Toxicity Score" />
            <span class="mt-3 rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $badge }}">Risiko {{ $scan->risk_level }}</span>
        </div>
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-amber-500 to-rose-600 p-6 text-white shadow-sm flex items-center">
            <p class="text-sm text-white/90">{{ $raw['summary'] ?? 'Lowongan ini bukan pasti toxic, tapi ada beberapa hal yang perlu kamu klarifikasi.' }}</p>
        </div>
    </div>

    {{-- Warning signs --}}
    <div class="mt-6 space-y-3">
        @foreach ($scan->warning_signs ?? [] as $sign)
            @php $sev = $sign['severity'] ?? 'low'; $b = ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-red-100 text-red-700'][$sev] ?? 'bg-slate-100 text-slate-600'; @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-slate-800">⚠️ {{ $sign['sign'] ?? '-' }}</p>
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase {{ $b }}">{{ $sev }}</span>
                </div>
                @if (!empty($sign['why_it_matters']))<p class="mt-1 text-sm text-slate-600">{{ $sign['why_it_matters'] }}</p>@endif
            </div>
        @endforeach
    </div>

    {{-- Questions to ask --}}
    @if (!empty($scan->questions_to_ask_hr))
        <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-blue-800">🗣️ Pertanyaan Aman buat Ditanyakan ke HR</h3>
            <ul class="space-y-2 text-sm text-blue-900">
                @foreach ($scan->questions_to_ask_hr as $q)<li class="flex gap-2"><span>•</span>{{ $q }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Conclusion --}}
    @if (!empty($raw['safe_conclusion']) || !empty($raw['recommendation']))
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-slate-800">Kesimpulan</h3>
            @if (!empty($raw['safe_conclusion']))<p class="text-sm text-slate-600">{{ $raw['safe_conclusion'] }}</p>@endif
            @if (!empty($raw['recommendation']))<p class="mt-2 text-sm font-medium text-amber-700">Rekomendasi: {{ $raw['recommendation'] }}</p>@endif
        </div>
    @endif
</x-dashboard-layout>
