<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: CUENTA_V.html");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$directorio_subida = 'IMAGENES/perfiles/';

// Crear carpeta si no existe
if (!is_dir($directorio_subida)) {
    mkdir($directorio_subida, 0777, true);
}

if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === 0) {
    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $nombre_foto = "perfil_" . $id_usuario . "_" . time() . "." . $ext;
    $ruta_final = $directorio_subida . $nombre_foto;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_final)) {
        // Actualizar la ruta en la base de datos
        $query = "UPDATE usuarios SET ruta_foto_perfil = '$ruta_final' WHERE id_usuario = $id_usuario";
        mysqli_query($conexion, $query);
    }
}

// Retornamos al panel
header("Location: CUENTA_V.html?mensaje=foto_actualizada");
exit();
?>