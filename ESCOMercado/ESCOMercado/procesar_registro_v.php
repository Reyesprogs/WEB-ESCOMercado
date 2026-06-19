<?php
// 1. Llamar a la conexión de la base de datos
require 'conexion.php';

// 2. Verificar que el formulario se haya enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Recibir los datos. Ojo: Aquí SÍ pedimos el teléfono
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $boleta = $_POST['boleta'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono']; // <-- Dato exclusivo del vendedor
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $rol = 'vendedor';

    // 4. Instrucción SQL para insertar en la tabla usuarios (incluye telefono)
    $sql = "INSERT INTO usuarios (nombres, apellidos, boleta, correo, telefono, username, password, rol) 
            VALUES ('$nombre', '$apellidos', '$boleta', '$correo', '$telefono', '$username', '$password', '$rol')";

    // 5. Ejecutar y avisar al usuario
    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('¡Cuenta de vendedor creada con éxito! Ahora puedes iniciar sesión.');
                window.location.href = 'ACCESO.html';
              </script>";
    } else {
        // Mejoramos el manejo de errores para que te avise exactamente qué falló (ej. Boleta duplicada)
        echo "<script>
                alert('Error al crear la cuenta: " . mysqli_error($conexion) . "');
                window.history.back();
              </script>";
    }
}
?>