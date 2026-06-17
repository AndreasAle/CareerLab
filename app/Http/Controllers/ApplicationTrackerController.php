<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\ApplicationTracker;
use Illuminate\Http\Request;

class ApplicationTrackerController extends Controller
{
    public function index(Request $request)
    {
        $apps = $request->user()->applicationTrackers()->latest()->get();

        $total = $apps->count();
        $interview = $apps->whereIn('status', ['interview', 'test'])->count();
        $offering = $apps->whereIn('status', ['offering', 'accepted'])->count();
        $rejected = $apps->whereIn('status', ['rejected', 'ghosted'])->count();

        $stats = [
            'total' => $total,
            'interview' => $interview,
            'offering' => $offering,
            'rejected' => $rejected,
            'conversion' => $total > 0 ? round($offering / $total * 100) : 0,
        ];

        return view('applications.index', [
            'apps' => $apps,
            'stats' => $stats,
            'statuses' => ApplicationTracker::STATUSES,
        ]);
    }

    public function store(ApplicationRequest $request)
    {
        $request->user()->applicationTrackers()->create($request->validated());

        return redirect()->route('applications.index')->with('success', 'Lamaran ditambahkan.');
    }

    public function update(ApplicationRequest $request, ApplicationTracker $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $application->update($request->validated());

        return redirect()->route('applications.index')->with('success', 'Lamaran diperbarui.');
    }

    public function updateStatus(Request $request, ApplicationTracker $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::in(ApplicationTracker::STATUSES)],
        ]);

        $application->update($validated);

        return back()->with('success', 'Status diperbarui.');
    }

    public function destroy(Request $request, ApplicationTracker $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $application->delete();

        return redirect()->route('applications.index')->with('success', 'Lamaran dihapus.');
    }
}
