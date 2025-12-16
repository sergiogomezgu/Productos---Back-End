@extends('layouts.admin')

@section('title', 'Hoteles')
@section('header', 'Listado de Hoteles')

@section('content')
    <a href="{{ route('admin.hotels.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
        Nuevo hotel
    </a>

    <table class="w-full mt-6 bg-white shadow rounded">
        <thead>
            <tr class="border-b">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Ciudad</th>
                <th class="p-3 text-left">Zona</th>
                <th class="p-3 text-left">Comisión</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($hotels as $hotel)
                <tr class="border-b">
                    <td class="p-3">{{ $hotel->id }}</td>
                    <td class="p-3">{{ $hotel->name }}</td>
                    <td class="p-3">{{ $hotel->city }}</td>
                    <td class="p-3">{{ $hotel->zona ?? '-' }}</td>
                    <td class="p-3">{{ number_format($hotel->comision_porcentaje ?? 0, 2) }}%</td>

                    <td class="p-3 flex gap-3">
                        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="text-blue-600">
                            Editar
                        </a>

                        <form action="{{ route('admin.hotels.destroy', $hotel) }}"
                              method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este hotel?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">
                                Eliminar
                            </button>
                        </form>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
