<x-admin-layout title="AI Logs" role="admin">
    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <select name="feature" class="rounded-xl border-slate-300 text-sm">
            <option value="">Semua fitur</option>
            @foreach ($features as $f)<option value="{{ $f }}" @selected(request('feature')===$f)>{{ $f }}</option>@endforeach
        </select>
        <select name="status" class="rounded-xl border-slate-300 text-sm">
            <option value="">Semua status</option>
            <option value="success" @selected(request('status')==='success')>Success</option>
            <option value="failed" @selected(request('status')==='failed')>Failed</option>
        </select>
        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Waktu</th><th class="px-4 py-3">User</th><th class="px-4 py-3">Fitur</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Tokens</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-slate-500">{{ $log->created_at->format('d M H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->user->name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="rounded bg-slate-100 px-2 py-0.5 font-mono text-xs">{{ $log->feature_key }}</span></td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $log->status }}</span></td>
                        <td class="px-4 py-3 text-slate-500">{{ $log->input_tokens }}/{{ $log->output_tokens }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada log AI.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</x-admin-layout>
