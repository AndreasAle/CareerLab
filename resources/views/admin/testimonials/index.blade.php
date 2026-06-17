<x-admin-layout title="Testimonials" role="admin">
    <div x-data="{ open: false }" class="mb-6">
        <button @click="open=!open" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">+ Testimoni Baru</button>
        <form x-show="open" x-cloak method="POST" action="{{ route('admin.testimonials.store') }}" class="mt-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
            @csrf
            <div class="grid gap-3 sm:grid-cols-2">
                <input type="text" name="user_name" required placeholder="Nama" class="rounded-xl border-slate-300 text-sm">
                <input type="text" name="role" placeholder="Role" class="rounded-xl border-slate-300 text-sm">
            </div>
            <textarea name="content" rows="3" required placeholder="Isi testimoni" class="w-full rounded-xl border-slate-300 text-sm"></textarea>
            <div class="flex items-center gap-4">
                <input type="number" name="rating" min="1" max="5" value="5" class="w-20 rounded-xl border-slate-300 text-sm">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded"> Aktif</label>
                <button class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
            </div>
        </form>
    </div>

    <div class="space-y-3">
        @foreach ($testimonials as $t)
            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div>
                    <p class="font-semibold text-slate-800">{{ $t->user_name }} <span class="text-amber-400">{!! str_repeat('★', $t->rating) !!}</span></p>
                    <p class="text-xs text-slate-500">{{ $t->role }} · {{ Str::limit($t->content, 80) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $t->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $t->is_active ? 'Aktif' : 'Off' }}</span>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                        <button class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Hapus</button></form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $testimonials->links() }}</div>
</x-admin-layout>
