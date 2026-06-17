<x-admin-layout title="Dashboard Coach" role="coach">
    <div class="grid gap-4 sm:grid-cols-3">
        @php
            $cards = [
                ['Total Booking', $stats['total_booking'], 'from-emerald-500 to-emerald-600'],
                ['Upcoming', $stats['upcoming'], 'from-blue-500 to-blue-600'],
                ['Selesai', $stats['completed'], 'from-purple-500 to-purple-600'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $grad])
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-3 h-9 w-9 rounded-xl bg-gradient-to-br {{ $grad }}"></div>
                <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
                <p class="text-xs text-slate-500">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Konsultasi Mendatang</h2>
            <a href="{{ route('coach.consultations.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Kelola semua →</a>
        </div>
        @forelse ($upcomingBookings as $b)
            <div class="flex items-center justify-between border-b border-slate-100 py-3 last:border-0">
                <div>
                    <p class="font-medium text-slate-800">{{ $b->topic }}</p>
                    <p class="text-xs text-slate-500">{{ $b->user->name }} · {{ optional($b->scheduled_at)->format('d M Y H:i') ?? 'Belum dijadwalkan' }}</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ ucfirst($b->status) }}</span>
            </div>
        @empty
            <p class="py-6 text-center text-sm text-slate-500">Belum ada konsultasi terjadwal.</p>
        @endforelse
    </div>
</x-admin-layout>
