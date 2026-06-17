<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalarySimulationRequest;
use App\Models\SalarySimulation;
use App\Services\AI\SalaryNegotiationService;
use Illuminate\Http\Request;

class SalarySimulatorController extends Controller
{
    public function index(Request $request)
    {
        return view('salary.index', [
            'scenarios' => SalaryNegotiationService::SCENARIOS,
            'simulations' => $request->user()->salarySimulations()->latest()->take(10)->get(),
        ]);
    }

    public function start(SalarySimulationRequest $request, SalaryNegotiationService $service)
    {
        $sim = $service->simulate($request->user(), $request->validated());

        return redirect()->route('salary.show', $sim)->with('success', 'Evaluasi negosiasi selesai!');
    }

    public function show(Request $request, SalarySimulation $simulation)
    {
        abort_unless($simulation->user_id === $request->user()->id, 403);

        return view('salary.show', [
            'sim' => $simulation,
            'scenarios' => SalaryNegotiationService::SCENARIOS,
        ]);
    }
}
