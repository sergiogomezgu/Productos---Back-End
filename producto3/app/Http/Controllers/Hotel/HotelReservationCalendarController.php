<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class HotelReservationCalendarController extends Controller
{
    public function index()
    {
        $hotelUser = auth()->user();

        // Reservas solo del hotel del usuario
        $reservations = Reservation::where('hotel_id', $hotelUser->hotel_id)
            ->with('hotel')
            ->get();

        // Convertir a eventos para FullCalendar
        $events = $reservations->map(function ($r) {
            return [
                'title' => ucfirst($r->status),
                'start' => $r->check_in,
                'end' => date('Y-m-d', strtotime($r->check_out . ' +1 day')),
                'color' => match ($r->status) {
                    'pendiente' => '#facc15',
                    'confirmada' => '#4ade80',
                    'cancelada' => '#f87171',
                },
            ];
        });

        return view('hotel.reservations.calendar', [
            'events' => $events
        ]);
    }
}
