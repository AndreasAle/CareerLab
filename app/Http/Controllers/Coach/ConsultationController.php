<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $coachId = $request->user()->id;

        // Bookings assigned to this coach, plus unassigned pending ones they can pick up.
        $bookings = ConsultationBooking::with('user')
            ->where(fn ($q) => $q->where('coach_id', $coachId)->orWhereNull('coach_id'))
            ->latest()->paginate(20);

        return view('coach.consultations.index', ['bookings' => $bookings]);
    }

    public function update(Request $request, ConsultationBooking $consultation)
    {
        $coachId = $request->user()->id;
        abort_unless($consultation->coach_id === null || $consultation->coach_id === $coachId, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'meeting_link' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $consultation->update($validated + ['coach_id' => $coachId]);

        return back()->with('success', 'Konsultasi diperbarui.');
    }
}
