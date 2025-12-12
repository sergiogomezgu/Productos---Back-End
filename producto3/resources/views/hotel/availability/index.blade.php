@extends('layouts.hotel')

@section('title', 'Disponibilidad')
@section('header', 'Disponibilidad del Hotel')

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

        events: "{{ route('hotel.availability.api') }}",

        dateClick: function(info) {
            fetch("{{ route('hotel.availability.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ date: info.dateStr })
            })
            .then(() => calendar.refetchEvents());
        }
    });

    calendar.render();
});
</script>
@endpush
