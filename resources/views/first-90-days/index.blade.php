<x-dashboard-layout title="First 90 Days">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">First 90 Days Survival Plan</h2>
        <p class="text-sm text-slate-500">Baru diterima kerja? Ini rencana biar kamu bertahan & terlihat valuable di 90 hari pertama.</p>
    </div>

    @php $o = $old ?? []; @endphp
    <form method="POST" action="{{ route('first-90-days.generate') }}" x-data="{ loading: false }" @submit="loading = true"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Posisi Baru</label>
                <input type="text" name="position" required value="{{ $o['position'] ?? auth()->user()->target_position }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Industri</label>
                <input type="text" name="industry" required value="{{ $o['industry'] ?? '' }}" placeholder="cth: Teknologi"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Level Pengalaman</label>
                <input type="text" name="experience_level" value="{{ $o['experience_level'] ?? '' }}" placeholder="cth: fresh graduate"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kekhawatiran Utama</label>
                <input type="text" name="main_concern" value="{{ $o['main_concern'] ?? '' }}" placeholder="cth: takut nggak bisa ikutin ritme"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
        </div>
        <button type="submit" :disabled="loading"
                class="w-full rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
            <span x-show="!loading">Buat Rencana 90 Hari</span>
            <span x-show="loading" x-cloak>Menyusun rencana...</span>
        </button>
    </form>

    @if ($result)
        {{-- Timeline plans --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                'week_1_plan' => ['Minggu Pertama', 'from-emerald-500 to-emerald-600'],
                'day_30_plan' => ['30 Hari', 'from-blue-500 to-blue-600'],
                'day_60_plan' => ['60 Hari', 'from-purple-500 to-purple-600'],
                'day_90_plan' => ['90 Hari', 'from-amber-500 to-orange-600'],
            ] as $key => [$label, $grad])
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-3 inline-block rounded-lg bg-gradient-to-br {{ $grad }} px-3 py-1 text-xs font-bold text-white">{{ $label }}</div>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach ($result[$key] ?? [] as $item)<li class="flex gap-2"><span class="text-slate-400">·</span>{{ $item }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        {{-- How-to sections --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            @foreach ([
                'how_to_communicate' => '💬 Cara Komunikasi',
                'how_to_ask_questions' => '🙋 Cara Bertanya Tanpa Terlihat Lemah',
                'how_to_report_progress' => '📊 Cara Lapor Progress',
                'how_to_handle_toxic_senior' => '🛡️ Hadapi Senior Toxic',
            ] as $key => $title)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-3 font-semibold text-slate-800">{{ $title }}</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach ($result[$key] ?? [] as $item)<li class="flex gap-2"><span class="text-emerald-500">→</span>{{ $item }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        @if (!empty($result['success_metrics']))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                <h3 class="mb-3 font-semibold text-emerald-800">🏆 Tolok Ukur Sukses</h3>
                <ul class="space-y-2 text-sm text-emerald-900">
                    @foreach ($result['success_metrics'] as $m)<li class="flex gap-2"><span>✓</span>{{ $m }}</li>@endforeach
                </ul>
            </div>
        @endif
    @endif
</x-dashboard-layout>
