<x-public-layout :title="$post->title">
    <article class="mx-auto max-w-3xl px-4 py-16 lg:px-8">
        <a href="{{ route('blog.index') }}" class="text-sm text-slate-500 hover:text-slate-700">← Semua artikel</a>
        <h1 class="mt-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ $post->title }}</h1>
        <p class="mt-2 text-sm text-slate-400">{{ optional($post->published_at)->format('d M Y') }}</p>
        <div class="mt-8 h-48 rounded-2xl bg-gradient-to-br from-emerald-100 via-blue-100 to-purple-100"></div>
        <div class="prose prose-slate mt-8 max-w-none text-slate-700">
            @foreach (preg_split('/\n\n+/', $post->content) as $para)
                <p class="mb-4 whitespace-pre-line leading-relaxed">{{ $para }}</p>
            @endforeach
        </div>
    </article>
</x-public-layout>
