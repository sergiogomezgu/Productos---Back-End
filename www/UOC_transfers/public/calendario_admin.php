<?php
// public/calendario_admin.php - versión corregida y segura
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../includes/verificar_admin.php';

// En este punto verificar_admin.php ya ha comprobado que el usuario logueado
// está en la lista de admins y que existe $_SESSION['email']
// Aquí cualquier lógica PHP adicional se realiza sin salida previa.

include_once __DIR__ . '/../includes/header.php';
?>
<main class="container" style="margin:40px auto; max-width:1000px;">
    <h1 style="text-align:center;">📅 Calendario de trayectos</h1>
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
        events: 'eventos.php',
        eventClick: function(info) {
            alert("Reserva: " + info.event.title + "\nFecha: " + info.event.start.toLocaleString());
        }
    });
    calendar.render();
});
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
