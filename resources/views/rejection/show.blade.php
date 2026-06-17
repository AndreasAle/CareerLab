<x-dashboard-layout title="Hasil Autopsy">
    <a href="{{ route('rejection.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Kembali</a>

    @php $raw = json_decode($autopsy->ai_raw_response, true) ?? []; @endphp

    <div class="rounded-2xl bg-gradient-to-br from-purple-600 to-rose-600 p-6 text-white shadow-sm">
        <p class="text-xs text-white/70">{{ $types[$autopsy->rejection_type] ?? $autopsy->rejection_type }}</p>
        <h2 class="mt-1 font-bold">Kemungkinan Penyebab Utama</h2>
        <p class="mt-2 text-sm text-white/90">{{ $raw['most_likely_issue'] ?? '—' }}</p>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-amber-700">🔍 Kemungkinan Penyebab</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($autopsy->possible_causes ?? [] as $c)<li class="flex gap-2"><span class="text-amber-500">•</span>{{ $c }}</li>@endforeach
            </ul>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-emerald-700">🔧 Rencana Perbaikan</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($autopsy->improvement_plan ?? [] as $p)<li class="flex gap-2"><span class="text-emerald-500">→</span>{{ $p }}</li>@endforeach
            </ul>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-3 font-semibold text-blue-700">📅 Action Plan 7 Hari</h3>
        <ul class="space-y-2 text-sm text-slate-600">
            @foreach ($autopsy->next_action ?? [] as $a)<li class="flex gap-2"><span class="text-blue-500">→</span>{{ $a }}</li>@endforeach
        </ul>
    </div>

    @if (!empty($raw['follow_up_template']))
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <h3 class="mb-2 font-semibold text-emerald-800">✉️ Template Follow Up HR</h3>
            <p class="whitespace-pre-line text-sm text-emerald-900">{{ $raw['follow_up_template'] }}</p>
        </div>
    @endif

    @if (!empty($raw['recommended_features']))
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($raw['recommended_features'] as $f)
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Coba: {{ $f }}</span>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
