<x-admin-layout title="Dashboard Admin" role="admin">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $cards = [
                ['Total User', $stats['total_users'], 'from-emerald-500 to-emerald-600'],
                ['Langganan Aktif', $stats['active_subscriptions'], 'from-blue-500 to-blue-600'],
                ['Total Order', $stats['total_orders'], 'from-purple-500 to-purple-600'],
                ['Revenue', 'Rp' . number_format($stats['revenue'], 0, ',', '.'), 'from-amber-500 to-orange-600'],
                ['CV Review Hari Ini', $stats['cv_reviews_today'], 'from-teal-500 to-teal-600'],
                ['AI Usage Hari Ini', $stats['ai_usage_today'], 'from-indigo-500 to-indigo-600'],
                ['Pembayaran Pending', $stats['pending_payments'], 'from-rose-500 to-rose-600'],
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

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.orders.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Verifikasi Pembayaran ({{ $stats['pending_payments'] }})</a>
        <a href="{{ route('admin.ai-prompts.index') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700">Edit Prompt AI</a>
        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700">Kelola User</a>
    </div>
</x-admin-layout>
