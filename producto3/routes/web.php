<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminHotelUserController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Hotel\HotelDashboardController;
use App\Http\Controllers\Hotel\HotelReservationController;
use App\Http\Controllers\Hotel\HotelReservationCalendarController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard por defecto de Laravel
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ PANEL ADMIN
Route::middleware(['auth', 'isAdmin'])->group(function () {

    // Calendario de reservas (debe ir antes del resource)
    Route::get('/admin/reservations/calendar', [AdminReservationController::class, 'calendar'])
        ->name('admin.reservations.calendar');

    // Dashboard admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Hoteles
    Route::resource('/admin/hotels', AdminHotelController::class)
        ->names('admin.hotels');

    // Usuarios hotel
    Route::resource('/admin/hotel-users', AdminHotelUserController::class)
        ->names('admin.hotel_users');

    // Reservas
    Route::resource('/admin/reservations', AdminReservationController::class)
        ->names('admin.reservations');
});

// ✅ PANEL HOTEL
Route::middleware(['auth', 'isHotel'])->group(function () {

    // Dashboard del hotel
    Route::get('/hotel/dashboard', [HotelDashboardController::class, 'index'])
        ->name('hotel.dashboard');

    // Calendario del hotel
    Route::get('/hotel/reservations/calendar', [HotelReservationCalendarController::class, 'index'])
        ->name('hotel.reservations.calendar');

    // Reservas del hotel
    Route::resource('/hotel/reservations', HotelReservationController::class)
        ->names('hotel.reservations');

        // Disponibilidad del hotel
Route::get('/hotel/availability', [HotelAvailabilityController::class, 'index'])
    ->name('hotel.availability');

Route::post('/hotel/availability/toggle', [HotelAvailabilityController::class, 'toggle'])
    ->name('hotel.availability.toggle');

});

require __DIR__.'/auth.php';
