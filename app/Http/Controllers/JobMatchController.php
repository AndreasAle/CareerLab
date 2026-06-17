<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobMatchRequest;
use App\Models\CvUpload;
use App\Models\JobMatchCheck;
use App\Services\AI\JobMatchService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class JobMatchController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('job-match.index', [
            'cvs' => $user->cvUploads()->latest()->get(),
            'checks' => $user->jobMatchChecks()->latest()->take(10)->get(),
        ]);
    }

    public function check(JobMatchRequest $request, JobMatchService $service, SubscriptionService $subs)
    {
        $user = $request->user();

        if (! $subs->canUse($user, 'job_match')) {
            return redirect()->route('pricing')
                ->with('upgrade', 'Limit Job Match kamu sudah habis. Upgrade untuk cek lowongan tanpa batas.');
        }

        $cv = null;
        if ($request->filled('cv_upload_id')) {
            $cv = CvUpload::where('id', $request->cv_upload_id)->where('user_id', $user->id)->first();
        }

        $check = $service->check($user, $cv, $request->job_title, $request->company_name, $request->job_description);

        return redirect()->route('job-match.show', $check)->with('success', 'Analisis job match selesai!');
    }

    public function show(Request $request, JobMatchCheck $check)
    {
        abort_unless($check->user_id === $request->user()->id, 403);

        return view('job-match.show', ['check' => $check]);
    }
}
