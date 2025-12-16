<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZonaReservationsController;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// REST WebService - Información agregada de reservas por zona
Route::get('/zonas/reservations', [ZonaReservationsController::class, 'index']);
