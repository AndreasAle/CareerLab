<x-dashboard-layout title="Career Report">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Career Diagnosis Report</h2>
            <p class="text-sm text-slate-500">Gabungkan hasil CV Review, Job Match, Red Flag & Interview jadi satu report PDF premium.</p>
        </div>
        <form method="POST" action="{{ route('career-report.generate') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <button type="submit" :disabled="loading"
                    class="rounded-xl bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
                <span x-show="!loading">✨ Generate Report Baru</span>
                <span x-show="loading" x-cloak>Menyusun report...</span>
            </button>
        </form>
    </div>

    @unless ($hasCv)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
            Kamu perlu <a href="{{ route('cv.index') }}" class="font-semibold underline">melakukan CV Review</a> dulu sebelum bisa generate report.
        </div>
    @endunless

    @if ($reports->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 text-2xl">📄</div>
            <p class="text-sm text-slate-500">Belum ada report. Klik "Generate Report Baru" untuk membuat Career Diagnosis pertamamu.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($reports as $report)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-slate-900 text-white">
                            <span class="text-lg font-bold">{{ $report->overall_score }}</span>
                            <span class="text-[9px] text-slate-400">/100</span>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $report->title }}</p>
                            <p class="text-xs text-slate-400">{{ $report->created_at->diffForHumans() }} · {{ ucfirst($report->report_data['career_readiness_level'] ?? '-') }}</p>
                        </div>
                    </div>
                    @if ($report->pdf_path)
                        <a href="{{ route('career-report.download', $report) }}" class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">⬇ Download PDF</a>
                    @else
                        <span class="text-xs text-slate-400">PDF tidak tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
