@php
    $flashStyles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'error'   => 'border-red-200 bg-red-50 text-red-800',
        'upgrade' => 'border-purple-200 bg-purple-50 text-purple-800',
    ];
@endphp

@foreach ($flashStyles as $key => $classes)
    @if (session($key))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
             class="mb-4 flex items-start gap-3 rounded-xl border px-4 py-3 text-sm {{ $classes }}">
            <svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="flex-1">{{ session($key) }}</div>
            <button @click="show = false" class="opacity-50 hover:opacity-100">✕</button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
