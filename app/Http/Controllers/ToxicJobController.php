<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToxicJobScanRequest;
use App\Models\ToxicJobScan;
use App\Services\AI\ToxicJobService;
use Illuminate\Http\Request;

class ToxicJobController extends Controller
{
    public function index(Request $request)
    {
        return view('toxic-job.index', [
            'scans' => $request->user()->toxicJobScans()->latest()->take(10)->get(),
        ]);
    }

    public function scan(ToxicJobScanRequest $request, ToxicJobService $service)
    {
        $scan = $service->scan(
            $request->user(),
            $request->job_title,
            $request->company_name,
            $request->job_description,
        );

        return redirect()->route('toxic-job.show', $scan)->with('success', 'Analisis toxic workplace selesai!');
    }

    public function show(Request $request, ToxicJobScan $scan)
    {
        abort_unless($scan->user_id === $request->user()->id, 403);

        return view('toxic-job.show', ['scan' => $scan]);
    }
}
