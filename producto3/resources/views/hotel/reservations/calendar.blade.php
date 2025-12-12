@extends('layouts.hotel')

@section('title', 'Calendario de Reservas')
@section('header', 'Calendario de Reservas')

@section('content')

<div class="bg-white p-6 shadow rounded">
    <div id="calendar"></div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        events: @json($events),
    });

    calendar.render();
});
</script>
@endpush
