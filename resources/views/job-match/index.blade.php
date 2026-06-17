<x-dashboard-layout title="Job Match">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Job Match Reality Check</h2>
        <p class="text-sm text-slate-500">Paste deskripsi lowongan, kita bandingkan dengan CV kamu. Realistis, tanpa janji muluk.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('job-match.check') }}" x-data="{ loading: false }" @submit="loading = true"
              class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Posisi Lowongan</label>
                    <input type="text" name="job_title" required value="{{ old('job_title') }}" placeholder="cth: Backend Developer"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Perusahaan (opsional)</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="cth: Tokopedia"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <label class="mt-4 block text-sm font-medium text-slate-700">Pilih CV</label>
            <select name="cv_upload_id" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">— Tanpa CV —</option>
                @foreach ($cvs as $cv)
                    <option value="{{ $cv->id }}" @selected(old('cv_upload_id') == $cv->id)>{{ $cv->original_filename }}</option>
                @endforeach
            </select>
            @if ($cvs->isEmpty())
                <p class="mt-1 text-xs text-amber-600">Belum punya CV? <a href="{{ route('cv.index') }}" class="underline">Upload dulu</a> biar hasilnya lebih akurat.</p>
            @endif

            <label class="mt-4 block text-sm font-medium text-slate-700">Deskripsi Lowongan</label>
            <textarea name="job_description" rows="8" required placeholder="Paste seluruh isi lowongan di sini..."
                      class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('job_description') }}</textarea>

            <button type="submit" :disabled="loading"
                    class="mt-5 w-full rounded-xl bg-gradient-to-r from-blue-500 to-emerald-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                <span x-show="!loading">Cek Match Score</span>
                <span x-show="loading" x-cloak>Menganalisis kecocokan...</span>
            </button>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($checks as $c)
                <a href="{{ route('job-match.show', $c) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-slate-700">{{ $c->job_title }}</span>
                        <span class="text-sm font-bold text-blue-600">{{ $c->match_score }}%</span>
                    </div>
                    <span class="text-xs text-slate-400">{{ $c->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Belum ada cek job match.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
