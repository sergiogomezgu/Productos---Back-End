@extends('layouts.hotel')

@section('title', 'Dashboard')
@section('header', 'Panel del Hotel')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Reservas totales</h3>
        <p class="text-3xl font-bold">{{ $totalReservations ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Confirmadas</h3>
        <p class="text-3xl font-bold">{{ $confirmed ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Pendientes</h3>
        <p class="text-3xl font-bold">{{ $pending ?? 0 }}</p>
    </div>

</div>

@endsection
