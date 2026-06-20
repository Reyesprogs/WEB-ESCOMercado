<?php
session_start();
require 'conexion.php';

// Validar sesión activa
if(!isset($_SESSION['usuario_id'])) {
    header("Location: ACCESO.html");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// 1. Detectar si la petición viene del historial de COMPRADOR
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_pedido' && isset($_POST['id_pedido'])) {
    
    $id_pedido = mysqli_real_escape_string($conexion, $_POST['id_pedido']);
    
    // Eliminamos el pedido asegurándonos de que pertenezca al comprador en sesión
    $query_eliminar_pedido = "DELETE FROM pedidos WHERE id_pedido = $id_pedido AND id_comprador = $id_usuario";
    
    if(mysqli_query($conexion, $query_eliminar_pedido)) {
        header("Location: CUENTA_V.html?mensaje=pedido_eliminado");
        exit();
    } else {
        echo "Error al eliminar el pedido: " . mysqli_error($conexion);
    }

} 
// 2. Detectar si el VENDEDOR quiere limpiar un pedido de "REGRESADOS"
else if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_regresado' && isset($_POST['id_pedido'])) {
    
    $id_pedido = mysqli_real_escape_string($conexion, $_POST['id_pedido']);
    
    // Aquí hacemos un JOIN para asegurar que el usuario que intenta borrar el pedido es realmente el vendedor de ese producto
    $query_eliminar_regresado = "DELETE pedidos FROM pedidos 
                                 INNER JOIN productos ON pedidos.id_producto = productos.id_producto 
                                 WHERE pedidos.id_pedido = $id_pedido AND productos.id_vendedor = $id_usuario";
    
    if(mysqli_query($conexion, $query_eliminar_regresado)) {
        header("Location: CUENTA_V.html?mensaje=regresado_limpiado");
        exit();
    } else {
        echo "Error al limpiar el registro: " . mysqli_error($conexion);
    }

}
// 3. De lo contrario, procedemos con la eliminación normal del PRODUCTO en venta
else if (isset($_POST['id_producto'])) {
    
    $id_producto = mysqli_real_escape_string($conexion, $_POST['id_producto']);
    
    // Cambiamos el estado a 'eliminado'
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