<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        return view('admin.consultations.index', [
            'bookings' => ConsultationBooking::with(['user', 'coach'])->latest()->paginate(20),
            'coaches' => User::where('role', 'coach')->get(),
        ]);
    }

    public function assign(Request $request, ConsultationBooking $consultation)
    {
        $validated = $request->validate([
            'coach_id' => ['required', 'exists:users,id'],
        ]);

        $consultation->update(['coach_id' => $validated['coach_id'], 'status' => 'confirmed']);

        return back()->with('success', 'Coach ditugaskan ke konsultasi.');
    }
}
