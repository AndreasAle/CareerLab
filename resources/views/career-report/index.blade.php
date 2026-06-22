<x-dashboard-layout title="Career Report">
    <x-page-head icon="spark" gradient="from-indigo-500 via-violet-500 to-purple-600"
                 title="Career Diagnosis Report"
                 subtitle="Gabungkan hasil CV Review, Job Match, Red Flag & Interview jadi satu report PDF premium.">
        <form method="POST" action="{{ route('career-report.generate') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <button type="submit" :disabled="loading"
                    class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 disabled:opacity-60">
                <span x-show="!loading" class="flex items-center gap-2"><x-icon name="spark" class="h-4 w-4"/> Generate Report</span>
                <span x-show="loading" x-cloak>Menyusun report...</span>
            </button>
        </form>
    </x-page-head>

    @unless ($hasCv)
        <div class="cl-rise mb-6 flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
            <x-icon name="bolt" class="h-5 w-5 shrink-0"/>
            <span>Kamu perlu <a href="{{ route('cv.index') }}" class="font-semibold underline">melakukan CV Review</a> dulu sebelum bisa generate report.</span>
        </div>
    @endunless

    @if ($reports->isEmpty())
        <div class="cl-rise rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 text-white"><x-icon name="doc" class="h-8 w-8"/></div>
            <p class="text-sm text-slate-500">Belum ada report. Klik "Generate Report" untuk membuat Career Diagnosis pertamamu.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($reports as $i => $report)
                <div class="cl-rise flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" style="--reveal-delay:{{ $i*40 }}ms">
                    <div class="flex items-center gap-4">
                        <div class="grid h-14 w-14 flex-col place-items-center rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-white">
                            <span class="text-lg font-bold leading-none">{{ $report->overall_score }}</span>
                            <span class="text-[9px] text-slate-400">/100</span>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $report->title }}</p>
                            <p class="text-xs text-slate-400">{{ $report->created_at->diffForHumans() }} · {{ ucfirst($report->report_data['career_readiness_level'] ?? '-') }}</p>
                        </div>
                    </div>
                    @if ($report->pdf_path)
                        <a href="{{ route('career-report.download', $report) }}" class="flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100"><x-icon name="down" class="h-4 w-4"/> Download PDF</a>
                    @else
                        <span class="text-xs text-slate-400">PDF tidak tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-dashboard-layout>
