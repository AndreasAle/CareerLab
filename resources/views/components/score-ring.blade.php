@props(['score' => 0, 'label' => 'Score', 'size' => 120])

@php
    $score = max(0, min(100, (int) $score));
    $radius = ($size / 2) - 8;
    $circ = 2 * M_PI * $radius;
    $offset = $circ * (1 - $score / 100);
    $color = $score >= 75 ? '#10b981' : ($score >= 50 ? '#3b82f6' : '#f59e0b');
    $uid = 'ring' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center']) }}>
    <div class="relative" style="width: {{ $size }}px; height: {{ $size }}px;"
         x-data="{ n: 0 }"
         x-init="let t=Date.now(); (function s(){let p=Math.min(1,(Date.now()-t)/1500); n=Math.round(p*{{ $score }}); if(p<1)requestAnimationFrame(s)})()">
        <svg class="-rotate-90" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
            <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $radius }}" fill="none" stroke="#e2e8f0" stroke-width="8"/>
            <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $radius }}" fill="none"
                    stroke="{{ $color }}" stroke-width="8" stroke-linecap="round"
                    stroke-dasharray="{{ $circ }}" class="cl-ring"
                    style="--cl-circ: {{ $circ }}; --cl-offset: {{ $offset }};"/>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold" style="color: {{ $color }}" x-text="n">{{ $score }}</span>
            <span class="text-[10px] uppercase tracking-wider text-slate-400">/ 100</span>
        </div>
    </div>
    <span class="mt-2 text-xs font-medium text-slate-500">{{ $label }}</span>
</div>
