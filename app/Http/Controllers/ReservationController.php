<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()->latest('reservation_time')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'reservation_time' => ['required', 'date', 'after:now'],
            'special_request' => ['nullable', 'string'],
        ]);

        Reservation::create([
            'user_id' => auth()->id(),
            'party_size' => $validated['party_size'],
            'reservation_time' => $validated['reservation_time'],
            'status' => 'pending',
            'special_request' => $validated['special_request'] ?? null,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation request submitted successfully.');
    }
}
