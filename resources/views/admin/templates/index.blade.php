<x-admin-layout title="Templates" role="admin">
    <div class="mb-4"><a href="{{ route('admin.templates.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">+ Template Baru</a></div>
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Judul</th><th class="px-4 py-3">Tipe</th><th class="px-4 py-3">Premium</th><th class="px-4 py-3">Aktif</th><th class="px-4 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($templates as $t)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $t->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->typeLabel() }}</td>
                        <td class="px-4 py-3">{{ $t->is_premium ? '⭐' : '—' }}</td>
                        <td class="px-4 py-3">{{ $t->is_active ? '✅' : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.templates.edit', $t) }}" class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Edit</a>
                                <form method="POST" action="{{ route('admin.templates.destroy', $t) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Hapus</button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $templates->links() }}</div>
</x-admin-layout>
