<x-dashboard-layout title="Toxic Job Detector">
    <x-page-head icon="fire" gradient="from-amber-500 to-orange-600"
                 title="Toxic Workplace Detector"
                 subtitle="Paste lowongan atau cerita interview, kita bantu baca tanda-tanda yang perlu kamu klarifikasi." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('toxic-job.scan') }}" x-data="{ loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Posisi (opsional)</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Perusahaan (opsional)</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                </div>
            </div>

            <label class="mt-4 block text-sm font-medium text-slate-700">Lowongan / Cerita Interview</label>
            <textarea name="job_description" rows="9" required placeholder="Paste isi lowongan atau ceritakan pengalaman interview kamu..."
                      class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500">{{ old('job_description') }}</textarea>

            <button type="submit" :disabled="loading"
                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="fire" class="h-4 w-4"/> Deteksi Tanda Toxic</span>
                <span x-show="loading" x-cloak>Membaca sinyal...</span>
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($scans as $s)
                <a href="{{ route('toxic-job.show', $s) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-slate-700">{{ $s->job_title ?: 'Tanpa judul' }}</span>
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold uppercase {{ ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-rose-100 text-rose-700'][$s->risk_level] }}">{{ $s->risk_level }}</span>
                    </div>
                    <span class="text-xs text-slate-400">{{ $s->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Belum ada scan.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
