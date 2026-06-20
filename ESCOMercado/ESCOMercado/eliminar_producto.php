<?php
session_start();
require 'conexion.php';

// Validar seguridad
if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ACCESO.html");
    exit();
}

$id_producto = intval($_POST['id_producto']);
$id_vendedor = $_SESSION['usuario_id'];

// Hacemos un "Soft Delete" cambiando el estado para no romper historiales de compra
$query = "UPDATE productos SET estado = 'eliminado' WHERE id_producto = $id_producto AND id_vendedor = $id_vendedor";

if (mysqli_query($conexion, $query)) {
    echo "<script>
            alert('Producto eliminado correctamente del catálogo.');
            window.location.href='CUENTA_V.html';
          </script>";
} else {
    echo "<script>
            alert('Error al eliminar el producto.');
            window.history.back();
          </script>";
}
?>