@extends('layouts.hotel')

@section('title', 'Editar Reserva')
@section('header', 'Editar Reserva')

@section('content')

<form method="POST" action="{{ route('hotel.reservations.update', $reservation) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1 font-semibold">Entrada</label>
        <input type="date" name="check_in" value="{{ $reservation->check_in }}" class="border rounded px-3 py-2 w-full">
    </div>

    <div>
        <label class="block mb-1 font-semibold">Salida</label>
        <input type="date" name="check_out" value="{{ $reservation->check_out }}" class="border rounded px-3 py-2 w-full">
    </div>

    <div>
        <label class="block mb-1 font-semibold">Huéspedes</label>
        <input type="number" name="guests" value="{{ $reservation->guests }}" class="border rounded px-3 py-2 w-full">
    </div>

    <div>
        <label class="block mb-1 font-semibold">Estado</label>
        <select name="status" class="border rounded px-3 py-2 w-full">
            <option value="pendiente" {{ $reservation->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmada" {{ $reservation->status == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
            <option value="cancelada" {{ $reservation->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
</form>

@endsection
