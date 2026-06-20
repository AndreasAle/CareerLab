@props(['title' => 'CareerLab AI'])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Latihan Masuk Kerja Sebelum Ketemu HRD</title>
    <meta name="description" content="CareerLab AI: upload CV, lihat cara HRD membaca profilmu, latihan interview, deteksi red flag, dan siapkan lamaran kerja dengan 10 fitur AI.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
    <header x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 10"
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="scrolled ? 'border-b border-slate-100 bg-white/85 backdrop-blur-xl shadow-sm' : ''">
        <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 text-white">
                    <x-icon name="compass" class="h-5 w-5"/>
                </div>
                <span class="text-lg font-bold tracking-tight">CareerLab<span class="text-indigo-600">AI</span></span>
            </a>

            <div class="hidden items-center gap-1 md:flex">
                @php $links = [['home','Beranda',null],['home','Fitur','#fitur'],['pricing','Harga',null],['blog.index','Blog',null]]; @endphp
                <a href="{{ route('home') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Beranda</a>
                <a href="{{ route('home') }}#fitur" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Fitur</a>
                <a href="{{ route('pricing') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Harga</a>
                <a href="{{ route('blog.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Blog</a>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.03]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Masuk</a>
                    <a href="{{ route('free.cv') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.03]">Cek CV Gratis</a>
                @endauth
            </div>

            <button @click="open = !open" class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 md:hidden">
                <x-icon x-show="!open" name="menu" class="h-6 w-6"/>
                <x-icon x-show="open" x-cloak name="x" class="h-6 w-6"/>
            </button>
        </nav>

        <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-3 md:hidden">
            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-slate-50">Beranda</a>
            <a href="{{ route('home') }}#fitur" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-slate-50">Fitur</a>
            <a href="{{ route('pricing') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-slate-50">Harga</a>
            <a href="{{ route('blog.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-slate-50">Blog</a>
            <div class="mt-2 flex gap-2 border-t border-slate-100 pt-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="pt-16">{{ $slot }}</main>

    <footer class="border-t border-slate-100 bg-slate-50">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-12 lg:px-8">
            <div class="md:col-span-5">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 text-white">
                        <x-icon name="compass" class="h-5 w-5"/>
                    </div>
                    <span class="text-lg font-bold">CareerLab<span class="text-indigo-600">AI</span></span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-500">Career simulator untuk Gen Z & fresh graduate. Latihan masuk kerja sebelum ketemu HRD beneran — didukung 10 fitur AI.</p>
                <div class="mt-5 flex items-center gap-2 text-xs text-slate-400">
                    <x-icon name="shield" class="h-4 w-4 text-emerald-500"/> Data CV hanya dipakai untuk analisis career di platform ini.
                </div>
            </div>
            <div class="md:col-span-2">
                <h4 class="mb-3 text-sm font-semibold text-slate-900">Produk</h4>
                <ul class="space-y-2.5 text-sm text-slate-500">
                    <li><a href="{{ route('home') }}#fitur" class="transition hover:text-indigo-600">Fitur</a></li>
                    <li><a href="{{ route('pricing') }}" class="transition hover:text-indigo-600">Harga</a></li>
                    <li><a href="{{ route('blog.index') }}" class="transition hover:text-indigo-600">Blog</a></li>
                </ul>
            </div>
            <div class="md:col-span-2">
                <h4 class="mb-3 text-sm font-semibold text-slate-900">Akun</h4>
                <ul class="space-y-2.5 text-sm text-slate-500">
                    <li><a href="{{ route('login') }}" class="transition hover:text-indigo-600">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="transition hover:text-indigo-600">Daftar Gratis</a></li>
                </ul>
            </div>
            <div class="md:col-span-3">
                <h4 class="mb-3 text-sm font-semibold text-slate-900">Mulai sekarang</h4>
                <p class="mb-3 text-sm text-slate-500">Gratis, tanpa kartu kredit.</p>
                <a href="{{ route('free.cv') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Cek CV Gratis <x-icon name="arrow" class="h-4 w-4"/>
                </a>
            </div>
        </div>
        <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">© {{ date('Y') }} CareerLab AI. Semua hak cipta dilindungi.</div>
    </footer>

    <style>[x-cloak]{display:none!important}</style>
    <script>
        // Scroll reveal
        document.addEventListener('DOMContentLoaded', () => {
            const els = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window)) { els.forEach(e => e.classList.add('in')); return; }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            els.forEach(e => io.observe(e));
        });
    </script>
</body>
</html>
