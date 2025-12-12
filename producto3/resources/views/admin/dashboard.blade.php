@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Panel de control')

@section('content')

{{-- Tarjetas resumen --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg rounded-xl p-6">
        <h3 class="text-sm font-medium opacity-90">Hoteles</h3>
        <p class="text-4xl font-bold mt-2">{{ $totalHotels }}</p>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg rounded-xl p-6">
        <h3 class="text-sm font-medium opacity-90">Usuarios de hotel</h3>
        <p class="text-4xl font-bold mt-2">{{ $totalHotelUsers }}</p>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg rounded-xl p-6">
        <h3 class="text-sm font-medium opacity-90">Reservas</h3>
        <p class="text-4xl font-bold mt-2">{{ $totalReservations }}</p>
    </div>

</div>

{{-- Estado de reservas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-yellow-100 text-yellow-800 p-6 rounded-lg shadow border">
        <h3 class="text-sm font-semibold">Pendientes</h3>
        <p class="text-3xl font-bold mt-2">{{ $pending }}</p>
    </div>

    <div class="bg-green-100 text-green-800 p-6 rounded-lg shadow border">
        <h3 class="text-sm font-semibold">Confirmadas</h3>
        <p class="text-3xl font-bold mt-2">{{ $confirmed }}</p>
    </div>

    <div class="bg-red-100 text-red-800 p-6 rounded-lg shadow border">
        <h3 class="text-sm font-semibold">Canceladas</h3>
        <p class="text-3xl font-bold mt-2">{{ $cancelled }}</p>
    </div>

</div>

{{-- Últimas reservas --}}
<div class="bg-white p-6 rounded-lg shadow border mb-10">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas reservas</h3>

    <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Hotel</th>
                <th class="px-4 py-2">Usuario</th>
                <th class="px-4 py-2">Entrada</th>
                <th class="px-4 py-2">Salida</th>
                <th class="px-4 py-2">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($latestReservations as $r)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $r->id }}</td>
                    <td class="px-4 py-2">{{ $r->hotel->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $r->hotelUser->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $r->check_in }}</td>
                    <td class="px-4 py-2">{{ $r->check_out }}</td>
                    <td class="px-4 py-2 capitalize">
                        <span class="
                            px-2 py-1 rounded text-xs font-medium
                            @if($r->status == 'pendiente') bg-yellow-200 text-yellow-800
                            @elseif($r->status == 'confirmada') bg-green-200 text-green-800
                            @else bg-red-200 text-red-800 @endif
                        ">
                            {{ $r->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Gráfica opcional --}}
<div class="bg-white p-6 rounded-lg shadow border">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservas por estado</h3>
    <canvas id="reservationsChart" height="120"></canvas>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('reservationsChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Confirmadas', 'Canceladas'],
            datasets: [{
                data: [{{ $pending }}, {{ $confirmed }}, {{ $cancelled }}],
                backgroundColor: ['#facc15', '#4ade80', '#f87171'],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14 },
                        padding: 20
                    }
                }
            }
        }
    });
});
</script>
@endpush
