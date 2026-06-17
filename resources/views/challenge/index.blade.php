<x-dashboard-layout title="Challenge 7 Hari">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Challenge 7 Hari Siap Kerja</h2>
        <p class="text-sm text-slate-500">Selesaikan 1 misi tiap hari biar makin siap masuk dunia kerja.</p>
    </div>

    @if (! $challenge)
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada challenge aktif.</div>
    @else
        {{-- Progress --}}
        <div class="mb-6 rounded-2xl bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 p-6 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold">{{ $challenge->title }}</h3>
                    <p class="text-sm text-white/80">{{ $completedTaskIds->count() }} dari {{ $challenge->tasks->count() }} misi selesai</p>
                </div>
                <span class="text-3xl font-extrabold">{{ $progressPercent }}%</span>
            </div>
            <div class="mt-4 h-2.5 w-full overflow-hidden rounded-full bg-white/25">
                <div class="h-full rounded-full bg-white transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        {{-- Tasks --}}
        <div class="space-y-3">
            @foreach ($challenge->tasks as $task)
                @php $done = $completedTaskIds->contains($task->id); @endphp
                <div class="flex items-center justify-between rounded-2xl border bg-white p-4 shadow-sm {{ $done ? 'border-emerald-200' : 'border-slate-200' }}">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold {{ $done ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ $done ? '✓' : 'H'.$task->day_number }}
                        </div>
                        <div>
                            <p class="font-semibold {{ $done ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $task->title }}</p>
                            <p class="text-xs text-slate-500">{{ $task->description }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('challenge.toggle', $task) }}">
                        @csrf
                        <button class="rounded-lg px-3 py-1.5 text-xs font-semibold {{ $done ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                            {{ $done ? 'Batalkan' : 'Selesai' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
