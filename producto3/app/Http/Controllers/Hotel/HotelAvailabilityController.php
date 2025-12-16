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

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'vehiculo_tipo' => 'required|in:Turismo,Bus',
            'num_vehiculos' => 'required|integer|min:1',
        ]);

        Availability::create([
            'hotel_id' => auth()->user()->hotel_id,
            'fecha' => $request->fecha,
            'vehiculo_tipo' => $request->vehiculo_tipo,
            'num_vehiculos' => $request->num_vehiculos,
        ]);

        return redirect()->route('hotel.availability')->with('success', 'Disponibilidad agregada correctamente');
    }

    public function destroy(Availability $availability)
    {
        if ($availability->hotel_id != auth()->user()->hotel_id) {
            abort(403);
        }

        $availability->delete();

        return redirect()->route('hotel.availability')->with('success', 'Disponibilidad eliminada correctamente');
    }

    // API para FullCalendar (admin y hotel)
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
