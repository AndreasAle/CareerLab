<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationBookingRequest;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        return view('consultation.index', [
            'bookings' => $request->user()->consultationBookings()->with('coach')->latest()->get(),
        ]);
    }

    public function book(ConsultationBookingRequest $request)
    {
        $request->user()->consultationBookings()->create([
            'topic' => $request->topic,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes ?? 60,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('consultation.index')
            ->with('success', 'Booking konsultasi terkirim! Coach akan mengonfirmasi jadwalnya.');
    }
}
