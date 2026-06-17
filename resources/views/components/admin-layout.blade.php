@props(['title' => 'Admin', 'role' => 'admin'])
@php
    $adminNav = [
        ['admin.dashboard', 'Dashboard'],
        ['admin.users.index', 'Users'],
        ['admin.plans.index', 'Plans'],
        ['admin.orders.index', 'Orders'],
        ['admin.templates.index', 'Templates'],
        ['admin.ai-prompts.index', 'AI Prompts'],
        ['admin.ai-logs.index', 'AI Logs'],
        ['admin.blog.index', 'Blog'],
        ['admin.testimonials.index', 'Testimonials'],
        ['admin.consultations.index', 'Consultations'],
    ];
    $coachNav = [
        ['coach.dashboard', 'Dashboard'],
        ['coach.consultations.index', 'Consultations'],
    ];
    $nav = $role === 'coach' ? $coachNav : $adminNav;
    $badge = ucfirst($role);
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — CareerLab AI {{ $badge }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased">
<div x-data="{ open: false }" class="min-h-full">
    <aside class="fixed inset-y-0 left-0 z-40 w-60 transform bg-slate-900 text-slate-300 transition-transform lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex h-16 items-center gap-2 px-5 border-b border-white/10">
            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600"></div>
            <span class="font-bold text-white">CareerLab</span>
            <span class="rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-semibold text-white">{{ $badge }}</span>
        </div>
        <nav class="p-3 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 4rem)">
            @foreach ($nav as [$route, $label])
                <a href="{{ route($route) }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($route) ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </aside>
    <div x-show="open" @click="open = false" x-cloak class="fixed inset-0 z-30 bg-black/30 lg:hidden"></div>

    <div class="lg:pl-60">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
            <button @click="open = !open" class="lg:hidden rounded-lg p-2 hover:bg-slate-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-base font-semibold">{{ $title }}</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm font-medium text-red-600 hover:underline">Keluar</button></form>
            </div>
        </header>
        <main class="p-4 lg:p-8">
            @include('partials.flash')
            {{ $slot }}
        </main>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
</body>
</html>
