<x-dashboard-layout title="Challenge 7 Hari">
    <x-page-head icon="bolt" gradient="from-amber-500 to-yellow-500"
                 title="Challenge 7 Hari Siap Kerja"
                 subtitle="Selesaikan 1 misi tiap hari biar makin siap masuk dunia kerja." />

    @if (! $challenge)
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada challenge aktif.</div>
    @else
        <div class="cl-rise relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600 p-6 text-white shadow-sm">
            <div class="absolute -right-8 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h3 class="font-bold">{{ $challenge->title }}</h3>
                    <p class="text-sm text-white/80">{{ $completedTaskIds->count() }} dari {{ $challenge->tasks->count() }} misi selesai</p>
                </div>
                <span class="text-3xl font-extrabold">{{ $progressPercent }}%</span>
            </div>
            <div class="relative mt-4 h-2.5 w-full overflow-hidden rounded-full bg-white/25">
                <div class="cl-bar h-full rounded-full bg-white" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($challenge->tasks as $i => $task)
                @php $done = $completedTaskIds->contains($task->id); @endphp
                <div class="cl-rise flex items-center justify-between rounded-2xl border bg-white p-4 shadow-sm {{ $done ? 'border-emerald-200' : 'border-slate-200' }}" style="--reveal-delay:{{ $i*40 }}ms">
                    <div class="flex items-center gap-4">
                        <div class="grid h-11 w-11 place-items-center rounded-xl text-sm font-bold {{ $done ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white' : 'bg-slate-100 text-slate-500' }}">
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
