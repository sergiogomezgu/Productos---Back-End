@extends('layouts.admin')

@section('title', 'Nuevo Hotel')
@section('header', 'Crear nuevo hotel')

@section('content')
    <div class="bg-white shadow rounded p-6 max-w-xl">
        <form action="{{ route('admin.hotels.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Ciudad</label>
                <input type="text" name="city" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Dirección</label>
                <input type="text" name="address" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Teléfono</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Descripción</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Zona</label>
                <select name="zona" class="w-full border rounded px-3 py-2">
                    <option value="">Seleccionar zona</option>
                    <option value="Norte">Norte</option>
                    <option value="Sur">Sur</option>
                    <option value="Este">Este</option>
                    <option value="Oeste">Oeste</option>
                    <option value="Centro">Centro</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Comisión (%)</label>
                <input type="number" name="comision_porcentaje" step="0.01" min="0" max="100" class="w-full border rounded px-3 py-2" value="0">
                <small class="text-gray-500">Porcentaje de comisión que recibirá el hotel por cada reserva</small>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Guardar hotel
            </button>
        </form>
    </div>
@endsection
