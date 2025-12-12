@extends('layouts.admin')

@section('title', 'Usuarios de Hotel')
@section('header', 'Listado de Usuarios de Hotel')

@section('content')
    <a href="{{ route('admin.hotel_users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
        Nuevo usuario de hotel
    </a>

    <table class="w-full mt-6 bg-white shadow rounded">
        <thead>
            <tr class="border-b">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Hotel</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="p-3">{{ $user->id }}</td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3">{{ $user->hotel->name }}</td>

                    <td class="p-3 flex gap-3">
                        <a href="{{ route('admin.hotel_users.edit', $user) }}" class="text-blue-600">Editar</a>

                        <form action="{{ route('admin.hotel_users.destroy', $user) }}"
                              method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
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
