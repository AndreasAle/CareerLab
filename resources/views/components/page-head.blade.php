@props([
    'icon' => 'spark',
    'title' => '',
    'subtitle' => null,
    'gradient' => 'from-indigo-500 via-violet-500 to-purple-600',
])

<div class="cl-rise mb-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br {{ $gradient }} text-white shadow-lg shadow-indigo-500/20">
            <x-icon :name="$icon" class="h-6 w-6"/>
        </div>
        <div>
            <h2 class="text-lg font-bold tracking-tight text-slate-800">{{ $title }}</h2>
            @if ($subtitle)
                <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if (! $slot->isEmpty())
        <div class="flex items-center gap-2">{{ $slot }}</div>
    @endif
</div>
