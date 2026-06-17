<x-dashboard-layout title="Hasil Negosiasi">
    <a href="{{ route('salary.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    @php $r = $sim->report_data ?? []; @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
            <x-score-ring :score="$sim->score" label="Skor Negosiasi" />
            <span class="mt-3 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $scenarios[$sim->scenario] ?? $sim->scenario }}</span>
        </div>
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-emerald-600 to-blue-600 p-6 text-white shadow-sm">
            <h2 class="font-bold">{{ $sim->target_position }}</h2>
            <p class="mt-2 text-sm text-white/90">{{ $sim->ai_feedback }}</p>
        </div>
    </div>

    @if (!empty($r['issue']))
        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-amber-800">⚠️ Yang Perlu Diperhatikan</h3>
            <ul class="space-y-1 text-sm text-amber-900">
                @foreach ($r['issue'] as $i)<li class="flex gap-2"><span>•</span>{{ $i }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if ($sim->suggested_answer)
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-emerald-800">✨ Versi Jawaban Lebih Baik</h3>
            <p class="text-sm text-emerald-900">{{ $sim->suggested_answer }}</p>
        </div>
    @endif

    @if (!empty($r['negotiation_strategy']))
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-blue-700">🎯 Strategi Negosiasi</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($r['negotiation_strategy'] as $s)<li class="flex gap-2"><span class="text-blue-500">→</span>{{ $s }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if (!empty($r['hr_reply']))
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-slate-800">💬 Balasan HR (simulasi)</h3>
            <p class="text-sm text-slate-600">{{ $r['hr_reply'] }}</p>
        </div>
    @endif

    <a href="{{ route('salary.index') }}" class="mt-6 inline-block rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Latihan Lagi</a>
</x-dashboard-layout>
