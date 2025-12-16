@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Panel de control')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-gray-600 text-sm">Hoteles</h3>
            <p class="text-3xl font-bold">{{ $totalHotels }}</p>
        </div>

        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-gray-600 text-sm">Usuarios de hotel</h3>
            <p class="text-3xl font-bold">{{ $totalHotelUsers }}</p>
        </div>

        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-gray-600 text-sm">Reservas</h3>
            <p class="text-3xl font-bold">{{ $totalReservations }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-yellow-100 p-6 shadow rounded">
            <h3 class="text-gray-700 text-sm">Pendientes</h3>
            <p class="text-3xl font-bold">{{ $pending }}</p>
        </div>

        <div class="bg-green-100 p-6 shadow rounded">
            <h3 class="text-gray-700 text-sm">Confirmadas</h3>
            <p class="text-3xl font-bold">{{ $confirmed }}</p>
        </div>

        <div class="bg-red-100 p-6 shadow rounded">
            <h3 class="text-gray-700 text-sm">Canceladas</h3>
            <p class="text-3xl font-bold">{{ $cancelled }}</p>
        </div>

        <div class="bg-blue-100 p-6 shadow rounded">
            <h3 class="text-gray-700 text-sm">Comisiones este mes</h3>
            <p class="text-2xl font-bold text-blue-800">€{{ number_format($totalComisionesMes, 2) }}</p>
        </div>

    </div>

    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-xl font-semibold mb-4">Últimas reservas</h3>

        <table class="w-full bg-white">
            <thead>
                <tr class="border-b">
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Hotel</th>
                    <th class="p-3 text-left">Usuario</th>
                    <th class="p-3 text-left">Entrada</th>
                    <th class="p-3 text-left">Salida</th>
                    <th class="p-3 text-left">Estado</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($latestReservations as $reservation)
                    <tr class="border-b">
                        <td class="p-3">{{ $reservation->id }}</td>
                        <td class="p-3">{{ $reservation->hotel->name }}</td>
                        <td class="p-3">{{ $reservation->hotelUser?->name ?? '—' }}</td>
                        <td class="p-3">{{ $reservation->check_in }}</td>
                        <td class="p-3">{{ $reservation->check_out }}</td>
                        <td class="p-3 capitalize">{{ $reservation->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<div class="bg-white p-6 shadow rounded mb-8">
    <h3 class="text-xl font-semibold mb-4">Reservas por estado</h3>
    <canvas id="reservasChart"></canvas>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('reservasChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendientes', 'Confirmadas', 'Canceladas'],
            datasets: [{
                label: 'Número de reservas',
                data: [{{ $pending }}, {{ $confirmed }}, {{ $cancelled }}],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush

