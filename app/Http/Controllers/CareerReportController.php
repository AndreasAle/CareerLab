<?php

namespace App\Http\Controllers;

use App\Models\CareerReport;
use App\Services\AI\CareerReportService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerReportController extends Controller
{
    public function index(Request $request)
    {
        return view('career-report.index', [
            'reports' => $request->user()->careerReports()->latest()->get(),
            'hasCv' => $request->user()->cvReviews()->exists(),
        ]);
    }

    public function generate(Request $request, CareerReportService $service, SubscriptionService $subs)
    {
        $user = $request->user();

        if (! $subs->canUse($user, 'report')) {
            return redirect()->route('pricing')
                ->with('upgrade', 'Career Report PDF tersedia di paket berbayar. Upgrade untuk generate report premium kamu.');
        }

        if (! $user->cvReviews()->exists()) {
            return back()->with('warning', 'Lakukan minimal 1 CV Review dulu sebelum generate report.');
        }

        $report = $service->generate($user);

        return redirect()->route('career-report.index')
            ->with('success', 'Career Diagnosis Report kamu siap! Skor: ' . $report->overall_score . '/100.');
    }

    public function download(Request $request, CareerReport $report)
    {
        abort_unless($report->user_id === $request->user()->id, 403);
        abort_unless($report->pdf_path && Storage::disk('local')->exists($report->pdf_path), 404);

        return Storage::disk('local')->download(
            $report->pdf_path,
            'CareerLab-Report-' . $report->id . '.pdf'
        );
    }
}
