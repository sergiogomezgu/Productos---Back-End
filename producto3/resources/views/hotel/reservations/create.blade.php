@extends('layouts.hotel')

@section('title', 'Nueva Reserva')
@section('header', 'Crear Reserva')

@section('content')

<form method="POST" action="{{ route('hotel.reservations.store') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block mb-1 font-semibold">Entrada</label>
        <input type="date" name="check_in" class="border rounded px-3 py-2 w-full">
    </div>

    <div>
        <label class="block mb-1 font-semibold">Salida</label>
        <input type="date" name="check_out" class="border rounded px-3 py-2 w-full">
    </div>

    <div>
        <label class="block mb-1 font-semibold">Huéspedes</label>
        <input type="number" name="guests" class="border rounded px-3 py-2 w-full" min="1" required>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Precio Total (€)</label>
        <input type="number" name="total_price" step="0.01" min="0" class="border rounded px-3 py-2 w-full" required>
        <small class="text-gray-500">El hotel recibirá comisión sobre este monto</small>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Estado</label>
        <select name="status" class="border rounded px-3 py-2 w-full">
            <option value="pendiente">Pendiente</option>
            <option value="confirmada">Confirmada</option>
            <option value="cancelada">Cancelada</option>
        </select>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Crear</button>
</form>

@endsection
