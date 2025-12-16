@extends('layouts.admin')

@section('title', 'Editar Reserva')
@section('header', 'Editar Reserva')

@section('content')
    <div class="bg-white shadow rounded p-6 max-w-xl">
        <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Hotel</label>
                <select name="hotel_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $hotel->id == $reservation->hotel_id ? 'selected' : '' }}>
                            {{ $hotel->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Usuario del hotel (opcional)</label>
                <select name="hotel_user_id" class="w-full border rounded px-3 py-2">
                    <option value="">— Ninguno —</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $reservation->hotel_user_id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->hotel->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Fecha de entrada</label>
                <input type="date" name="check_in" value="{{ $reservation->check_in }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Fecha de salida</label>
                <input type="date" name="check_out" value="{{ $reservation->check_out }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Huéspedes</label>
                <input type="number" name="guests" value="{{ $reservation->guests }}" class="w-full border rounded px-3 py-2" min="1" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Precio total (€)</label>
                <input type="number" step="0.01" name="total_price" value="{{ $reservation->total_price }}" class="w-full border rounded px-3 py-2" min="0" required>
                <small class="text-gray-500">Base para calcular comisiones del hotel</small>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Estado</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="pendiente" {{ $reservation->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ $reservation->status == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada" {{ $reservation->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Actualizar reserva
            </button>
        </form>
    </div>
@endsection
