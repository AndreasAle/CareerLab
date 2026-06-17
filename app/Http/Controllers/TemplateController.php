<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request, SubscriptionService $subs)
    {
        $templates = Template::where('is_active', true)->orderBy('type')->get()->groupBy('type');
        $plan = $subs->planFor($request->user());

        return view('templates.index', [
            'grouped' => $templates,
            'typeLabels' => Template::TYPES,
            'canPremium' => ! $plan->isFree(),
            'planName' => $plan->name,
        ]);
    }
}
