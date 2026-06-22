<x-dashboard-layout title="Salary Simulator">
    <x-page-head icon="graph" gradient="from-emerald-500 to-green-600"
                 title="Salary Negotiation Simulator"
                 subtitle="Latihan jawab offering gaji tanpa terlihat pasrah atau terlalu agresif." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('salary.start') }}" x-data="{ loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Posisi</label>
                    <input type="text" name="target_position" required value="{{ old('target_position', auth()->user()->target_position) }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kota</label>
                    <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Level Pengalaman</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level') }}" placeholder="cth: fresh graduate"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Skenario</label>
                    <select name="scenario" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach ($scenarios as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Gaji Harapan</label>
                    <input type="text" name="expected_salary" value="{{ old('expected_salary') }}" placeholder="cth: 7.000.000"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Gaji Ditawarkan (opsional)</label>
                    <input type="text" name="offered_salary" value="{{ old('offered_salary') }}" placeholder="cth: 5.500.000"
                           class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Jawaban Kamu Menghadapi Offering</label>
                <textarea name="user_answer" rows="4" required placeholder="Tulis bagaimana kamu akan menjawab tawaran gaji tersebut..."
                          class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('user_answer') }}</textarea>
            </div>

            <button type="submit" :disabled="loading"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="graph" class="h-4 w-4"/> Nilai Jawaban Saya</span>
                <span x-show="loading" x-cloak>HR menilai jawabanmu...</span>
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Riwayat</h3>
            @forelse ($simulations as $s)
                <a href="{{ route('salary.show', $s) }}" class="mb-2 block rounded-xl border border-slate-100 p-3 hover:bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-slate-700">{{ $s->target_position }}</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $s->score }}</span>
                    </div>
                    <span class="text-xs text-slate-400">{{ $scenarios[$s->scenario] ?? $s->scenario }} · {{ $s->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Belum ada simulasi.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
