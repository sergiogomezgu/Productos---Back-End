<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Reservation;
use Illuminate\Http\Request;

class HotelAvailabilityController extends Controller
{
    public function index()
    {
        $hotelId = auth()->user()->hotel_id;

        $days = Availability::where('hotel_id', $hotelId)->get();

        return view('hotel.availability.index', compact('days'));
    }

    public function toggle(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $day = Availability::where('hotel_id', $hotelId)
            ->where('date', $request->date)
            ->first();

        if ($day) {
            $day->update(['available' => !$day->available]);
        } else {
            Availability::create([
                'hotel_id' => $hotelId,
                'date' => $request->date,
                'available' => false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    // ✅ API para FullCalendar (admin y hotel)
    public function api()
    {
        $hotelId = auth()->user()->hotel_id;

        $reservations = Reservation::where('hotel_id', $hotelId)->get();
        $availability = Availability::where('hotel_id', $hotelId)->get();

        $events = [];

        foreach ($reservations as $r) {
            $events[] = [
                'title' => 'Reserva: ' . ucfirst($r->status),
                'start' => $r->check_in,
                'end' => date('Y-m-d', strtotime($r->check_out . ' +1 day')),
                'color' => match ($r->status) {
                    'pendiente' => '#facc15',
                    'confirmada' => '#4ade80',
                    'cancelada' => '#f87171',
                },
            ];
        }

        foreach ($availability as $a) {
            if (!$a->available) {
                $events[] = [
                    'title' => 'Bloqueado',
                    'start' => $a->date,
                    'color' => '#9ca3af',
                ];
            }
        }

        return response()->json($events);
    }
}
