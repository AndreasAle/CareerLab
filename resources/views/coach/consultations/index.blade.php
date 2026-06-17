<x-admin-layout title="Kelola Konsultasi" role="coach">
    <div class="space-y-4">
        @forelse ($bookings as $b)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $b->topic }}</p>
                        <p class="text-xs text-slate-500">{{ $b->user->name }} · {{ optional($b->scheduled_at)->format('d M Y H:i') }} · {{ $b->duration_minutes }} menit</p>
                        @if ($b->notes)<p class="mt-1 text-sm text-slate-600">{{ $b->notes }}</p>@endif
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($b->status) }}</span>
                </div>
                <form method="POST" action="{{ route('coach.consultations.update', $b) }}" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-3">
                    @csrf @method('PATCH')
                    <select name="status" class="rounded-xl border-slate-300 text-sm">
                        @foreach (['pending','confirmed','completed','cancelled'] as $s)<option value="{{ $s }}" @selected($b->status===$s)>{{ ucfirst($s) }}</option>@endforeach
                    </select>
                    <input type="text" name="meeting_link" value="{{ $b->meeting_link }}" placeholder="Link meeting" class="rounded-xl border-slate-300 text-sm">
                    <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-4 py-2 text-sm font-semibold text-white">Update</button>
                </form>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada konsultasi.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-admin-layout>
