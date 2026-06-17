<x-dashboard-layout title="Detail Pesanan">
    <a href="{{ route('orders.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Pesanan saya</a>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-bold text-slate-800">{{ $order->plan->name ?? 'Paket' }}</h2>
            <p class="text-sm text-slate-500">Kode: {{ $order->order_code }}</p>
            <p class="mt-4 text-3xl font-extrabold text-slate-900">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
            @php $sc = ['unpaid'=>'bg-amber-100 text-amber-700','paid'=>'bg-emerald-100 text-emerald-700','failed'=>'bg-red-100 text-red-700','expired'=>'bg-slate-100 text-slate-600'][$order->payment_status]; @endphp
            <span class="mt-2 inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $sc }}">{{ ucfirst($order->payment_status) }}</span>

            @if ($order->payment_status === 'paid')
                <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800">✅ Pembayaran terverifikasi. Langganan kamu aktif!</div>
            @endif
        </div>

        @if ($order->payment_status === 'unpaid')
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-slate-800">Instruksi Pembayaran (Manual)</h3>
                <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm">
                    <p class="text-slate-500">Transfer ke:</p>
                    <p class="font-semibold text-slate-800">{{ $bankInfo }}</p>
                    <p class="mt-2 text-slate-500">Nominal:</p>
                    <p class="font-semibold text-slate-800">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                </div>

                <form method="POST" action="{{ route('orders.proof', $order) }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <label class="block text-sm font-medium text-slate-700">Upload Bukti Transfer</label>
                    <input type="file" name="proof" accept="image/*,application/pdf" required class="mt-1 w-full text-sm">
                    <button class="mt-3 w-full rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Kirim Bukti</button>
                </form>
                @if ($order->proof_path)
                    <p class="mt-3 text-xs text-emerald-600">📎 Bukti sudah terkirim. Menunggu verifikasi admin.</p>
                @endif
            </div>
        @endif
    </div>
</x-dashboard-layout>
