<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class HotelCommissionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $comisionesMensuales = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'confirmada')
            ->select(
                DB::raw('YEAR(check_in) as year'),
                DB::raw('MONTH(check_in) as month'),
                DB::raw('COUNT(*) as total_reservas'),
                DB::raw('SUM(total_price) as total_ventas')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $hotel = \App\Models\Hotel::find($hotelId);
        $comisionPorcentaje = $hotel->comision_porcentaje ?? 0;

        $comisionesMensuales = $comisionesMensuales->map(function ($item) use ($comisionPorcentaje) {
            $item->comision = ($item->total_ventas * $comisionPorcentaje) / 100;
            return $item;
        });

        return view('hotel.commissions.index', compact('comisionesMensuales', 'comisionPorcentaje'));
    }
}
