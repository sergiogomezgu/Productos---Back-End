<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Hotel;

class HotelDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;
        
        $hotel = Hotel::find($hotelId);
        
        $totalReservas = Reservation::where('hotel_id', $hotelId)->count();
        $reservasPendientes = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'pendiente')
            ->count();
        $reservasConfirmadas = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'confirmada')
            ->count();
            
        // Comisiones del mes actual
        $comisionMesActual = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'confirmada')
            ->whereYear('check_in', date('Y'))
            ->whereMonth('check_in', date('m'))
            ->sum('total_price');
            
        $comisionEsteMes = ($comisionMesActual * ($hotel->comision_porcentaje ?? 0)) / 100;
        
        // Últimas reservas
        $ultimasReservas = Reservation::where('hotel_id', $hotelId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('hotel.dashboard', compact(
            'hotel',
            'totalReservas',
            'reservasPendientes',
            'reservasConfirmadas',
            'comisionEsteMes',
            'ultimasReservas'
        ));
    }
}
