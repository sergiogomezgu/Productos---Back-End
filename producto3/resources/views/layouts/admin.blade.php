<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-900 text-white p-4">
            <h1 class="text-xl font-bold mb-6">Admin</h1>
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block hover:bg-gray-700 px-3 py-2 rounded">
                    Dashboard
                </a>
                <a href="{{ route('admin.hotels.index') }}" class="block hover:bg-gray-700 px-3 py-2 rounded">
                    Hoteles
                </a>
               <a href="{{ route('admin.hotel_users.index') }}" class="block hover:bg-gray-700 px-3 py-2 rounded">
    Usuarios hotel
</a>
                <a href="{{ route('admin.reservations.index') }}" class="block hover:bg-gray-700 px-3 py-2 rounded">
    Reservas
</a>
<a href="{{ route('admin.reservations.calendar') }}" class="block hover:bg-gray-700 px-3 py-2 rounded">
    Calendario
</a>
            </nav>
        </aside>

        {{-- Contenido principal --}}
        <main class="flex-1 p-6">
            <header class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">
                    @yield('header', 'Panel de administración')
                </h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-600 hover:underline">
                        Cerrar sesión
                    </button>
                </form>
            </header>

            <section>
                @if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
                @yield('content')
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

@stack('scripts')
</body>
</html>
