<x-dashboard-layout title="Application Tracker">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Application Tracker</h2>
        <p class="text-sm text-slate-500">Catat semua lamaran kamu biar nggak ada yang kelewat.</p>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
        @foreach ([
            ['Total', $stats['total'], 'text-slate-800'],
            ['Interview', $stats['interview'], 'text-blue-600'],
            ['Offering', $stats['offering'], 'text-emerald-600'],
            ['Ditolak', $stats['rejected'], 'text-red-600'],
            ['Conversion', $stats['conversion'].'%', 'text-purple-600'],
        ] as [$lbl, $val, $color])
            <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-2xl font-bold {{ $color }}">{{ $val }}</p>
                <p class="text-[11px] text-slate-500">{{ $lbl }}</p>
            </div>
        @endforeach
    </div>

    {{-- Add form --}}
    <div x-data="{ open: false }" class="mb-6">
        <button @click="open = !open" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">+ Tambah Lamaran</button>
        <form x-show="open" x-cloak method="POST" action="{{ route('applications.store') }}" class="mt-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <input type="text" name="company_name" required placeholder="Nama perusahaan" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <input type="text" name="position" required placeholder="Posisi" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <input type="text" name="job_source" placeholder="Sumber / link lowongan" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <input type="date" name="applied_at" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <select name="status" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @foreach ($statuses as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
                <input type="date" name="follow_up_date" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <textarea name="notes" rows="2" placeholder="Catatan" class="sm:col-span-2 rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
            </div>
            <button type="submit" class="mt-4 rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Simpan</button>
        </form>
    </div>

    {{-- List --}}
    @php
        $statusColors = [
            'saved'=>'bg-slate-100 text-slate-600','applied'=>'bg-blue-100 text-blue-700','screening'=>'bg-cyan-100 text-cyan-700',
            'interview'=>'bg-indigo-100 text-indigo-700','test'=>'bg-purple-100 text-purple-700','offering'=>'bg-emerald-100 text-emerald-700',
            'accepted'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700','ghosted'=>'bg-amber-100 text-amber-700',
        ];
    @endphp
    @if ($apps->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada lamaran tercatat.</div>
    @else
        <div class="space-y-3">
            @foreach ($apps as $app)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800">{{ $app->position }}</p>
                            <p class="text-xs text-slate-500">{{ $app->company_name }}@if($app->applied_at) · apply {{ $app->applied_at->format('d M Y') }}@endif</p>
                            @if ($app->follow_up_date)
                                <p class="mt-1 inline-block rounded-full bg-amber-50 px-2 py-0.5 text-[11px] text-amber-700">⏰ Follow up: {{ $app->follow_up_date->format('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('applications.status', $app) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-200 py-1 text-xs font-semibold {{ $statusColors[$app->status] }}">
                                    @foreach ($statuses as $s)<option value="{{ $s }}" @selected($app->status === $s)>{{ ucfirst($s) }}</option>@endforeach
                                </select>
                            </form>
                            <form method="POST" action="{{ route('applications.destroy', $app) }}" onsubmit="return confirm('Hapus lamaran ini?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @if ($app->notes)<p class="mt-2 border-t border-slate-100 pt-2 text-sm text-slate-600">{{ $app->notes }}</p>@endif
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
