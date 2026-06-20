<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ACCESO.html");
    exit();
}

$id_pedido = intval($_POST['id_pedido']);
$accion = $_POST['accion'];

// Obtener los datos del pedido para saber qué producto es y cuántos son (necesario si cancelamos)
$q_pedido = "SELECT id_producto, cantidad FROM pedidos WHERE id_pedido = $id_pedido";
$res_pedido = mysqli_query($conexion, $q_pedido);

if (mysqli_num_rows($res_pedido) > 0) {
    $pedido = mysqli_fetch_assoc($res_pedido);
    $id_producto = $pedido['id_producto'];
    $cantidad = $pedido['cantidad'];

    // LÓGICA SEGÚN EL BOTÓN PRESIONADO
    if ($accion === 'aceptar') {
        
        // Cambia el estado a entregado/aceptado
        $query = "UPDATE pedidos SET estado_entrega = 'aceptado' WHERE id_pedido = $id_pedido";
        mysqli_query($conexion, $query);
        echo "<script>
                alert('¡Pago y evidencia aceptados! El comprador recibirá la confirmación en su historial.'); 
                window.location.href='CUENTA_V.html';
              </script>";

    } elseif ($accion === 'regresar') {
        
        // Cambia el estado a rechazada. El stock sigue apartado, pero el comprador debe resubir evidencia
        $query = "UPDATE pedidos SET estado_entrega = 'evidencia_rechazada' WHERE id_pedido = $id_pedido";
        mysqli_query($conexion, $query);
        echo "<script>
                alert('Evidencia rechazada. El pedido sigue apartado, pero se notificará al comprador que algo no cuadró.'); 
                window.location.href='CUENTA_V.html';
              </script>";

    } elseif ($accion === 'cancelar') {
        
        // 1. Regresar la cantidad apartada al stock original del producto
        $query_stock = "UPDATE productos SET stock = stock + $cantidad WHERE id_producto = $id_producto";
        mysqli_query($conexion, $query_stock);
        
        // 2. Marcar el pedido como cancelado
        $query_cancelar = "UPDATE pedidos SET estado_entrega = 'cancelado' WHERE id_pedido = $id_pedido";
        mysqli_query($conexion, $query_cancelar);

        echo "<script>
                alert('Pedido cancelado. El apartado se eliminó y las $cantidad piezas regresaron al stock.'); 
                window.location.href='CUENTA_V.html';
              </script>";
    }
} else {
    echo "<script>alert('Error: No se encontró el pedido en el sistema.'); window.location.href='CUENTA_V.html';</script>";
}
?>