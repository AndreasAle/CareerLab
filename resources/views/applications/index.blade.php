<x-dashboard-layout title="Application Tracker">
    <x-page-head icon="briefcase" gradient="from-amber-500 to-orange-600"
                 title="Application Tracker"
                 subtitle="Catat semua lamaran kamu biar nggak ada yang kelewat." />

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
        @foreach ([
            ['Total', $stats['total'], 'text-slate-800', 'briefcase'],
            ['Interview', $stats['interview'], 'text-blue-600', 'chat'],
            ['Offering', $stats['offering'], 'text-emerald-600', 'check-c'],
            ['Ditolak', $stats['rejected'], 'text-rose-600', 'x'],
            ['Conversion', $stats['conversion'].'%', 'text-violet-600', 'graph'],
        ] as $idx => [$lbl, $val, $color, $ic])
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm" style="--reveal-delay:{{ $idx*40 }}ms">
                <div class="mx-auto mb-1.5 grid h-8 w-8 place-items-center rounded-lg bg-slate-100 {{ $color }}"><x-icon :name="$ic" class="h-4 w-4"/></div>
                <p class="text-2xl font-bold {{ $color }}">{{ $val }}</p>
                <p class="text-[11px] text-slate-500">{{ $lbl }}</p>
            </div>
        @endforeach
    </div>

    {{-- Add form --}}
    <div x-data="{ open: false }" class="mb-6">
        <button @click="open = !open" class="flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"><x-icon name="briefcase" class="h-4 w-4"/> Tambah Lamaran</button>
        <form x-show="open" x-cloak x-transition method="POST" action="{{ route('applications.store') }}" class="mt-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <input type="text" name="company_name" required placeholder="Nama perusahaan" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="position" required placeholder="Posisi" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="job_source" placeholder="Sumber / link lowongan" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="date" name="applied_at" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select name="status" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($statuses as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
                <input type="date" name="follow_up_date" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <textarea name="notes" rows="2" placeholder="Catatan" class="sm:col-span-2 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>
            <button type="submit" class="mt-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25">Simpan</button>
        </form>
    </div>

    @php
        $statusColors = [
            'saved'=>'bg-slate-100 text-slate-600','applied'=>'bg-blue-100 text-blue-700','screening'=>'bg-cyan-100 text-cyan-700',
            'interview'=>'bg-indigo-100 text-indigo-700','test'=>'bg-violet-100 text-violet-700','offering'=>'bg-emerald-100 text-emerald-700',
            'accepted'=>'bg-green-100 text-green-700','rejected'=>'bg-rose-100 text-rose-700','ghosted'=>'bg-amber-100 text-amber-700',
        ];
    @endphp
    @if ($apps->isEmpty())
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada lamaran tercatat.</div>
    @else
        <div class="space-y-3">
            @foreach ($apps as $i => $app)
                <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" style="--reveal-delay:{{ $i*30 }}ms">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800">{{ $app->position }}</p>
                            <p class="text-xs text-slate-500">{{ $app->company_name }}@if($app->applied_at) · apply {{ $app->applied_at->format('d M Y') }}@endif</p>
                            @if ($app->follow_up_date)
                                <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[11px] text-amber-700"><x-icon name="clock" class="h-3 w-3"/> Follow up: {{ $app->follow_up_date->format('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('applications.status', $app) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-200 py-1 text-xs font-semibold {{ $statusColors[$app->status] }}">
                                    @foreach ($statuses as $s)<option value="{{ $s }}" @selected($app->status === $s)>{{ ucfirst($s) }}</option>@endforeach
                                </select>
                            </form>
                            <form method="POST" action="{{ route('applications.destroy', $app) }}" onsubmit="return confirm('Hapus lamaran ini?')">
                                @csrf @method('DELETE')
                                <button class="grid h-8 w-8 place-items-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100"><x-icon name="x" class="h-4 w-4"/></button>
                            </form>
                        </div>
                    </div>
                    @if ($app->notes)<p class="mt-2 border-t border-slate-100 pt-2 text-sm text-slate-600">{{ $app->notes }}</p>@endif
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
