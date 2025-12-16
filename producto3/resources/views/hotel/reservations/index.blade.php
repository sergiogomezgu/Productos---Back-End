<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <tr>
                <th class="p-4 text-left font-semibold">Tipo</th>
                <th class="p-4 text-left font-semibold">Fecha</th>
                <th class="p-4 text-left font-semibold">Cliente</th>
                <th class="p-4 text-left font-semibold">Viajeros</th>
                <th class="p-4 text-left font-semibold">Vehículo</th>
                <th class="p-4 text-left font-semibold">Precio</th>
                <th class="p-4 text-left font-semibold">Estado</th>
                <th class="p-4 text-left font-semibold">Acciones</th>
            </tr>
        </thead>

        <tbody class="text-gray-700">
            @forelse ($reservations as $reservation)
                <tr class="border-b hover:bg-blue-50 transition duration-200">
                    <td class="p-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if($reservation->tipo_traslado == 'Llegada') bg-green-100 text-green-800
                            @elseif($reservation->tipo_traslado == 'Salida') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $reservation->tipo_traslado }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->fecha_traslado)->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->hora_traslado)->format('H:i') }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-medium">{{ $reservation->cliente_nombre }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->cliente_email }}</div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 rounded-full font-semibold">
                            {{ $reservation->num_viajeros }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $reservation->vehiculo_tipo == 'Turismo' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ $reservation->vehiculo_tipo }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold text-green-600">
                        {{ number_format($reservation->precio, 2) }}€
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if($reservation->estado == 'Pendiente') bg-yellow-100 text-yellow-800
                            @elseif($reservation->estado == 'Confirmada') bg-green-100 text-green-800
                            @elseif($reservation->estado == 'Completada') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $reservation->estado }}
                        </span>
                    </td>

                    <td class="p-4">
                        <div class="flex gap-2">
                            <a href="{{ route('hotel.reservations.edit', $reservation) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>

                            <form action="{{ route('hotel.reservations.destroy', $reservation) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar esta reserva?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-lg font-medium">No hay reservas registradas</p>
                        <p class="text-sm mt-2">Las reservas aparecerán aquí cuando se creen</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
