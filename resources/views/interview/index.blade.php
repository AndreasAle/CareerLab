<x-dashboard-layout title="Interview Simulator">
    <x-page-head icon="chat" gradient="from-violet-500 to-purple-600"
                 title="Interview Drama Simulator"
                 subtitle="Latihan interview lawan AI HRD dengan berbagai karakter. Coba dulu sebelum interview beneran." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('interview.start') }}" x-data="{ loading: false }" @submit="loading = true"
              class="cl-rise lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <label class="block text-sm font-medium text-slate-700">Target Posisi</label>
            <input type="text" name="target_position" required value="{{ old('target_position', auth()->user()->target_position) }}"
                   placeholder="cth: Junior Backend Developer"
                   class="mt-1 mb-5 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Mode HRD</label>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3" x-data="{ mode: '{{ old('hrd_mode', 'friendly') }}' }">
                @foreach ($modes as $key => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="hrd_mode" value="{{ $key }}" x-model="mode" class="peer hidden">
                        <div class="rounded-xl border-2 border-slate-200 p-3 text-center text-sm font-medium text-slate-600"
                             :class="mode === '{{ $key }}' ? 'border-violet-500 bg-violet-50 text-violet-700' : ''">{{ $label }}</div>
                    </label>
                @endforeach
            </div>

            <label class="mt-5 block text-sm font-medium text-slate-700 mb-2">Tingkat Kesulitan</label>
            <div class="flex gap-3" x-data="{ diff: '{{ old('difficulty', 'normal') }}' }">
                @foreach ($difficulties as $key => $label)
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="difficulty" value="{{ $key }}" x-model="diff" class="peer hidden">
                        <div class="rounded-xl border-2 border-slate-200 py-2 text-center text-sm font-medium text-slate-600"
                             :class="diff === '{{ $key }}' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : ''">{{ $label }}</div>
                    </label>
                @endforeach
            </div>

            <button type="submit" :disabled="loading"
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="play" class="h-4 w-4"/> Mulai Interview</span>
                <span x-show="loading" x-cloak>HRD sedang masuk ruangan...</span>
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Karakter HRD</h3>
            <ul class="space-y-2.5 text-sm text-slate-600">
                <li>😊 <strong>Friendly</strong> — santai & suportif</li>
                <li>🏢 <strong>Corporate</strong> — formal & terstruktur</li>
                <li>🚀 <strong>Startup</strong> — cepat & to the point</li>
                <li>🧐 <strong>Strict</strong> — teliti & kritis</li>
                <li>😤 <strong>Galak Mode</strong> — penuh tekanan</li>
                <li>🪤 <strong>Trap Question</strong> — pertanyaan jebakan</li>
            </ul>
        </div>
    </div>

    <h3 class="mt-8 mb-3 text-sm font-bold uppercase tracking-wide text-slate-400">Riwayat Interview</h3>
    @if ($sessions->isEmpty())
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400"><x-icon name="chat" class="h-7 w-7"/></div>
            <p class="text-sm text-slate-500">Belum ada sesi interview.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($sessions as $i => $s)
                <a href="{{ route('interview.show', $s) }}" class="cl-rise flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md" style="--reveal-delay:{{ $i*40 }}ms">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-violet-50 text-violet-600"><x-icon name="chat" class="h-5 w-5"/></span>
                        <div>
                            <p class="font-medium text-slate-800">{{ $s->target_position }}</p>
                            <p class="text-xs text-slate-400">{{ $modes[$s->hrd_mode] ?? $s->hrd_mode }} · {{ $s->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if ($s->status === 'completed')
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Skor {{ $s->final_score }}</span>
                    @else
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Berlangsung</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
