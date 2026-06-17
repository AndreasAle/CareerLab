<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedFlagScanRequest;
use App\Models\CvUpload;
use App\Models\RedFlagScan;
use App\Services\AI\RedFlagService;
use Illuminate\Http\Request;

class RedFlagController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('red-flag.index', [
            'cvs' => $user->cvUploads()->latest()->get(),
            'scans' => $user->redFlagScans()->latest()->take(10)->get(),
        ]);
    }

    public function scan(RedFlagScanRequest $request, RedFlagService $service)
    {
        $user = $request->user();

        $cv = null;
        if ($request->filled('cv_upload_id')) {
            $cv = CvUpload::where('id', $request->cv_upload_id)->where('user_id', $user->id)->first();
        }

        $profile = $request->only(['current_status', 'experience', 'work_gap', 'resign_reason', 'career_target']);

        $scan = $service->scan($user, $cv, $request->target_position, $profile);

        return redirect()->route('red-flag.show', $scan)->with('success', 'Scan red flag selesai!');
    }

    public function show(Request $request, RedFlagScan $scan)
    {
        abort_unless($scan->user_id === $request->user()->id, 403);

        return view('red-flag.show', ['scan' => $scan]);
    }
}
