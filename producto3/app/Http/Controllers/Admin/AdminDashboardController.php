<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\Reservation;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Datos principales
        $totalHotels = Hotel::count();
        $totalHotelUsers = HotelUser::count();
        $totalReservations = Reservation::count();

        // Reservas por estado
        $pending = Reservation::where('status', 'pendiente')->count();
        $confirmed = Reservation::where('status', 'confirmada')->count();
        $cancelled = Reservation::where('status', 'cancelada')->count();

        // Comisiones del mes actual
        $reservasConfirmadasMes = Reservation::where('status', 'confirmada')
            ->whereYear('check_in', date('Y'))
            ->whereMonth('check_in', date('m'))
            ->with('hotel')
            ->get();
            
        $totalComisionesMes = 0;
        foreach ($reservasConfirmadasMes as $reserva) {
            if ($reserva->hotel) {
                $totalComisionesMes += ($reserva->total_price * $reserva->hotel->comision_porcentaje) / 100;
            }
        }

        // Últimas reservas
        $latestReservations = Reservation::with(['hotel', 'hotelUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalHotels',
            'totalHotelUsers',
            'totalReservations',
            'pending',
            'confirmed',
            'cancelled',
            'totalComisionesMes',
            'latestReservations'
        ));
    }
}
