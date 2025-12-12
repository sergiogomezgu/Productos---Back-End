<table class="w-full bg-white shadow rounded-lg overflow-hidden">
    <thead class="bg-gray-100 text-gray-700">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Entrada</th>
            <th class="p-3 text-left">Salida</th>
            <th class="p-3 text-left">Huéspedes</th>
            <th class="p-3 text-left">Estado</th>
            <th class="p-3 text-left">Acciones</th>
        </tr>
    </thead>

    <tbody class="text-gray-800">
        @foreach ($reservations as $reservation)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-3">{{ $reservation->id }}</td>
                <td class="p-3">{{ $reservation->check_in }}</td>
                <td class="p-3">{{ $reservation->check_out }}</td>
                <td class="p-3">{{ $reservation->guests }}</td>
                <td class="p-3 capitalize">
                    <span class="
                        px-2 py-1 rounded text-sm
                        @if($reservation->status == 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($reservation->status == 'confirmada') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif
                    ">
                        {{ $reservation->status }}
                    </span>
                </td>

                <td class="p-3 flex gap-3">
                    <a href="{{ route('hotel.reservations.edit', $reservation) }}"
                       class="text-blue-600 hover:underline">
                        Editar
                    </a>

                    <form action="{{ route('hotel.reservations.destroy', $reservation) }}"
                          method="POST"
                          onsubmit="return confirm('¿Eliminar reserva?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
