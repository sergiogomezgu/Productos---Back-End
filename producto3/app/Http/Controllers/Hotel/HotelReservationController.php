<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Availability;

class HotelReservationController extends Controller
{
    public function index()
    {
        $hotelUser = auth()->user();

        // Reservas SOLO del hotel del usuario
        $reservations = Reservation::where('hotel_id', $hotelUser->hotel_id)
            ->with(['hotel', 'hotelUser'])
            ->get();

        return view('hotel.reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('hotel.reservations.create');
    }

    public function store(Request $request)
    {
        $hotelUser = auth()->user();

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'status' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        // ✅ Comprobar días bloqueados
        $blocked = Availability::where('hotel_id', $hotelUser->hotel_id)
            ->where('available', false)
            ->whereBetween('date', [$request->check_in, $request->check_out])
            ->exists();

        if ($blocked) {
            return back()->withErrors([
                'error' => 'No se puede crear la reserva porque hay días bloqueados en ese rango.'
            ]);
        }

        // ✅ Crear reserva
        Reservation::create([
            'hotel_id' => $hotelUser->hotel_id,
            'hotel_user_id' => $hotelUser->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'status' => $request->status,
            'total_price' => 0,
        ]);

        return redirect()->route('hotel.reservations.index')
            ->with('success', 'Reserva creada correctamente');
    }

    public function edit(Reservation $reservation)
    {
        if ($reservation->hotel_id !== auth()->user()->hotel_id) {
            abort(403);
        }

        return view('hotel.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->hotel_id !== auth()->user()->hotel_id) {
            abort(403);
        }

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'status' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        // ✅ Comprobar días bloqueados
        $blocked = Availability::where('hotel_id', $reservation->hotel_id)
            ->where('available', false)
            ->whereBetween('date', [$request->check_in, $request->check_out])
            ->exists();

        if ($blocked) {
            return back()->withErrors([
                'error' => 'No se puede actualizar la reserva porque hay días bloqueados en ese rango.'
            ]);
        }

        $reservation->update($request->all());

        return redirect()->route('hotel.reservations.index')
            ->with('success', 'Reserva actualizada correctamente');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->hotel_id !== auth()->user()->hotel_id) {
            abort(403);
        }

        $reservation->delete();

        return redirect()->route('hotel.reservations.index')
            ->with('success', 'Reserva eliminada correctamente');
    }
}
