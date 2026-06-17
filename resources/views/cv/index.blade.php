<x-dashboard-layout title="CV Review">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">HRD Black Box CV Review</h2>
        <p class="text-sm text-slate-500">Upload CV kamu, lihat cara HRD membacanya dalam 10-30 detik pertama.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Upload card --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('cv.upload') }}" enctype="multipart/form-data"
                  x-data="{ name: '', loading: false }" @submit="loading = true"
                  class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                @csrf
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-blue-500 text-white">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <h3 class="font-semibold text-slate-800">Upload CV (PDF, maks 5MB)</h3>
                <p class="mb-4 text-xs text-slate-500">Privasi aman — CV kamu hanya dipakai untuk analisis career di platform ini.</p>

                <label class="inline-block cursor-pointer rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Pilih File PDF
                    <input type="file" name="cv_file" accept="application/pdf" class="hidden" @change="name = $event.target.files[0]?.name ?? ''">
                </label>
                <p class="mt-2 text-xs text-emerald-600" x-text="name"></p>

                <div class="my-5 flex items-center gap-3 text-xs text-slate-400">
                    <span class="h-px flex-1 bg-slate-200"></span> ATAU TEMPEL TEKS CV <span class="h-px flex-1 bg-slate-200"></span>
                </div>
                <textarea name="manual_text" rows="5" placeholder="Tempel isi CV kamu di sini kalau tidak punya file PDF..."
                          class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>

                <button type="submit" :disabled="loading"
                        class="mt-4 w-full rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                    <span x-show="!loading">Upload & Lanjut</span>
                    <span x-show="loading" x-cloak>Memproses...</span>
                </button>
            </form>
        </div>

        {{-- Tips --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-slate-800">Tips biar akurat</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                <li class="flex gap-2"><span class="text-emerald-500">✓</span> Gunakan CV versi terbaru</li>
                <li class="flex gap-2"><span class="text-emerald-500">✓</span> Pastikan teks bisa di-select (bukan hasil scan)</li>
                <li class="flex gap-2"><span class="text-emerald-500">✓</span> Siapkan target posisi yang kamu incar</li>
            </ul>
        </div>
    </div>

    {{-- History --}}
    <h3 class="mt-8 mb-3 text-sm font-semibold uppercase tracking-wide text-slate-400">Riwayat CV</h3>
    @if ($cvs->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
            Belum ada CV yang diunggah. Yuk mulai dari atas! 🚀
        </div>
    @else
        <div class="space-y-3">
            @foreach ($cvs as $cv)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-slate-800">{{ $cv->original_filename }}</p>
                        <p class="text-xs text-slate-400">{{ $cv->created_at->diffForHumans() }} · {{ $cv->reviews_count }} review</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cv.review.show', $cv) }}" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">Review</a>
                        <form method="POST" action="{{ route('cv.destroy', $cv) }}" onsubmit="return confirm('Hapus CV ini?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
