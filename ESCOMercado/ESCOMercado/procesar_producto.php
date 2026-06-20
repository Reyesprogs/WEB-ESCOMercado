<?php
session_start();
require 'conexion.php';

// 1. Validar que el usuario tenga sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ACCESO.html");
    exit();
}

$id_vendedor = $_SESSION['usuario_id'];

// 2. Verificar que la petición sea por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recibir y sanitizar campos de texto
    $titulo       = mysqli_real_escape_string($conexion, $_POST['titulo_prod']);
    $precio       = mysqli_real_escape_string($conexion, $_POST['precio_prod']);
    $stock        = mysqli_real_escape_string($conexion, $_POST['stock_prod']);
    $categoria    = mysqli_real_escape_string($conexion, $_POST['categoria_prod']);
    $subcategoria = mysqli_real_escape_string($conexion, $_POST['subcategoria_prod']);
    $descripcion  = mysqli_real_escape_string($conexion, $_POST['desc_prod']);
    
    // Definir y crear directorio de subidas si no existe
    $directorio_subida = 'IMAGENES/productos/';
    if (!is_dir($directorio_subida)) {
        mkdir($directorio_subida, 0777, true);
    }

    // --------------------------------------------------------
    // PROCESAR: Imagen Principal (Portada)
    // --------------------------------------------------------
    $ruta_imagen_principal = "";
    if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === 0) {
        $ext = pathinfo($_FILES['imagen_principal']['name'], PATHINFO_EXTENSION);
        // Generamos un nombre único con la función time() para evitar duplicados
        $nombre_img_principal = "prod_" . time() . "_" . uniqid() . "." . $ext;
        $ruta_final_principal = $directorio_subida . $nombre_img_principal;

        if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_final_principal)) {
            $ruta_imagen_principal = $ruta_final_principal;
        } else {
            echo "Error al subir la imagen principal.";
            exit();
        }
    } else {
        echo "La imagen principal es obligatoria.";
        exit();
    }

    // --------------------------------------------------------
    // PROCESAR: Video Demostrativo (Opcional)
    // --------------------------------------------------------
    $ruta_video = NULL;
    if (isset($_FILES['video_prod']) && $_FILES['video_prod']['error'] === 0) {
        $ext_video = pathinfo($_FILES['video_prod']['name'], PATHINFO_EXTENSION);
        $nombre_video = "vid_" . time() . "_" . uniqid() . "." . $ext_video;
        $ruta_final_video = $directorio_subida . $nombre_video;

        if (move_uploaded_file($_FILES['video_prod']['tmp_name'], $ruta_final_video)) {
            $ruta_video = "'$ruta_final_video'";
        }
    }
    // Si no se subió video, lo preparamos como NULL para la consulta SQL
    $ruta_video_sql = $ruta_video ?? "NULL";

    // --------------------------------------------------------
    // INSERTAR: Registro del producto en la Base de Datos
    // --------------------------------------------------------
    $query_producto = "INSERT INTO productos (id_vendedor, titulo, precio, stock, categoria, subcategoria, ruta_imagen_principal, ruta_video, descripcion, estado) 
                       VALUES ($id_vendedor, '$titulo', $precio, $stock, '$categoria', '$subcategoria', '$ruta_imagen_principal', $ruta_video_sql, '$descripcion', 'disponible')";

    if (mysqli_query($conexion, $query_producto)) {
        // Obtenemos el ID del producto que se acaba de crear para asociarle sus fotos secundarias
        $id_nuevo_producto = mysqli_insert_id($conexion);

        // --------------------------------------------------------
        // PROCESAR: Imágenes Secundarias / Capturas (Múltiples)
        // --------------------------------------------------------
        if (isset($_FILES['imagenes_secundarias']) && !empty($_FILES['imagenes_secundarias']['name'][0])) {
            $total_imagenes = count($_FILES['imagenes_secundarias']['name']);

            // Iteramos sobre el arreglo múltiple de archivos de PHP
            for ($i = 0; $i < $total_imagenes; $i++) {
                if ($_FILES['imagenes_secundarias']['error'][$i] === 0) {
                    $ext_sec = pathinfo($_FILES['imagenes_secundarias']['name'][$i], PATHINFO_EXTENSION);
                    $nombre_sec = "sec_" . time() . "_" . $i . "_" . uniqid() . "." . $ext_sec;
                    $ruta_final_sec = $directorio_subida . $nombre_sec;

                    if (move_uploaded_file($_FILES['imagenes_secundarias']['tmp_name'][$i], $ruta_final_sec)) {
                        // Insertar la ruta de la imagen secundaria en su respectiva tabla auxiliar
                        $query_secundaria = "INSERT INTO imagenes_productos (id_producto, ruta_imagen) VALUES ($id_nuevo_producto, '$ruta_final_sec')";
                        mysqli_query($conexion, $query_secundaria);
                    }
                }
            }
        }

        // Redirección exitosa de vuelta al panel
        header("Location: CUENTA_V.html?mensaje=producto_publicado");
        exit();

    } else {
        echo "Error al registrar el producto en la base de datos: " . mysqli_error($conexion);
    }

} else {
    header("Location: CUENTA_V.html");
    exit();
}
?>