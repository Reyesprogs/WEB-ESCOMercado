<?php
session_start();
session_unset();   // Libera todas las variables de sesión
session_destroy(); // Destruye la sesión en el servidor

// Redirigir al inicio como invitado
header("Location: INICIO.html");
exit();
?>