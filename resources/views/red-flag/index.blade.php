<x-dashboard-layout title="Red Flag Scanner">
    <x-page-head icon="shield" gradient="from-rose-500 to-pink-600"
                 title="Red Flag Scanner Kandidat"
                 subtitle="Deteksi potensi red flag di profilmu sebelum HRD yang menemukannya. Tenang, semua bisa diperbaiki." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('red-flag.scan') }}" x-data="{ loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Target Posisi</label>
                    <input type="text" name="target_position" required value="{{ old('target_position', auth()->user()->target_position) }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status Saat Ini</label>
                    <input type="text" name="current_status" value="{{ old('current_status') }}" placeholder="cth: fresh graduate / unemployed"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">CV (opsional)</label>
                <select name="cv_upload_id" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                    <option value="">— Tanpa CV —</option>
                    @foreach ($cvs as $cv)<option value="{{ $cv->id }}">{{ $cv->original_filename }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ringkasan Pengalaman</label>
                <textarea name="experience" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500" placeholder="cth: 1 tahun magang, lalu kerja 6 bulan...">{{ old('experience') }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Gap Kerja (kalau ada)</label>
                    <input type="text" name="work_gap" value="{{ old('work_gap') }}" placeholder="cth: 8 bulan menganggur"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Alasan Resign (kalau ada)</label>
                    <input type="text" name="resign_reason" value="{{ old('resign_reason') }}" placeholder="cth: bos toxic, gaji kecil..."
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Target Karier</label>
                <input type="text" name="career_target" value="{{ old('career_target') }}" placeholder="cth: jadi senior dev dalam 3 tahun"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
            </div>

            <button type="submit" :disabled="loading"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="shield" class="h-4 w-4"/> Scan Red Flag</span>
                <span x-show="loading" x-cloak>Memindai profil kamu...</span>
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($scans as $s)
                <a href="{{ route('red-flag.show', $s) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-slate-700">{{ $s->target_position }}</span>
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
