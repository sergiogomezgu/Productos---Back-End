@extends('layouts.admin')

@section('title', 'Reservas')
@section('header', 'Listado de Reservas')

@section('content')
    <a href="{{ route('admin.reservations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
        Nueva reserva
    </a>
<form method="GET" action="{{ route('admin.reservations.index') }}" class="mb-6 flex gap-4 items-end">

    <div>
        <label class="block text-sm font-semibold mb-1">Hotel</label>
        <select name="hotel_id" class="border rounded px-3 py-2">
            <option value="">Todos</option>
            @foreach ($hotels as $hotel)
                <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                    {{ $hotel->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Estado</label>
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Todos</option>
            <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmada" {{ request('status') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
            <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Desde</label>
        <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Hasta</label>
        <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
    <a href="{{ route('admin.reservations.index') }}" class="bg-gray-300 px-4 py-2 rounded">Limpiar</a>
</form>

    <table class="w-full mt-6 bg-white shadow rounded">
        <thead>
            <tr class="border-b">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Hotel</th>
                <th class="p-3 text-left">Usuario</th>
                <th class="p-3 text-left">Entrada</th>
                <th class="p-3 text-left">Salida</th>
                <th class="p-3 text-left">Huéspedes</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reservations as $reservation)
                <tr class="border-b">
                    <td class="p-3">{{ $reservation->id }}</td>
                    <td class="p-3">{{ $reservation->hotel->name }}</td>
                    <td class="p-3">
                        {{ $reservation->hotelUser?->name ?? '—' }}
                    </td>
                    <td class="p-3">{{ $reservation->check_in }}</td>
                    <td class="p-3">{{ $reservation->check_out }}</td>
                    <td class="p-3">{{ $reservation->guests }}</td>
                    <td class="p-3 capitalize">{{ $reservation->status }}</td>

                    <td class="p-3 flex gap-3">
                        <a href="{{ route('admin.reservations.edit', $reservation) }}" class="text-blue-600">
                            Editar
                        </a>

                        <form action="{{ route('admin.reservations.destroy', $reservation) }}"
                              method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta reserva?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
