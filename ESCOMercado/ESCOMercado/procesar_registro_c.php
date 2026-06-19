<?php
// 1. Llamar a la conexión de la base de datos
require 'conexion.php';

// 2. Verificar que el formulario se haya enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Recibir los datos de los <input name="...">
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $boleta = $_POST['boleta'];
    $correo = $_POST['correo'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $rol = 'comprador';

    // 4. Instrucción SQL para insertar en la tabla usuarios
    $sql = "INSERT INTO usuarios (nombres, apellidos, boleta, correo, username, password, rol) 
            VALUES ('$nombre', '$apellidos', '$boleta', '$correo', '$username', '$password', '$rol')";

    // 5. Ejecutar y avisar al usuario
    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('¡Cuenta de comprador creada con éxito! Ahora puedes iniciar sesión.');
                window.location.href = 'ACCESO.html';
              </script>";
    } else {
        echo "Ocurrió un error: " . mysqli_error($conexion);
    }
}
?>