<x-admin-layout :title="$template->exists ? 'Edit Template' : 'Template Baru'" role="admin">
    <form method="POST" action="{{ $template->exists ? route('admin.templates.update', $template) : route('admin.templates.store') }}"
          class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        @if ($template->exists) @method('PUT') @endif
        <div><label class="block text-sm font-medium text-slate-700">Judul</label>
            <input type="text" name="title" required value="{{ old('title', $template->title) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">Tipe</label>
            <select name="type" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                @foreach (\App\Models\Template::TYPES as $k=>$lbl)<option value="{{ $k }}" @selected(old('type',$template->type)===$k)>{{ $lbl }}</option>@endforeach
            </select></div>
        <div><label class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <input type="text" name="description" value="{{ old('description', $template->description) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">Konten</label>
            <textarea name="content" rows="8" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('content', $template->content) }}</textarea></div>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_premium" value="1" @checked(old('is_premium',$template->is_premium)) class="rounded"> Premium</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$template->is_active ?? true)) class="rounded"> Aktif</label>
        </div>
        <div class="flex gap-2">
            <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Simpan</button>
            <a href="{{ route('admin.templates.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
