@extends('layouts.admin')

@section('title', 'Nuevo Usuario de Hotel')
@section('header', 'Crear Usuario de Hotel')

@section('content')
    <div class="bg-white shadow rounded p-6 max-w-xl">
        <form action="{{ route('admin.hotel_users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Hotel</label>
                <select name="hotel_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Selecciona un hotel</option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Crear usuario
            </button>
        </form>
    </div>
@endsection
