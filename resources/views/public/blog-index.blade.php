<x-public-layout title="Blog">
    {{-- hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute right-0 top-10 h-72 w-72 rounded-full bg-violet-200/40 blur-3xl"></div>
        </div>
        <div class="mx-auto max-w-3xl px-4 py-16 text-center lg:px-8 lg:py-20">
            <span class="reveal inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50/70 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                <x-icon name="doc" class="h-3.5 w-3.5"/> Insight Karier
            </span>
            <h1 class="reveal mt-5 text-4xl font-extrabold tracking-tight sm:text-5xl">Blog CareerLab</h1>
            <p class="reveal mt-4 text-lg text-slate-500">Tips praktis seputar CV, interview, negosiasi gaji, dan dunia kerja.</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-24 lg:px-8">
        @if ($posts->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-16 text-center">
                <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-indigo-50 text-indigo-600"><x-icon name="doc" class="h-7 w-7"/></div>
                <p class="text-sm text-slate-500">Belum ada artikel. Nantikan ya!</p>
            </div>
        @else
            {{-- featured (first post) --}}
            @php $featured = $posts->first(); @endphp
            <a href="{{ route('blog.show', $featured) }}" class="reveal group mb-10 grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-xl lg:grid-cols-2">
                <div class="relative min-h-[220px] bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600">
                    <div class="absolute inset-0 grid place-items-center"><x-icon name="spark" class="h-16 w-16 text-white/30"/></div>
                    <span class="absolute left-5 top-5 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-indigo-700">Featured</span>
                </div>
                <div class="flex flex-col justify-center p-7">
                    <p class="text-xs font-medium text-slate-400">{{ optional($featured->published_at)->isoFormat('D MMMM Y') }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-800 transition group-hover:text-indigo-600">{{ $featured->title }}</h2>
                    <p class="mt-3 text-slate-500">{{ $featured->excerpt }}</p>
                    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 transition group-hover:gap-2.5">Baca selengkapnya <x-icon name="arrow" class="h-4 w-4"/></span>
                </div>
            </a>

            {{-- grid --}}
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts->slice(1) as $i => $post)
                    <a href="{{ route('blog.show', $post) }}" class="reveal group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl" style="--reveal-delay:{{ $i*70 }}ms">
                        <div class="relative h-40 bg-gradient-to-br from-indigo-100 via-violet-100 to-purple-100">
                            <div class="absolute inset-0 grid place-items-center"><x-icon name="doc" class="h-10 w-10 text-indigo-300"/></div>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <p class="text-xs font-medium text-slate-400">{{ optional($post->published_at)->isoFormat('D MMM Y') }}</p>
                            <h3 class="mt-2 font-bold text-slate-800 transition group-hover:text-indigo-600">{{ $post->title }}</h3>
                            <p class="mt-2 flex-1 text-sm text-slate-500 line-clamp-2">{{ $post->excerpt }}</p>
                            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 transition group-hover:gap-2.5">Baca <x-icon name="arrow-ur" class="h-4 w-4"/></span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </section>
</x-public-layout>
