<x-public-layout title="Harga">
    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        @if (session('upgrade'))
            <div class="mb-8 rounded-2xl border border-purple-200 bg-purple-50 px-5 py-4 text-sm font-medium text-purple-800">
                {{ session('upgrade') }}
            </div>
        @endif

        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-slate-900">Pilih paket yang pas buat kamu</h1>
            <p class="mt-3 text-slate-600">Mulai gratis, upgrade kapan saja saat butuh lebih banyak.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach ($plans as $plan)
                <div class="flex flex-col rounded-2xl border bg-white p-6 shadow-sm {{ $plan->slug === 'pro' ? 'border-emerald-400 ring-2 ring-emerald-400' : 'border-slate-200' }}">
                    @if ($plan->slug === 'pro')<span class="mb-2 inline-block rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-semibold text-emerald-700">Populer</span>@endif
                    <h3 class="font-bold text-slate-800">{{ $plan->name }}</h3>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $plan->priceFormatted() }}</p>
                    <p class="text-xs text-slate-400">{{ $plan->duration_days >= 365 ? 'Selamanya' : 'per ' . $plan->duration_days . ' hari' }}</p>
                    <ul class="mt-4 flex-1 space-y-2 text-sm text-slate-600">
                        @foreach (($plan->features ?? []) as $f)
                            <li class="flex gap-2"><span class="text-emerald-500">✓</span> {{ $f }}</li>
                        @endforeach
                    </ul>
                    @auth
                        @if ($plan->isFree())
                            <a href="{{ route('dashboard') }}" class="mt-6 rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Paket Saat Ini</a>
                        @else
                            <form method="POST" action="{{ route('orders.create') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button class="w-full rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-4 py-2.5 text-center text-sm font-semibold text-white">Pilih {{ $plan->name }}</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="mt-6 rounded-xl px-4 py-2.5 text-center text-sm font-semibold {{ $plan->isFree() ? 'border border-slate-300 text-slate-700 hover:bg-slate-50' : 'bg-gradient-to-r from-emerald-500 to-blue-500 text-white' }}">
                            {{ $plan->isFree() ? 'Mulai Gratis' : 'Pilih ' . $plan->name }}
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        <p class="mt-10 text-center text-xs text-slate-400">
            Pembayaran MVP saat ini via <strong>transfer manual</strong>. Integrasi Midtrans/Xendit menyusul.
        </p>
    </section>
</x-public-layout>
