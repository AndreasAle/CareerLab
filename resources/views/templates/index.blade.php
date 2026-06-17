<x-dashboard-layout title="Template Library">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Template Library</h2>
        <p class="text-sm text-slate-500">Template siap pakai buat CV, email lamaran, follow up HR, dan lainnya.</p>
    </div>

    @foreach ($grouped as $type => $templates)
        <div class="mb-8">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-400">{{ $typeLabels[$type] ?? $type }}</h3>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($templates as $t)
                    @php $locked = $t->is_premium && ! $canPremium; @endphp
                    <div x-data="{ open: false }" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $t->title }}</p>
                                <p class="text-xs text-slate-500">{{ $t->description }}</p>
                            </div>
                            @if ($t->is_premium)
                                <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700">Premium</span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">Free</span>
                            @endif
                        </div>

                        @if ($locked)
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-center">
                                <p class="text-sm text-slate-500">🔒 Template premium</p>
                                <a href="{{ route('pricing') }}" class="mt-2 inline-block rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Upgrade untuk buka</a>
                            </div>
                        @else
                            <button @click="open = !open" class="mt-3 text-xs font-semibold text-emerald-600 hover:underline" x-text="open ? 'Sembunyikan' : 'Lihat template'"></button>
                            <div x-show="open" x-cloak class="mt-3" x-data="{ copied: false }">
                                <pre class="whitespace-pre-wrap rounded-xl bg-slate-50 p-4 text-sm text-slate-700" x-ref="content">{{ $t->content }}</pre>
                                <button @click="navigator.clipboard.writeText($refs.content.innerText); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="mt-2 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
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
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada template.</div>
    @endif
</x-dashboard-layout>
