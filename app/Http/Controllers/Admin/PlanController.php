<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index', ['plans' => Plan::orderBy('price')->get()]);
    }

    public function create()
    {
        return view('admin.plans.form', ['plan' => new Plan(['cv_review_limit' => 0, 'interview_limit' => 0, 'job_match_limit' => 0, 'report_limit' => 0, 'duration_days' => 30])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        $data['features'] = $this->parseFeatures($request->input('features_text'));
        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan dibuat.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.form', ['plan' => $plan]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validateData($request);
        $data['features'] = $this->parseFeatures($request->input('features_text'));
        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan diperbarui.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'cv_review_limit' => ['required', 'integer', 'min:-1'],
            'interview_limit' => ['required', 'integer', 'min:-1'],
            'job_match_limit' => ['required', 'integer', 'min:-1'],
            'report_limit' => ['required', 'integer', 'min:-1'],
            'has_manual_review' => ['nullable', 'boolean'],
            'has_consultation' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'has_manual_review' => $request->boolean('has_manual_review'),
            'has_consultation' => $request->boolean('has_consultation'),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function parseFeatures(?string $text): array
    {
        return collect(preg_split('/\r?\n/', (string) $text))
            ->map(fn ($l) => trim($l))->filter()->values()->all();
    }
}
