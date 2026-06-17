<x-dashboard-layout title="Interview Simulator">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Interview Drama Simulator</h2>
        <p class="text-sm text-slate-500">Latihan interview lawan AI HRD dengan berbagai karakter. Coba dulu sebelum interview beneran.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Start form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('interview.start') }}" x-data="{ loading: false }" @submit="loading = true"
                  class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <label class="block text-sm font-medium text-slate-700">Target Posisi</label>
                <input type="text" name="target_position" required value="{{ old('target_position', auth()->user()->target_position) }}"
                       placeholder="cth: Junior Backend Developer"
                       class="mt-1 mb-5 w-full rounded-xl border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500">

                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Mode HRD</label>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3" x-data="{ mode: '{{ old('hrd_mode', 'friendly') }}' }">
                    @foreach ($modes as $key => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="hrd_mode" value="{{ $key }}" x-model="mode" class="peer hidden">
                            <div class="rounded-xl border-2 border-slate-200 p-3 text-center text-sm font-medium text-slate-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700"
                                 :class="mode === '{{ $key }}' ? 'border-purple-500 bg-purple-50 text-purple-700' : ''">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>

                <label class="mt-5 block text-sm font-medium text-slate-700 mb-2">Tingkat Kesulitan</label>
                <div class="flex gap-3" x-data="{ diff: '{{ old('difficulty', 'normal') }}' }">
                    @foreach ($difficulties as $key => $label)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="difficulty" value="{{ $key }}" x-model="diff" class="peer hidden">
                            <div class="rounded-xl border-2 border-slate-200 py-2 text-center text-sm font-medium text-slate-600"
                                 :class="diff === '{{ $key }}' ? 'border-blue-500 bg-blue-50 text-blue-700' : ''">{{ $label }}</div>
                        </label>
                    @endforeach
                </div>

                <button type="submit" :disabled="loading"
                        class="mt-6 w-full rounded-xl bg-gradient-to-r from-purple-500 to-blue-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
                    <span x-show="!loading">Mulai Interview</span>
                    <span x-show="loading" x-cloak>HRD sedang masuk ruangan...</span>
                </button>
            </form>
        </div>

        {{-- Mode legend --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-slate-800">Karakter HRD</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                <li>😊 <strong>Friendly</strong> — santai & suportif</li>
                <li>🏢 <strong>Corporate</strong> — formal & terstruktur</li>
                <li>🚀 <strong>Startup</strong> — cepat & to the point</li>
                <li>🧐 <strong>Strict</strong> — teliti & kritis</li>
                <li>😤 <strong>Galak Mode</strong> — penuh tekanan</li>
                <li>🪤 <strong>Trap Question</strong> — pertanyaan jebakan</li>
            </ul>
        </div>
    </div>

    {{-- History --}}
    <h3 class="mt-8 mb-3 text-sm font-semibold uppercase tracking-wide text-slate-400">Riwayat Interview</h3>
    @if ($sessions->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada sesi interview.</div>
    @else
        <div class="space-y-3">
            @foreach ($sessions as $s)
                <a href="{{ route('interview.show', $s) }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md">
                    <div>
                        <p class="font-medium text-slate-800">{{ $s->target_position }}</p>
                        <p class="text-xs text-slate-400">{{ $modes[$s->hrd_mode] ?? $s->hrd_mode }} · {{ $s->created_at->diffForHumans() }}</p>
                    </div>
                    @if ($s->status === 'completed')
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Skor {{ $s->final_score }}</span>
                    @else
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Berlangsung</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
