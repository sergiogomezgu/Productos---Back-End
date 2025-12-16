<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ZonaReservationsController extends Controller
{
    public function index()
    {
        $totalReservas = Reservation::where('status', 'confirmada')->count();

        if ($totalReservas == 0) {
            return response()->json([
                'total_traslados' => 0,
                'zonas' => []
            ]);
        }

        $reservasPorZona = Hotel::select('zona')
            ->selectRaw('COUNT(reservations.id) as num_traslados')
            ->join('reservations', 'hotels.id', '=', 'reservations.hotel_id')
            ->where('reservations.status', 'confirmada')
            ->whereNotNull('zona')
            ->groupBy('zona')
            ->get();

        $resultado = $reservasPorZona->map(function ($item) use ($totalReservas) {
            return [
                'zona' => $item->zona,
                'num_traslados' => $item->num_traslados,
                'porcentaje' => round(($item->num_traslados / $totalReservas) * 100, 2)
            ];
        });

        return response()->json([
            'total_traslados' => $totalReservas,
            'zonas' => $resultado
        ]);
    }
}
