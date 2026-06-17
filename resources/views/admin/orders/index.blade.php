<x-admin-layout title="Orders" role="admin">
    <form method="GET" class="mb-4 flex gap-2">
        <select name="status" class="rounded-xl border-slate-300 text-sm">
            <option value="">Semua status</option>
            @foreach (['unpaid','paid','failed','expired'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach
        </select>
        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Kode</th><th class="px-4 py-3">User</th><th class="px-4 py-3">Plan</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Bukti</th><th class="px-4 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                    @php $sc = ['unpaid'=>'bg-amber-100 text-amber-700','paid'=>'bg-emerald-100 text-emerald-700','failed'=>'bg-red-100 text-red-700','expired'=>'bg-slate-100 text-slate-600'][$order->payment_status]; @endphp
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->order_code }}</td>
                        <td class="px-4 py-3">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->plan->name ?? '-' }}</td>
                        <td class="px-4 py-3">Rp{{ number_format($order->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $sc }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td class="px-4 py-3">{{ $order->proof_path ? '📎 ada' : '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($order->payment_status === 'unpaid')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.orders.approve', $order) }}">@csrf @method('PATCH')
                                        <button class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Setujui</button></form>
                                    <form method="POST" action="{{ route('admin.orders.reject', $order) }}">@csrf @method('PATCH')
                                        <button class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Tolak</button></form>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada order.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</x-admin-layout>
