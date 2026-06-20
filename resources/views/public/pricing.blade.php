<x-public-layout title="Harga">
    {{-- hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -left-20 top-0 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute right-0 top-10 h-80 w-80 rounded-full bg-violet-200/50 blur-3xl"></div>
        </div>
        <div class="mx-auto max-w-3xl px-4 py-16 text-center lg:px-8 lg:py-20">
            @if (session('upgrade'))
                <div class="reveal mb-8 inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-5 py-3 text-sm font-medium text-violet-800">
                    <x-icon name="bolt" class="h-4 w-4"/> {{ session('upgrade') }}
                </div>
            @endif
            <span class="reveal inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50/70 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                <x-icon name="spark" class="h-3.5 w-3.5"/> Harga transparan
            </span>
            <h1 class="reveal mt-5 text-4xl font-extrabold tracking-tight sm:text-5xl">Pilih paket yang pas buat kamu</h1>
            <p class="reveal mt-4 text-lg text-slate-500">Mulai gratis, upgrade kapan saja saat butuh lebih banyak. Tanpa biaya tersembunyi.</p>
        </div>
    </section>

    {{-- plans --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach ($plans as $i => $plan)
                @php $featured = $plan->slug === 'pro'; @endphp
                <div class="reveal flex flex-col rounded-2xl border bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl {{ $featured ? 'border-indigo-400 ring-2 ring-indigo-400' : 'border-slate-200' }}" style="--reveal-delay:{{ $i*70 }}ms">
                    @if ($featured)
                        <span class="mb-3 inline-flex w-fit items-center gap-1 rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 px-3 py-1 text-xs font-semibold text-white"><x-icon name="star" class="h-3 w-3"/> Populer</span>
                    @endif
                    <h3 class="font-bold text-slate-800">{{ $plan->name }}</h3>
                    <div class="mt-3 flex items-end gap-1">
                        <span class="text-3xl font-extrabold tracking-tight">{{ $plan->priceFormatted() }}</span>
                    </div>
                    <p class="text-xs text-slate-400">{{ $plan->duration_days >= 365 ? 'Selamanya' : 'per ' . $plan->duration_days . ' hari' }}</p>

                    <ul class="mt-5 flex-1 space-y-2.5 text-sm text-slate-600">
                        @foreach (($plan->features ?? []) as $f)
                            <li class="flex gap-2"><x-icon name="check-c" class="h-4 w-4 shrink-0 text-emerald-500"/> {{ $f }}</li>
                        @endforeach
                    </ul>

                    @auth
                        @if ($plan->isFree())
                            <a href="{{ route('dashboard') }}" class="mt-6 rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Paket Saat Ini</a>
                        @else
                            <form method="POST" action="{{ route('orders.create') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button class="w-full rounded-xl px-4 py-2.5 text-center text-sm font-semibold {{ $featured ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white' : 'bg-slate-900 text-white hover:bg-slate-700' }}">Pilih {{ $plan->name }}</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="mt-6 rounded-xl px-4 py-2.5 text-center text-sm font-semibold {{ $plan->isFree() ? 'border border-slate-200 text-slate-700 hover:bg-slate-50' : ($featured ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white' : 'bg-slate-900 text-white hover:bg-slate-700') }}">
                            {{ $plan->isFree() ? 'Mulai Gratis' : 'Pilih ' . $plan->name }}
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        <div class="reveal mx-auto mt-10 flex max-w-xl items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-center text-xs text-slate-500">
            <x-icon name="shield" class="h-4 w-4 text-emerald-500"/> Pembayaran MVP via transfer manual. Integrasi Midtrans/Xendit menyusul.
        </div>
    </section>

    {{-- comparison highlights --}}
    <section class="mx-auto max-w-5xl px-4 pb-20 lg:px-8">
        <div class="reveal grid gap-5 sm:grid-cols-3">
            @foreach ([['lock','Aman & privat','Data CV kamu hanya untuk analisis di platform ini.'],['bolt','Langsung jalan','Semua fitur AI aktif begitu langganan diaktifkan.'],['face','Tanpa ribet','Mulai gratis, upgrade hanya saat kamu butuh.']] as $i => [$ic,$t,$d])
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm" style="--reveal-delay:{{ $i*80 }}ms">
                    <div class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-xl bg-indigo-50 text-indigo-600"><x-icon :name="$ic" class="h-6 w-6"/></div>
                    <p class="font-semibold text-slate-800">{{ $t }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-public-layout>
