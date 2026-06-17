<x-admin-layout title="AI Prompts" role="admin">
    <p class="mb-4 text-sm text-slate-500">Edit prompt AI tanpa deploy ulang. Gunakan placeholder seperti <code class="rounded bg-slate-100 px-1">@{{cv_text}}</code>.</p>
    <div class="space-y-3">
        @foreach ($prompts as $p)
            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div>
                    <p class="font-semibold text-slate-800">{{ $p->name }} <span class="ml-2 rounded bg-slate-100 px-2 py-0.5 font-mono text-xs text-slate-500">{{ $p->key }}</span></p>
                    <p class="text-xs text-slate-500">{{ $p->description }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $p->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $p->is_active ? 'Aktif' : 'Off' }}</span>
                    <a href="{{ route('admin.ai-prompts.edit', $p) }}" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Edit</a>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
