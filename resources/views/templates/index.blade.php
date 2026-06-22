<x-dashboard-layout title="Template Library">
    <x-page-head icon="grid" gradient="from-teal-500 to-cyan-600"
                 title="Template Library"
                 subtitle="Template siap pakai buat CV, email lamaran, follow up HR, dan lainnya." />

    @foreach ($grouped as $type => $templates)
        <div class="mb-8">
            <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-400">{{ $typeLabels[$type] ?? $type }}</h3>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($templates as $i => $t)
                    @php $locked = $t->is_premium && ! $canPremium; @endphp
                    <div x-data="{ open: false }" class="cl-rise rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" style="--reveal-delay:{{ $i*40 }}ms">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $t->title }}</p>
                                <p class="text-xs text-slate-500">{{ $t->description }}</p>
                            </div>
                            @if ($t->is_premium)
                                <span class="flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700"><x-icon name="star" class="h-3 w-3"/> Premium</span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">Free</span>
                            @endif
                        </div>

                        @if ($locked)
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-center">
                                <div class="mx-auto mb-1 grid h-9 w-9 place-items-center rounded-lg bg-amber-50 text-amber-600"><x-icon name="lock" class="h-5 w-5"/></div>
                                <p class="text-sm text-slate-500">Template premium</p>
                                <a href="{{ route('pricing') }}" class="mt-2 inline-block rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Upgrade untuk buka</a>
                            </div>
                        @else
                            <button @click="open = !open" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:underline" x-text="open ? 'Sembunyikan' : 'Lihat template'"></button>
                            <div x-show="open" x-cloak class="mt-3" x-data="{ copied: false }">
                                <pre class="whitespace-pre-wrap rounded-xl bg-slate-50 p-4 text-sm text-slate-700" x-ref="content">{{ $t->content }}</pre>
                                <button @click="navigator.clipboard.writeText($refs.content.innerText); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="mt-2 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                    <span x-show="!copied">Salin</span><span x-show="copied" x-cloak>Tersalin ✓</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @if ($grouped->isEmpty())
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada template.</div>
    @endif
</x-dashboard-layout>
