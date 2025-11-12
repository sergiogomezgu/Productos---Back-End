<?php include_once '../includes/header.php'; ?>

<main class="container" style="margin:40px auto; max-width:1000px;">
    <h1 style="text-align:center;">📅 Mi calendario de reservas</h1>
    <div id='calendar'></div>
</main>

<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: 'eventos_usuario.php'
    });
    calendar.render();
});
</script>

<?php include_once '../includes/footer.php'; ?>
