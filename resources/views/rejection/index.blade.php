<x-dashboard-layout title="Rejection Autopsy">
    <x-page-head icon="compass" gradient="from-purple-500 to-fuchsia-600"
                 title="Rejection Autopsy"
                 subtitle="Bedah kenapa kamu sering gagal — tanpa nyalahin kamu, fokus ke perbaikan." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('rejection.analyze') }}" x-data="{ loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis Kegagalan</label>
                    <select name="rejection_type" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500">
                        @foreach ($types as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Posisi yang Dilamar</label>
                    <input type="text" name="target_position" value="{{ old('target_position') }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ceritakan Prosesnya</label>
                <textarea name="story" rows="6" required placeholder="Ceritakan tahap terakhir yang kamu lewati dan apa yang terjadi..."
                          class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500">{{ old('story') }}</textarea>
            </div>
            <button type="submit" :disabled="loading"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-purple-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="compass" class="h-4 w-4"/> Bedah Kegagalan</span>
                <span x-show="loading" x-cloak>Menganalisis...</span>
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($autopsies as $a)
                <a href="{{ route('rejection.show', $a) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <span class="text-sm font-medium text-slate-700">{{ $types[$a->rejection_type] ?? $a->rejection_type }}</span>
                    <span class="block text-xs text-slate-400">{{ $a->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Belum ada analisis.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
