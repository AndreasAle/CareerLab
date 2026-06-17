@props(['title' => 'Panel', 'accent' => 'Admin'])
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — CareerLab AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600"></div>
                <span class="font-bold">CareerLab<span class="text-emerald-600">AI</span></span>
                <span class="rounded-full bg-slate-900 px-2.5 py-0.5 text-[11px] font-semibold text-white">{{ $accent }}</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="text-sm font-medium text-red-600 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-8 lg:px-8">
        @include('partials.flash')
        <h1 class="mb-6 text-xl font-bold text-slate-800">{{ $title }}</h1>
        {{ $slot }}
    </main>
</body>
</html>
