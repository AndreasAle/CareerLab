<x-dashboard-layout title="Konsultasi">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Konsultasi 1-on-1</h2>
        <p class="text-sm text-slate-500">Booking sesi konsultasi dengan career coach kami.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('consultation.book') }}" class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Topik Konsultasi</label>
                <input type="text" name="topic" required value="{{ old('topic') }}" placeholder="cth: Review CV & strategi apply" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jadwal</label>
                    <input type="datetime-local" name="scheduled_at" required value="{{ old('scheduled_at') }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" value="60" min="15" max="180" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                <textarea name="notes" rows="3" class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('notes') }}</textarea>
            </div>
            <button class="w-full rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-3 text-sm font-semibold text-white">Book Konsultasi</button>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-slate-800">Booking Saya</h3>
            @forelse ($bookings as $b)
                <div class="mb-3 rounded-xl border border-slate-100 p-3">
                    <p class="text-sm font-medium text-slate-700">{{ $b->topic }}</p>
                    <p class="text-xs text-slate-400">{{ optional($b->scheduled_at)->format('d M Y H:i') }}</p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ ucfirst($b->status) }}</span>
                        @if ($b->meeting_link)<a href="{{ $b->meeting_link }}" target="_blank" class="text-xs font-semibold text-emerald-600">Join →</a>@endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">Belum ada booking.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
