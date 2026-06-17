<x-admin-layout title="Consultations" role="admin">
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">User</th><th class="px-4 py-3">Topik</th><th class="px-4 py-3">Jadwal</th><th class="px-4 py-3">Coach</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Assign</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($bookings as $b)
                    <tr>
                        <td class="px-4 py-3">{{ $b->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $b->topic }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ optional($b->scheduled_at)->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $b->coach->name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ ucfirst($b->status) }}</span></td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.consultations.assign', $b) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="coach_id" class="rounded-lg border-slate-200 py-1 text-xs">
                                    @foreach ($coaches as $coach)<option value="{{ $coach->id }}" @selected($b->coach_id===$coach->id)>{{ $coach->name }}</option>@endforeach
                                </select>
                                <button class="rounded-lg bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700">Assign</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-admin-layout>
