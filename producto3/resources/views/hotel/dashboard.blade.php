@extends('layouts.hotel')

@section('title', 'Dashboard')
@section('header', 'Panel del Hotel')

@section('content')

<!-- Información del hotel -->
<div class="bg-white p-6 shadow rounded mb-6">
    <h3 class="text-xl font-bold mb-2">{{ $hotel->name }}</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="text-gray-600">Zona:</span>
            <span class="font-semibold">{{ $hotel->zona ?? 'No asignada' }}</span>
        </div>
        <div>
            <span class="text-gray-600">Comisión pactada:</span>
            <span class="font-semibold text-green-600">{{ number_format($hotel->comision_porcentaje ?? 0, 2) }}%</span>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Reservas totales</h3>
        <p class="text-3xl font-bold">{{ $totalReservas }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Confirmadas</h3>
        <p class="text-3xl font-bold text-green-600">{{ $reservasConfirmadas }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Pendientes</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $reservasPendientes }}</p>
    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-gray-600 text-sm">Comisión este mes</h3>
        <p class="text-3xl font-bold text-green-600">€{{ number_format($comisionEsteMes, 2) }}</p>
    </div>

</div>

<!-- Últimas reservas -->
<div class="bg-white p-6 shadow rounded">
    <h3 class="text-lg font-bold mb-4">Últimas reservas</h3>
    
    @if($ultimasReservas->isEmpty())
        <p class="text-gray-500">No hay reservas aún</p>
    @else
        <table class="w-full">
            <thead class="border-b">
                <tr>
                    <th class="text-left p-2">Fecha check-in</th>
                    <th class="text-left p-2">Fecha check-out</th>
                    <th class="text-left p-2">Huéspedes</th>
                    <th class="text-left p-2">Total</th>
                    <th class="text-left p-2">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimasReservas as $reserva)
                    <tr class="border-b">
                        <td class="p-2">{{ $reserva->check_in }}</td>
                        <td class="p-2">{{ $reserva->check_out }}</td>
                        <td class="p-2">{{ $reserva->guests }}</td>
                        <td class="p-2">€{{ number_format($reserva->total_price, 2) }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-xs
                                {{ $reserva->status == 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $reserva->status == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $reserva->status == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst($reserva->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
