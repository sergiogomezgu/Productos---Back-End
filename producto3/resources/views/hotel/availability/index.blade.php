@extends('layouts.hotel')

@section('title', 'Disponibilidad')
@section('header', 'Gestión de Disponibilidad de Vehículos')

@section('content')

<div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <div class="flex items-center">
        <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-blue-800">Gestione los vehículos disponibles por fecha para su hotel</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Formulario para agregar disponibilidad -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Agregar Disponibilidad</h3>
            
            <form action="{{ route('hotel.availability.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="fecha" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vehículo</label>
                    <select name="vehiculo_tipo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Turismo">Turismo</option>
                        <option value="Bus">Bus</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Vehículos</label>
                    <input type="number" name="num_vehiculos" min="1" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar Disponibilidad
                </button>
            </form>
        </div>
    </div>
    
    <!-- Lista de disponibilidades -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-xl font-semibold text-white">Disponibilidades Registradas</h3>
            </div>
            
            <div class="p-6">
                @if($days->count() > 0)
                    <div class="space-y-4">
                        @foreach($days->sortByDesc('fecha') as $day)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($day->fecha)->format('d/m/Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($day->fecha)->locale('es')->isoFormat('dddd') }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $day->vehiculo_tipo == 'Turismo' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ $day->vehiculo_tipo }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ $day->num_vehiculos }} vehículos
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <form action="{{ route('hotel.availability.destroy', $day) }}" method="POST" 
                                      onsubmit="return confirm('¿Eliminar esta disponibilidad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 text-lg">No hay disponibilidades registradas</p>
                        <p class="text-gray-400 text-sm mt-2">Agregue disponibilidades usando el formulario</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>

@endsection
