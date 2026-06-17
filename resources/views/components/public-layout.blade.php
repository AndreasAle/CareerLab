@props(['title' => 'CareerLab AI'])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Latihan Masuk Kerja Sebelum Ketemu HRD</title>
    <meta name="description" content="Upload CV, cek cara HRD membaca profil kamu, latihan interview, deteksi red flag, dan siapkan lamaran kerja dengan CareerLab AI.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased">
    <header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-100 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600"></div>
                <span class="text-lg font-bold tracking-tight">CareerLab<span class="text-emerald-600">AI</span></span>
            </a>
            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}#fitur" class="text-sm font-medium text-slate-600 hover:text-slate-900">Fitur</a>
                <a href="{{ route('pricing') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Harga</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Blog</a>
            </div>
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-4 py-2 text-sm font-semibold text-white">Cek CV Gratis</a>
                @endauth
            </div>
            <button @click="open = !open" class="md:hidden rounded-lg p-2 hover:bg-slate-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </nav>
        <div x-show="open" x-cloak class="border-t border-slate-100 px-4 py-3 md:hidden">
            <a href="{{ route('pricing') }}" class="block py-2 text-sm">Harga</a>
            <a href="{{ route('blog.index') }}" class="block py-2 text-sm">Blog</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-semibold text-emerald-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="block py-2 text-sm font-semibold text-emerald-600">Daftar Gratis</a>
            @endauth
        </div>
    </header>

    {{ $slot }}

    <footer class="border-t border-slate-100 bg-slate-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600"></div>
                    <span class="text-lg font-bold">CareerLab<span class="text-emerald-600">AI</span></span>
                </div>
                <p class="mt-3 max-w-sm text-sm text-slate-500">Career simulator untuk Gen Z & fresh graduate. Latihan masuk kerja sebelum ketemu HRD beneran.</p>
            </div>
            <div>
                <h4 class="mb-3 text-sm font-semibold text-slate-800">Produk</h4>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('pricing') }}" class="hover:text-slate-800">Harga</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-slate-800">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 text-sm font-semibold text-slate-800">Akun</h4>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('login') }}" class="hover:text-slate-800">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-slate-800">Daftar</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">
            © {{ date('Y') }} CareerLab AI. Data CV digunakan hanya untuk analisis career di platform ini.
        </div>
    </footer>
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
