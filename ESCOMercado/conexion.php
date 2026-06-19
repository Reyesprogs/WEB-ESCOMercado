<?php
$servidor = "localhost";
$usuario = "root";
$password = ""; // Vacío por defecto en XAMPP
$base_datos = "escomercado_db";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
// Forzar codificación para que los acentos y las 'ñ' se vean bien
mysqli_set_charset($conexion, "utf8mb4");
?>