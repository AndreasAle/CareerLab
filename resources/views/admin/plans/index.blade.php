<x-admin-layout title="Plans" role="admin">
    <div class="mb-4"><a href="{{ route('admin.plans.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">+ Plan Baru</a></div>
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Harga</th><th class="px-4 py-3">Durasi</th><th class="px-4 py-3">CV/Int/JM/Rep</th><th class="px-4 py-3">Aktif</th><th class="px-4 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($plans as $plan)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $plan->name }}</td>
                        <td class="px-4 py-3">{{ $plan->priceFormatted() }}</td>
                        <td class="px-4 py-3">{{ $plan->duration_days }} hari</td>
                        <td class="px-4 py-3 text-slate-500">{{ $plan->cv_review_limit }}/{{ $plan->interview_limit }}/{{ $plan->job_match_limit }}/{{ $plan->report_limit }}</td>
                        <td class="px-4 py-3">{{ $plan->is_active ? '✅' : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Edit</a>
                                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Hapus plan?')">@csrf @method('DELETE')
                                    <button class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
