<x-admin-layout title="Users" role="admin">
    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email..." class="rounded-xl border-slate-300 text-sm">
        <select name="role" class="rounded-xl border-slate-300 text-sm">
            <option value="">Semua role</option>
            @foreach (['user','coach','admin'] as $r)<option value="{{ $r }}" @selected(request('role')===$r)>{{ ucfirst($r) }}</option>@endforeach
        </select>
        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <select name="role" class="rounded-lg border-slate-200 py-1 text-xs">
                                    @foreach (['user','coach','admin'] as $r)<option value="{{ $r }}" @selected($user->role===$r)>{{ ucfirst($r) }}</option>@endforeach
                                </select>
                                <input type="hidden" name="is_active" value="{{ $user->is_active ? 1 : 0 }}">
                                <button class="rounded-lg bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700">Simpan</button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">@csrf @method('PATCH')
                                <button class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
