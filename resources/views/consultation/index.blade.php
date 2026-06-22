<x-dashboard-layout title="Konsultasi">
    <x-page-head icon="calendar" gradient="from-blue-500 to-indigo-600"
                 title="Konsultasi 1-on-1"
                 subtitle="Booking sesi konsultasi dengan career coach kami." />

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('consultation.book') }}"
              class="cl-rise lg:col-span-2 space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Topik Konsultasi</label>
                <input type="text" name="topic" required value="{{ old('topic') }}" placeholder="cth: Review CV & strategi apply"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jadwal</label>
                    <input type="datetime-local" name="scheduled_at" required value="{{ old('scheduled_at') }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" value="60" min="15" max="180" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                <textarea name="notes" rows="3" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>
            <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01]">
                <x-icon name="calendar" class="h-4 w-4"/> Book Konsultasi
            </button>
        </form>

        <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:120ms">
            <h3 class="mb-3 font-semibold text-slate-800">Booking Saya</h3>
            @forelse ($bookings as $b)
                <div class="mb-3 rounded-xl border border-slate-100 p-3">
                    <p class="text-sm font-medium text-slate-700">{{ $b->topic }}</p>
                    <p class="text-xs text-slate-400">{{ optional($b->scheduled_at)->format('d M Y H:i') }}</p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ ucfirst($b->status) }}</span>
                        @if ($b->meeting_link)<a href="{{ $b->meeting_link }}" target="_blank" class="text-xs font-semibold text-indigo-600">Join →</a>@endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">Belum ada booking.</p>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>
