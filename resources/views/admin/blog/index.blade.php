<x-admin-layout title="Blog" role="admin">
    <div class="mb-4"><a href="{{ route('admin.blog.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">+ Artikel Baru</a></div>
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Judul</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Publish</th><th class="px-4 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($posts as $post)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $post->title }}</td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $post->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $post->status }}</span></td>
                        <td class="px-4 py-3 text-slate-500">{{ optional($post->published_at)->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Edit</a>
                                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Hapus</button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</x-admin-layout>
