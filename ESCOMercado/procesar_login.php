<?php
// Arrancar el motor de sesiones de PHP
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $credencial = $_POST['credencial']; // Puede ser el username o el correo
    $password = $_POST['password'];

    // Buscar al usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE (correo = '$credencial' OR username = '$credencial') AND password = '$password'";
    $resultado = mysqli_query($conexion, $sql);

    // Si se encuentra exactamente 1 registro, el login es exitoso
    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Guardar datos clave en la sesión global
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombres'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        // Redirigir a la página de inicio
        header("Location: INICIO.html");
        exit();
    } else {
        // Si falla, avisar y regresar
        echo "<script>
                alert('Credenciales incorrectas. Verifica tu usuario/correo y contraseña.');
                window.location.href = 'ACCESO.html';
              </script>";
    }
}
?>