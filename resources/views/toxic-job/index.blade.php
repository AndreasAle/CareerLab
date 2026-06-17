<x-dashboard-layout title="Toxic Job Detector">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Toxic Workplace Detector</h2>
        <p class="text-sm text-slate-500">Paste lowongan atau cerita interview, kita bantu baca tanda-tanda yang perlu kamu klarifikasi.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('toxic-job.scan') }}" x-data="{ loading: false }" @submit="loading = true"
              class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
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
                    class="mt-5 w-full rounded-xl bg-gradient-to-r from-amber-500 to-rose-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                <span x-show="!loading">Deteksi Tanda Toxic</span>
                <span x-show="loading" x-cloak>Membaca sinyal...</span>
            </button>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($scans as $s)
                <a href="{{ route('toxic-job.show', $s) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-slate-700">{{ $s->job_title ?: 'Tanpa judul' }}</span>
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold uppercase {{ ['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-red-100 text-red-700'][$s->risk_level] }}">{{ $s->risk_level }}</span>
                    </div>
                    <span class="text-xs text-slate-400">{{ $s->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Belum ada scan.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
