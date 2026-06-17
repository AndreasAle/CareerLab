<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialAuditRequest;
use App\Services\AI\SocialAuditService;
use Illuminate\Http\Request;

class SocialAuditController extends Controller
{
    public function index()
    {
        return view('social-audit.index', ['result' => null]);
    }

    public function check(SocialAuditRequest $request, SocialAuditService $service)
    {
        $result = $service->audit($request->user(), $request->validated());

        return view('social-audit.index', [
            'result' => $result,
            'old' => $request->validated(),
        ]);
    }
}
