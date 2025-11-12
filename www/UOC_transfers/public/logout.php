<?php
session_start();
session_unset();
session_destroy();

// Redirige al inicio con mensaje
header("Location: index.php?msg=logout");
exit();
