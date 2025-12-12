@extends('layouts.admin')

@section('title', 'Editar Usuario de Hotel')
@section('header', 'Editar Usuario de Hotel')

@section('content')
    <div class="bg-white shadow rounded p-6 max-w-xl">
        <form action="{{ route('admin.hotel_users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Hotel</label>
                <select name="hotel_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $hotel->id == $user->hotel_id ? 'selected' : '' }}>
                            {{ $hotel->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nueva contraseña (opcional)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Actualizar usuario
            </button>
        </form>
    </div>
@endsection
