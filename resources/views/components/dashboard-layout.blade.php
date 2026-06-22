@props(['title' => 'Dashboard'])
@php
    $nav = [
        'Utama' => [
            ['dashboard', 'Dashboard', 'home'],
        ],
        'AI Tools' => [
            ['cv.index', 'CV Review', 'doc'],
            ['interview.index', 'Interview Sim', 'chat'],
            ['job-match.index', 'Job Match', 'target'],
            ['red-flag.index', 'Red Flag', 'shield'],
            ['toxic-job.index', 'Toxic Detector', 'fire'],
            ['salary.index', 'Salary Nego', 'graph'],
            ['rejection.index', 'Rejection', 'compass'],
            ['social-audit.index', 'Social Audit', 'users'],
            ['first-90-days.index', 'First 90 Days', 'rocket'],
        ],
        'Karier' => [
            ['career-report.index', 'Career Report', 'spark'],
            ['applications.index', 'Lamaran', 'briefcase'],
            ['templates.index', 'Template', 'grid'],
            ['challenge.index', 'Challenge', 'bolt'],
            ['consultation.index', 'Konsultasi', 'calendar'],
        ],
        'Akun' => [
            ['orders.index', 'Pesanan', 'card'],
            ['pricing', 'Upgrade', 'star'],
        ],
    ];
    $u = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — CareerLab AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased">
<div x-data="{ open: false }" class="min-h-full">
    {{-- ===== Sidebar ===== --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex h-16 shrink-0 items-center gap-2 px-5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 text-white">
                    <x-icon name="compass" class="h-5 w-5"/>
                </div>
                <span class="text-lg font-bold tracking-tight">CareerLab<span class="text-indigo-600">AI</span></span>
            </a>
        </div>

        <nav class="flex-1 space-y-5 overflow-y-auto px-3 pb-4">
            @foreach ($nav as $section => $items)
                <div>
                    <p class="px-3 pb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $section }}</p>
                    <div class="space-y-0.5">
                        @foreach ($items as $i => [$route, $label, $icon])
                            @php $active = request()->routeIs($route); @endphp
                            <a href="{{ route($route) }}"
                               class="cl-rise group relative flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition
                                      {{ $active ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-slate-100' }}"
                               style="animation-delay: {{ $i * 25 }}ms">
                                <x-icon :name="$icon" class="h-[18px] w-[18px] shrink-0 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"/>
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="shrink-0 border-t border-slate-100 p-3">
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-2.5">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-slate-700">{{ $u->name }}</p>
                    <p class="truncate text-[11px] text-slate-400">{{ $u->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-white hover:text-red-500" title="Keluar">
                        <x-icon name="logout" class="h-[18px] w-[18px]"/>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- mobile overlay --}}
    <div x-show="open" @click="open = false" x-cloak class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

    {{-- ===== Main ===== --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white/80 px-4 backdrop-blur-xl lg:px-8">
            <div class="flex items-center gap-3">
                <button @click="open = !open" class="lg:hidden grid h-9 w-9 place-items-center rounded-lg text-slate-600 hover:bg-slate-100">
                    <x-icon name="menu" class="h-5 w-5"/>
                </button>
                <h1 class="text-base font-bold text-slate-800">{{ $title }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('pricing') }}" class="hidden items-center gap-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:scale-[1.03] sm:flex">
                    <x-icon name="star" class="h-3.5 w-3.5"/> Upgrade
                </a>
                <a href="{{ route('home') }}" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-slate-100" title="Beranda">
                    <x-icon name="home" class="h-[18px] w-[18px]"/>
                </a>
                <div x-data="{ menu: false }" class="relative">
                    <button @click="menu = !menu" class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </button>
                    <div x-show="menu" @click.outside="menu = false" x-cloak x-transition class="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50"><x-icon name="settings" class="h-4 w-4"/> Profil</a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50"><x-icon name="card" class="h-4 w-4"/> Pesanan</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">@csrf
                            <button class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50"><x-icon name="logout" class="h-4 w-4"/> Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative min-h-[calc(100vh-4rem)] overflow-hidden p-4 lg:p-8">
            {{-- decorative vectors --}}
            <div class="pointer-events-none absolute -right-20 -top-24 -z-10 h-72 w-72 rounded-full bg-indigo-200/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-20 top-1/2 -z-10 h-72 w-72 rounded-full bg-violet-200/20 blur-3xl"></div>

            <div class="cl-fade">
                @include('partials.flash')
                {{ $slot }}
            </div>

            <footer class="mt-12 flex flex-col items-center justify-between gap-2 border-t border-slate-200 pt-5 text-xs text-slate-400 sm:flex-row">
                <p>© {{ date('Y') }} CareerLab AI. Semua hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('blog.index') }}" class="hover:text-indigo-600">Blog</a>
                    <a href="{{ route('pricing') }}" class="hover:text-indigo-600">Harga</a>
                    <span class="flex items-center gap-1"><x-icon name="shield" class="h-3.5 w-3.5 text-emerald-500"/> Data privat</span>
                </div>
            </footer>
        </main>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
</body>
</html>
