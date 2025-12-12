<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\HotelUser;

class AdminReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['hotel', 'hotelUser']);

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('check_in', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('check_out', '<=', $request->to);
        }

        $reservations = $query->get();
        $hotels = Hotel::all();

        return view('admin.reservations.index', compact('reservations', 'hotels'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        $users = HotelUser::all();

        return view('admin.reservations.create', compact('hotels', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'hotel_user_id' => 'nullable|exists:hotel_users,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        Reservation::create($request->all());

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reserva creada correctamente');
    }

    public function edit(Reservation $reservation)
    {
        $hotels = Hotel::all();
        $users = HotelUser::all();

        return view('admin.reservations.edit', compact('reservation', 'hotels', 'users'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'hotel_user_id' => 'nullable|exists:hotel_users,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $reservation->update($request->all());

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reserva actualizada correctamente');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reserva eliminada correctamente');
    }

    public function calendar()
    {
        $reservations = Reservation::with('hotel')->get();

        $events = $reservations->map(function ($r) {
            return [
                'title' => $r->hotel->name . ' - ' . ucfirst($r->status),
                'start' => $r->check_in,
                'end' => date('Y-m-d', strtotime($r->check_out . ' +1 day')),
                'color' => match ($r->status) {
                    'pendiente' => '#facc15',
                    'confirmada' => '#4ade80',
                    'cancelada' => '#f87171',
                },
            ];
        });

        return view('admin.reservations.calendar', [
            'events' => $events
        ]);
    }
}
