<?php

namespace App\Http\Controllers;

use App\Http\Requests\First90DaysRequest;
use App\Services\AI\First90DaysService;

class First90DaysController extends Controller
{
    public function index()
    {
        return view('first-90-days.index', ['result' => null]);
    }

    public function generate(First90DaysRequest $request, First90DaysService $service)
    {
        $result = $service->generate(
            $request->user(),
            $request->position,
            $request->industry,
            $request->experience_level,
            $request->main_concern,
        );

        return view('first-90-days.index', [
            'result' => $result,
            'old' => $request->validated(),
        ]);
    }
}
