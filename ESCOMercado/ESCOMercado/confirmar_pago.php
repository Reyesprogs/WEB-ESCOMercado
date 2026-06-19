<?php
session_start();
require 'conexion.php';

// Validar que el usuario sea un comprador logueado
if (!isset($_SESSION['usuario_id'])) {
    die("<script>alert('Debes iniciar sesión.'); window.location.href='ACCESO.html';</script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_comprador = $_SESSION['usuario_id'];
    $id_vendedor = intval($_POST['id_vendedor']);
    
    // 1. Procesar el lugar de entrega
    $lugar_entrega = $_POST['lugar_entrega'];
    if ($lugar_entrega === 'salon') {
        // Si eligió salón, guardamos el número que escribió
        $zona_entrega = "Salón: " . mysqli_real_escape_string($conexion, $_POST['salon_especifico']);
    } else {
        $zona_entrega = mysqli_real_escape_string($conexion, $lugar_entrega);
    }
    
    $horario_entrega = mysqli_real_escape_string($conexion, $_POST['horario_entrega']);
    
    // 2. Subir la imagen de evidencia (comprobante)
    $ruta_evidencia = '';
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $directorio_destino = 'IMAGENES/evidencias/';
        
        // Si la carpeta no existe, la crea automáticamente
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        // Generar un nombre único para que no choquen imágenes con el mismo nombre
        $nombre_archivo = time() . '_' . basename($_FILES['comprobante']['name']);
        $ruta_final = $directorio_destino . $nombre_archivo;
        
        if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta_final)) {
            $ruta_evidencia = $ruta_final;
        }
    }

    if (empty($ruta_evidencia)) {
        die("<script>alert('Error al subir el comprobante de pago. Intenta de nuevo.'); window.history.back();</script>");
    }

    // 3. Procesar SOLO los productos del carrito que pertenecen a este vendedor
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
            
            // Consultamos a quién le pertenece este producto
            $query_prod = "SELECT precio, id_vendedor, stock FROM productos WHERE id_producto = $id_producto";
            $res_prod = mysqli_query($conexion, $query_prod);
            
            if ($row_prod = mysqli_fetch_assoc($res_prod)) {
                // Si el producto es del vendedor al que le estamos pagando
                if ($row_prod['id_vendedor'] == $id_vendedor) {
                    
                    $total_producto = $row_prod['precio'] * $cantidad;
                    
                    // A) Insertar el pedido en la BD
                    $query_pedido = "INSERT INTO pedidos (id_producto, id_comprador, cantidad, total, zona_entrega, horario_entrega, ruta_evidencia, estado_entrega) 
                                     VALUES ($id_producto, $id_comprador, $cantidad, $total_producto, '$zona_entrega', '$horario_entrega', '$ruta_evidencia', 'pendiente')";
                    mysqli_query($conexion, $query_pedido);
                    
                    // B) Restar el stock del producto
                    $nuevo_stock = $row_prod['stock'] - $cantidad;
                    $query_stock = "UPDATE productos SET stock = $nuevo_stock WHERE id_producto = $id_producto";
                    mysqli_query($conexion, $query_stock);
                    
                    // C) Eliminar este producto específico del carrito (para que ya no aparezca)
                    unset($_SESSION['carrito'][$id_producto]);
                }
            }
        }
        
        // 4. Éxito y redirección al panel del comprador
        echo "<script>
                alert('¡Pago confirmado y enviado a revisión! El vendedor ha recibido tus datos.');
                window.location.href = 'CUENTA_C.html';
              </script>";
    } else {
        echo "<script>alert('Tu carrito está vacío.'); window.location.href = 'PRODUCTOS.html';</script>";
    }
} else {
    header("Location: CARRITO.html");
}
?>