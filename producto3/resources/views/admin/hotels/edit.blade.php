@extends('layouts.admin')

@section('title', 'Editar Hotel')
@section('header', 'Editar hotel')

@section('content')
    <div class="bg-white shadow rounded p-6 max-w-xl">
        <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="name" value="{{ $hotel->name }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Ciudad</label>
                <input type="text" name="city" value="{{ $hotel->city }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Dirección</label>
                <input type="text" name="address" value="{{ $hotel->address }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ $hotel->phone }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ $hotel->email }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Descripción</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4">{{ $hotel->description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Zona</label>
                <select name="zona" class="w-full border rounded px-3 py-2">
                    <option value="">Seleccionar zona</option>
                    <option value="Norte" {{ $hotel->zona == 'Norte' ? 'selected' : '' }}>Norte</option>
                    <option value="Sur" {{ $hotel->zona == 'Sur' ? 'selected' : '' }}>Sur</option>
                    <option value="Este" {{ $hotel->zona == 'Este' ? 'selected' : '' }}>Este</option>
                    <option value="Oeste" {{ $hotel->zona == 'Oeste' ? 'selected' : '' }}>Oeste</option>
                    <option value="Centro" {{ $hotel->zona == 'Centro' ? 'selected' : '' }}>Centro</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Comisión (%)</label>
                <input type="number" name="comision_porcentaje" step="0.01" min="0" max="100" value="{{ $hotel->comision_porcentaje ?? 0 }}" class="w-full border rounded px-3 py-2">
                <small class="text-gray-500">Porcentaje de comisión que recibirá el hotel por cada reserva</small>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Actualizar hotel
            </button>
        </form>
    </div>
@endsection
