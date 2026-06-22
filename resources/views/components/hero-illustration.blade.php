{{-- Flat career character: a person at a laptop with a rising chart + floating UI badges. On-brand, no external assets. --}}
<svg {{ $attributes->merge(['class' => 'h-auto w-full']) }} viewBox="0 0 380 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="hiScreen" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#a5b4fc"/><stop offset="100%" stop-color="#c4b5fd"/>
        </linearGradient>
        <linearGradient id="hiShirt" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#6366f1"/><stop offset="100%" stop-color="#4f46e5"/>
        </linearGradient>
        <linearGradient id="hiChart" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#a7f3d0"/>
        </linearGradient>
    </defs>

    {{-- soft backdrop blob --}}
    <path class="cl-blob" d="M300 150c0 70-55 120-130 120S40 215 40 150 95 35 170 35s130 45 130 115z" fill="#ffffff" opacity="0.10"/>

    {{-- decorative dots --}}
    <g fill="#ffffff" opacity="0.35">
        <circle cx="52" cy="60" r="3"/><circle cx="70" cy="48" r="2"/><circle cx="40" cy="80" r="2"/>
        <circle cx="330" cy="210" r="3"/><circle cx="345" cy="225" r="2"/><circle cx="318" cy="232" r="2"/>
    </g>

    {{-- floating "score" badge (top-left) --}}
    <g class="cl-float" style="transform-box: fill-box; transform-origin: center;">
        <rect x="24" y="92" width="92" height="44" rx="12" fill="#ffffff"/>
        <circle cx="46" cy="114" r="13" fill="none" stroke="#e2e8f0" stroke-width="4"/>
        <circle cx="46" cy="114" r="13" fill="none" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-dasharray="82" stroke-dashoffset="24" transform="rotate(-90 46 114)"/>
        <rect x="66" y="106" width="38" height="6" rx="3" fill="#e2e8f0"/>
        <rect x="66" y="118" width="26" height="6" rx="3" fill="#c7d2fe"/>
    </g>

    {{-- floating check badge (top-right) --}}
    <g class="cl-float" style="animation-delay:1.2s; transform-box: fill-box; transform-origin: center;">
        <rect x="278" y="64" width="78" height="40" rx="12" fill="#ffffff"/>
        <circle cx="298" cy="84" r="11" fill="#dcfce7"/>
        <path d="M293 84l4 4 7-8" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        <rect x="316" y="78" width="32" height="6" rx="3" fill="#e2e8f0"/>
        <rect x="316" y="89" width="22" height="6" rx="3" fill="#bbf7d0"/>
    </g>

    {{-- desk --}}
    <rect x="70" y="246" width="240" height="10" rx="5" fill="#312e81"/>
    <rect x="96" y="256" width="10" height="22" rx="4" fill="#312e81"/>
    <rect x="274" y="256" width="10" height="22" rx="4" fill="#312e81"/>

    {{-- laptop --}}
    <rect x="150" y="196" width="120" height="78" rx="8" fill="#1e1b4b"/>
    <rect x="160" y="206" width="100" height="58" rx="4" fill="url(#hiScreen)"/>
    {{-- rising chart on screen --}}
    <path d="M168 252 L186 238 L204 244 L222 224 L240 230 L252 214" stroke="url(#hiChart)" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="252" cy="214" r="3.5" fill="#34d399"/>
    <rect x="130" y="274" width="160" height="8" rx="4" fill="#1e1b4b"/>

    {{-- character --}}
    {{-- chair back --}}
    <rect x="92" y="170" width="60" height="70" rx="16" fill="#4338ca" opacity="0.5"/>
    {{-- torso / shirt --}}
    <path d="M96 240c0-30 14-52 30-52s30 22 30 52z" fill="url(#hiShirt)"/>
    {{-- collar --}}
    <path d="M114 196l12 12 12-12" stroke="#c7d2fe" stroke-width="3" fill="none" stroke-linecap="round"/>
    {{-- arm to laptop --}}
    <path d="M150 214c14 2 26 10 32 22" stroke="#4f46e5" stroke-width="12" fill="none" stroke-linecap="round"/>
    {{-- neck --}}
    <rect x="120" y="170" width="12" height="16" rx="6" fill="#f4c4a0"/>
    {{-- head --}}
    <circle cx="126" cy="158" r="18" fill="#f7cda6"/>
    {{-- hair --}}
    <path d="M108 156c0-12 8-22 18-22s18 10 18 22c-4-6-10-9-18-9s-14 3-18 9z" fill="#1f2937"/>
    {{-- face --}}
    <circle cx="121" cy="158" r="1.6" fill="#1f2937"/>
    <circle cx="131" cy="158" r="1.6" fill="#1f2937"/>
    <path d="M120 164c3 3 9 3 12 0" stroke="#b45309" stroke-width="1.6" fill="none" stroke-linecap="round"/>

    {{-- sparkles --}}
    <g fill="#fde68a">
        <path d="M300 130l2 6 6 2-6 2-2 6-2-6-6-2 6-2z"/>
        <path d="M60 200l1.5 4 4 1.5-4 1.5-1.5 4-1.5-4-4-1.5 4-1.5z"/>
    </g>
</svg>
