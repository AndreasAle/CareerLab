<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectionAutopsyRequest;
use App\Models\RejectionAutopsy;
use App\Services\AI\RejectionAutopsyService;
use Illuminate\Http\Request;

class RejectionAutopsyController extends Controller
{
    public function index(Request $request)
    {
        return view('rejection.index', [
            'types' => RejectionAutopsyService::TYPES,
            'autopsies' => $request->user()->rejectionAutopsies()->latest()->take(10)->get(),
        ]);
    }

    public function analyze(RejectionAutopsyRequest $request, RejectionAutopsyService $service)
    {
        $autopsy = $service->analyze(
            $request->user(),
            $request->rejection_type,
            $request->story,
            $request->target_position,
        );

        return redirect()->route('rejection.show', $autopsy)->with('success', 'Analisis selesai!');
    }

    public function show(Request $request, RejectionAutopsy $autopsy)
    {
        abort_unless($autopsy->user_id === $request->user()->id, 403);

        return view('rejection.show', [
            'autopsy' => $autopsy,
            'types' => RejectionAutopsyService::TYPES,
        ]);
    }
}
