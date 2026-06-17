<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use Illuminate\Http\Request;

class CoachDashboardController extends Controller
{
    public function index(Request $request)
    {
        $coachId = $request->user()->id;

        $stats = [
            'total_booking' => ConsultationBooking::where('coach_id', $coachId)->count(),
            'upcoming' => ConsultationBooking::where('coach_id', $coachId)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('scheduled_at', '>=', now())->count(),
            'completed' => ConsultationBooking::where('coach_id', $coachId)->where('status', 'completed')->count(),
        ];

        $upcomingBookings = ConsultationBooking::with('user')
            ->where('coach_id', $coachId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('scheduled_at')
            ->take(10)->get();

        return view('coach.dashboard', compact('stats', 'upcomingBookings'));
    }
}
