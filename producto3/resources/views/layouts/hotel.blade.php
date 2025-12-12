<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Panel Hotel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">

    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-900 text-gray-200 flex flex-col">

            <div class="p-6 border-b border-gray-700">
                <h2 class="text-xl font-bold tracking-wide">Panel del Hotel</h2>
            </div>

            <nav class="flex-1 p-4 space-y-2">

                <a href="{{ route('hotel.dashboard') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    Dashboard
                </a>

                <a href="{{ route('hotel.reservations.index') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    Mis reservas
                </a>

                <a href="{{ route('hotel.reservations.calendar') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    Calendario
                </a>

                <a href="{{ route('hotel.availability') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    Disponibilidad
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    Mi perfil
                </a>

            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </aside>

        {{-- Contenido principal --}}
        <main class="flex-1 p-10">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">@yield('header')</h1>

            {{-- Mensajes de éxito --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 border border-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-300 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    @stack('scripts')
</body>
</html>
