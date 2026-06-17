<x-dashboard-layout title="Pesanan Saya">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">Pesanan & Langganan</h2>
        <a href="{{ route('pricing') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Lihat Paket</a>
    </div>

    @if ($orders->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada pesanan. <a href="{{ route('pricing') }}" class="font-semibold text-emerald-600 underline">Pilih paket</a></div>
    @else
        <div class="space-y-3">
            @foreach ($orders as $order)
                @php $sc = ['unpaid'=>'bg-amber-100 text-amber-700','paid'=>'bg-emerald-100 text-emerald-700','failed'=>'bg-red-100 text-red-700','expired'=>'bg-slate-100 text-slate-600'][$order->payment_status]; @endphp
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $order->plan->name ?? 'Paket' }}</p>
                        <p class="text-xs text-slate-400">{{ $order->order_code }} · Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $sc }}">{{ ucfirst($order->payment_status) }}</span>
                        <a href="{{ route('orders.show', $order) }}" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
