<?php
session_start();
require 'conexion.php';

// Validar sesión activa
if(!isset($_SESSION['usuario_id'])) {
    header("Location: ACCESO.html");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Detectar si la petición viene del historial de compras
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_pedido' && isset($_POST['id_pedido'])) {
    
    $id_pedido = mysqli_real_escape_string($conexion, $_POST['id_pedido']);
    
    // Eliminamos el pedido asegurándonos de que pertenezca al usuario en sesión
    $query_eliminar_pedido = "DELETE FROM pedidos WHERE id_pedido = $id_pedido AND id_comprador = $id_usuario";
    
    if(mysqli_query($conexion, $query_eliminar_pedido)) {
        header("Location: CUENTA_V.html?mensaje=pedido_eliminado");
        exit();
    } else {
        echo "Error al eliminar el pedido: " . mysqli_error($conexion);
    }

} 
// De lo contrario, procedemos con la eliminación normal del producto en venta
else if (isset($_POST['id_producto'])) {
    
    $id_producto = mysqli_real_escape_string($conexion, $_POST['id_producto']);
    
    // Cambiamos el estado a 'eliminado' para que la nueva consulta de CUENTA_V lo ignore
    $query_eliminar_prod = "UPDATE productos SET estado = 'eliminado' WHERE id_producto = $id_producto AND id_vendedor = $id_usuario";
    
    if(mysqli_query($conexion, $query_eliminar_prod)) {
        header("Location: CUENTA_V.html?mensaje=producto_eliminado");
        exit();
    } else {
        echo "Error al eliminar el producto: " . mysqli_error($conexion);
    }

} else {
    // Si se accede al archivo sin datos válidos
    header("Location: CUENTA_V.html");
    exit();
}
?>