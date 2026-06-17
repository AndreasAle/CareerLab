<x-public-layout title="Blog">
    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-slate-900">Blog Karier</h1>
            <p class="mt-3 text-slate-600">Tips praktis seputar CV, interview, dan dunia kerja.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <a href="{{ route('blog.show', $post) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="mb-4 h-32 rounded-xl bg-gradient-to-br from-emerald-100 via-blue-100 to-purple-100"></div>
                    <h3 class="font-semibold text-slate-800 group-hover:text-emerald-600">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-slate-500 line-clamp-2">{{ $post->excerpt }}</p>
                    <p class="mt-3 text-xs text-slate-400">{{ optional($post->published_at)->format('d M Y') }}</p>
                </a>
            @empty
                <p class="col-span-full py-10 text-center text-sm text-slate-500">Belum ada artikel.</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    </section>
</x-public-layout>
