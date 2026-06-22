<x-dashboard-layout title="First 90 Days">
    <x-page-head icon="rocket" gradient="from-indigo-500 to-blue-600"
                 title="First 90 Days Survival Plan"
                 subtitle="Baru diterima kerja? Ini rencana biar kamu bertahan & terlihat valuable di 90 hari pertama." />

    @php $o = $old ?? []; @endphp
    <form method="POST" action="{{ route('first-90-days.generate') }}" x-data="{ loading: false }" @submit="loading = true"
          class="cl-rise space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Posisi Baru</label>
                <input type="text" name="position" required value="{{ $o['position'] ?? auth()->user()->target_position }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Industri</label>
                <input type="text" name="industry" required value="{{ $o['industry'] ?? '' }}" placeholder="cth: Teknologi"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Level Pengalaman</label>
                <input type="text" name="experience_level" value="{{ $o['experience_level'] ?? '' }}" placeholder="cth: fresh graduate"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kekhawatiran Utama</label>
                <input type="text" name="main_concern" value="{{ $o['main_concern'] ?? '' }}" placeholder="cth: takut nggak bisa ikutin ritme"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <button type="submit" :disabled="loading"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01] disabled:opacity-60">
            <span x-show="!loading" class="flex items-center gap-2"><x-icon name="rocket" class="h-4 w-4"/> Buat Rencana 90 Hari</span>
            <span x-show="loading" x-cloak>Menyusun rencana...</span>
        </button>
    </form>

    @if ($result)
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                'week_1_plan' => ['Minggu Pertama', 'from-emerald-500 to-emerald-600'],
                'day_30_plan' => ['30 Hari', 'from-blue-500 to-blue-600'],
                'day_60_plan' => ['60 Hari', 'from-violet-500 to-purple-600'],
                'day_90_plan' => ['90 Hari', 'from-amber-500 to-orange-600'],
            ] as $key => [$label, $grad])
                <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-3 inline-block rounded-lg bg-gradient-to-br {{ $grad }} px-3 py-1 text-xs font-bold text-white">{{ $label }}</div>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach ($result[$key] ?? [] as $item)<li class="flex gap-2"><span class="text-slate-400">·</span>{{ $item }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            @foreach ([
                'how_to_communicate' => '💬 Cara Komunikasi',
                'how_to_ask_questions' => '🙋 Cara Bertanya Tanpa Terlihat Lemah',
                'how_to_report_progress' => '📊 Cara Lapor Progress',
                'how_to_handle_toxic_senior' => '🛡️ Hadapi Senior Toxic',
            ] as $key => $title)
                <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-3 font-semibold text-slate-800">{{ $title }}</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach ($result[$key] ?? [] as $item)<li class="flex gap-2"><span class="text-indigo-500">→</span>{{ $item }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        @if (!empty($result['success_metrics']))
            <div class="cl-rise mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-emerald-800"><x-icon name="star" class="h-5 w-5"/> Tolok Ukur Sukses</h3>
                <ul class="space-y-2 text-sm text-emerald-900">@foreach ($result['success_metrics'] as $m)<li class="flex gap-2"><span>✓</span>{{ $m }}</li>@endforeach</ul>
            </div>
        @endif
    @endif
</x-dashboard-layout>
