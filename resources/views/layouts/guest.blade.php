<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CareerLab AI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-white font-sans text-slate-900 antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">

        {{-- ===== Left brand panel (desktop) ===== --}}
        <div class="relative hidden overflow-hidden bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -left-16 -top-16 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-20 right-0 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute right-24 top-1/3 h-40 w-40 rounded-full bg-emerald-300/20 blur-3xl"></div>

            <a href="{{ route('home') }}" class="relative flex items-center gap-2">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/15 ring-1 ring-white/20 backdrop-blur">
                    <x-icon name="compass" class="h-6 w-6"/>
                </div>
                <span class="text-xl font-bold tracking-tight">CareerLab<span class="text-emerald-300">AI</span></span>
            </a>

            <div class="relative">
                <h1 class="text-4xl font-extrabold leading-tight">Latihan masuk kerja<br>sebelum ketemu HRD.</h1>
                <p class="mt-4 max-w-md text-white/75">Bergabung dengan job seeker yang menyiapkan CV, interview, dan strategi lamaran mereka bareng 10 fitur AI.</p>

                <div class="mt-8 space-y-3">
                    @foreach ([['doc','CV Review ala HRD + ATS score'],['chat','Interview simulator 6 mode HRD'],['shield','Deteksi red flag & toxic job'],['spark','Career report PDF & tracker lamaran']] as [$ic,$t])
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white/15 ring-1 ring-white/20"><x-icon :name="$ic" class="h-5 w-5"/></span>
                            <span class="text-sm text-white/90">{{ $t }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative rounded-2xl bg-white/10 p-5 ring-1 ring-white/15 backdrop-blur">
                <div class="flex items-center gap-1 text-amber-300">{!! str_repeat('★', 5) !!}</div>
                <p class="mt-2 text-sm text-white/85">"Setelah perbaiki CV pakai saran CareerLab, aku dipanggil 3 interview dalam 2 minggu!"</p>
                <p class="mt-2 text-xs text-white/60">— Dina, Fresh Graduate</p>
            </div>
        </div>

        {{-- ===== Right form area ===== --}}
        <div class="relative flex flex-col px-6 py-8 sm:px-12">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 lg:hidden">
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 text-white">
                        <x-icon name="compass" class="h-5 w-5"/>
                    </div>
                    <span class="text-lg font-bold">CareerLab<span class="text-indigo-600">AI</span></span>
                </a>
                <a href="{{ route('home') }}" class="ml-auto inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
                    <x-icon name="chevron" class="h-4 w-4 rotate-180"/> Beranda
                </a>
            </div>

            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-center text-xs text-slate-400">© {{ date('Y') }} CareerLab AI · Data kamu aman & privat.</p>
        </div>
    </div>
</body>
</html>
