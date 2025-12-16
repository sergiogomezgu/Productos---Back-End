<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class AdminCommissionController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $hoteles = Hotel::with(['reservations' => function($query) use ($year, $month) {
            $query->where('status', 'confirmada')
                  ->whereYear('check_in', $year)
                  ->whereMonth('check_in', $month);
        }])->get();

        $comisionesPorHotel = $hoteles->map(function ($hotel) {
            $totalVentas = $hotel->reservations->sum('total_price');
            $totalReservas = $hotel->reservations->count();
            $comision = ($totalVentas * $hotel->comision_porcentaje) / 100;

            return [
                'hotel_id' => $hotel->id,
                'hotel_nombre' => $hotel->name,
                'zona' => $hotel->zona,
                'total_reservas' => $totalReservas,
                'total_ventas' => $totalVentas,
                'comision_porcentaje' => $hotel->comision_porcentaje,
                'comision_total' => $comision
            ];
        });

        $years = Reservation::selectRaw('DISTINCT YEAR(check_in) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.commissions.index', compact('comisionesPorHotel', 'year', 'month', 'years'));
    }
}
