<x-dashboard-layout title="CV Review">
    <x-page-head icon="doc" gradient="from-emerald-500 to-teal-600"
                 title="HRD Black Box CV Review"
                 subtitle="Upload CV kamu, lihat cara HRD membacanya dalam 10-30 detik pertama." />

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Upload card --}}
        <form method="POST" action="{{ route('cv.upload') }}" enctype="multipart/form-data"
              x-data="{ name: '', loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <label class="block cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-8 text-center transition hover:border-indigo-400 hover:bg-indigo-50/30">
                <input type="file" name="cv_file" accept="application/pdf" class="hidden" @change="name = $event.target.files[0]?.name ?? ''">
                <div class="mx-auto mb-3 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
                    <x-icon name="doc" class="h-8 w-8"/>
                </div>
                <p class="font-semibold text-slate-800">Klik untuk pilih CV (PDF, maks 5MB)</p>
                <p class="mt-1 text-xs text-slate-400">Privasi aman — CV kamu hanya dipakai untuk analisis di platform ini.</p>
                <p class="mt-2 text-xs font-semibold text-emerald-600" x-text="name"></p>
            </label>

            <div class="my-5 flex items-center gap-3 text-xs text-slate-400">
                <span class="h-px flex-1 bg-slate-200"></span> ATAU TEMPEL TEKS CV <span class="h-px flex-1 bg-slate-200"></span>
            </div>
            <textarea name="manual_text" rows="5" placeholder="Tempel isi CV kamu di sini kalau tidak punya file PDF..."
                      class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>

            <button type="submit" :disabled="loading"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="spark" class="h-4 w-4"/> Upload & Lanjut</span>
                <span x-show="loading" x-cloak>Memproses...</span>
            </button>
        </form>

        {{-- Tips --}}
        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 flex items-center gap-2 font-semibold text-slate-800"><x-icon name="spark" class="h-4 w-4 text-indigo-500"/> Tips biar akurat</h3>
            <ul class="space-y-3 text-sm text-slate-600">
                @foreach (['Gunakan CV versi terbaru','Pastikan teks bisa di-select (bukan hasil scan)','Siapkan target posisi yang kamu incar'] as $tip)
                    <li class="flex gap-2"><x-icon name="check-c" class="h-5 w-5 shrink-0 text-emerald-500"/> {{ $tip }}</li>
                @endforeach
            </ul>
            <div class="mt-5 rounded-xl bg-gradient-to-br from-indigo-50 to-violet-50 p-4">
                <p class="text-xs text-slate-500">Setelah review, kamu bisa lanjut latihan interview & generate Career Report PDF.</p>
            </div>
        </div>
    </div>

    {{-- History --}}
    <h3 class="mt-8 mb-3 text-sm font-bold uppercase tracking-wide text-slate-400">Riwayat CV</h3>
    @if ($cvs->isEmpty())
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400"><x-icon name="doc" class="h-7 w-7"/></div>
            <p class="text-sm text-slate-500">Belum ada CV yang diunggah. Yuk mulai dari atas! 🚀</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($cvs as $i => $cv)
                <div class="cl-rise flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md" style="--reveal-delay:{{ $i*40 }}ms">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600"><x-icon name="doc" class="h-5 w-5"/></span>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-800">{{ $cv->original_filename }}</p>
                            <p class="text-xs text-slate-400">{{ $cv->created_at->diffForHumans() }} · {{ $cv->reviews_count }} review</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cv.review.show', $cv) }}" class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">Review</a>
                        <form method="POST" action="{{ route('cv.destroy', $cv) }}" onsubmit="return confirm('Hapus CV ini?')">
                            @csrf @method('DELETE')
                            <button class="grid h-8 w-8 place-items-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100"><x-icon name="x" class="h-4 w-4"/></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
