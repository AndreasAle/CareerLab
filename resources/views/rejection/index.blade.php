<x-dashboard-layout title="Rejection Autopsy">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Rejection Autopsy</h2>
        <p class="text-sm text-slate-500">Bedah kenapa kamu sering gagal — tanpa nyalahin kamu, fokus ke perbaikan.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('rejection.analyze') }}" x-data="{ loading: false }" @submit="loading = true"
              class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
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
                    class="w-full rounded-xl bg-gradient-to-r from-purple-500 to-rose-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                <span x-show="!loading">Bedah Kegagalan</span>
                <span x-show="loading" x-cloak>Menganalisis...</span>
            </button>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
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
