<x-admin-layout :title="$post->exists ? 'Edit Artikel' : 'Artikel Baru'" role="admin">
    <form method="POST" action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}"
          class="max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        @if ($post->exists) @method('PUT') @endif
        <div><label class="block text-sm font-medium text-slate-700">Judul</label>
            <input type="text" name="title" required value="{{ old('title', $post->title) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">Excerpt</label>
            <input type="text" name="excerpt" value="{{ old('excerpt', $post->excerpt) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">Konten</label>
            <textarea name="content" rows="12" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('content', $post->content) }}</textarea></div>
        <div><label class="block text-sm font-medium text-slate-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                <option value="draft" @selected(old('status',$post->status)==='draft')>Draft</option>
                <option value="published" @selected(old('status',$post->status)==='published')>Published</option>
            </select></div>
        <div class="flex gap-2">
            <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Simpan</button>
            <a href="{{ route('admin.blog.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
