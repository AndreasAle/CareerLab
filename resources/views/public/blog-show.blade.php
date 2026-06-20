<x-public-layout :title="$post->title">
    <article class="mx-auto max-w-3xl px-4 py-16 lg:px-8">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
            <x-icon name="chevron" class="h-4 w-4 rotate-180"/> Semua artikel
        </a>

        <div class="mt-6">
            <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700"><x-icon name="doc" class="h-3.5 w-3.5"/> Insight Karier</span>
            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">{{ $post->title }}</h1>
            <div class="mt-4 flex items-center gap-3 text-sm text-slate-400">
                <span class="flex items-center gap-1.5"><x-icon name="calendar" class="h-4 w-4"/> {{ optional($post->published_at)->isoFormat('D MMMM Y') }}</span>
                <span class="flex items-center gap-1.5"><x-icon name="clock" class="h-4 w-4"/> {{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} menit baca</span>
            </div>
        </div>

        <div class="mt-8 grid h-56 place-items-center rounded-3xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600">
            <x-icon name="spark" class="h-16 w-16 text-white/40"/>
        </div>

        <div class="mt-10 text-[15px] leading-relaxed text-slate-700">
            @foreach (preg_split('/\n\n+/', $post->content) as $para)
                <p class="mb-5 whitespace-pre-line">{{ $para }}</p>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="mt-12 overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-7 text-white">
            <h3 class="text-xl font-bold">Praktikkan langsung di CareerLab AI</h3>
            <p class="mt-2 text-sm text-white/80">Upload CV kamu dan lihat cara HRD membacanya — gratis.</p>
            <a href="{{ route('register') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:scale-[1.03]">
                <x-icon name="spark" class="h-4 w-4 text-indigo-600"/> Cek CV Gratis
            </a>
        </div>
    </article>
</x-public-layout>
