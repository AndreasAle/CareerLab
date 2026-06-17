<x-admin-layout title="Edit Prompt AI" role="admin">
    <form method="POST" action="{{ route('admin.ai-prompts.update', $prompt) }}"
          class="max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-500">Key: <span class="font-mono">{{ $prompt->key }}</span> (tidak bisa diubah)</div>
        <div><label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text" name="name" required value="{{ old('name', $prompt->name) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <input type="text" name="description" value="{{ old('description', $prompt->description) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700">System Prompt</label>
            <textarea name="system_prompt" rows="5" required class="mt-1 w-full rounded-xl border-slate-300 font-mono text-xs">{{ old('system_prompt', $prompt->system_prompt) }}</textarea></div>
        <div><label class="block text-sm font-medium text-slate-700">User Prompt Template</label>
            <textarea name="user_prompt_template" rows="10" required class="mt-1 w-full rounded-xl border-slate-300 font-mono text-xs">{{ old('user_prompt_template', $prompt->user_prompt_template) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$prompt->is_active)) class="rounded"> Aktif</label>
        <div class="flex gap-2">
            <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Simpan</button>
            <a href="{{ route('admin.ai-prompts.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
