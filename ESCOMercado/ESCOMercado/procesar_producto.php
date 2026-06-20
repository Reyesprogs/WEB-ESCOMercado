<?php
session_start();
require 'conexion.php';

// Validar que un vendedor haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    die("<script>alert('Debes iniciar sesión para publicar un producto.'); window.location.href='ACCESO.html';</script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_vendedor = $_SESSION['usuario_id'];
    
    // 1. Limpieza de datos (Evitar inyecciones SQL)
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo_prod']);
    $precio = floatval($_POST['precio_prod']);
    $stock = intval($_POST['stock_prod']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria_prod']);
    $subcategoria = mysqli_real_escape_string($conexion, $_POST['subcategoria_prod']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['desc_prod']);
    $estado = 'disponible';
    
    // Directorios de guardado (se crean si no existen)
    $dir_img = 'IMAGENES/productos/';
    $dir_vid = 'IMAGENES/videos/';
    
    if (!file_exists($dir_img)) { mkdir($dir_img, 0777, true); }
    if (!file_exists($dir_vid)) { mkdir($dir_vid, 0777, true); }
    
    // --- 2. SUBIR IMAGEN PRINCIPAL (Obligatoria) ---
    $ruta_imagen_principal = '';
    if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] == 0) {
        $nombre_img = time() . '_img_' . basename($_FILES['imagen_principal']['name']);
        $ruta_imagen_principal = $dir_img . $nombre_img;
        move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_imagen_principal);
    } else {
        die("<script>alert('Error: La imagen de portada es obligatoria.'); window.history.back();</script>");
    }

    // --- 3. SUBIR VIDEO DEMOSTRATIVO (Opcional) ---
    $ruta_video = ''; 
    if (isset($_FILES['video_prod']) && $_FILES['video_prod']['error'] == 0) {
        $nombre_vid = time() . '_vid_' . basename($_FILES['video_prod']['name']);
        $ruta_video = $dir_vid . $nombre_vid;
        move_uploaded_file($_FILES['video_prod']['tmp_name'], $ruta_video);
    }

    // --- 4. SUBIR IMÁGENES SECUNDARIAS MÚLTIPLES (Opcional) ---
    $rutas_secundarias = [];
    // Verificamos si se subieron archivos al arreglo de imágenes secundarias
    if (isset($_FILES['imagenes_secundarias']) && !empty($_FILES['imagenes_secundarias']['name'][0])) {
        $total_archivos = count($_FILES['imagenes_secundarias']['name']);
        
        for ($i = 0; $i < $total_archivos; $i++) {
            if ($_FILES['imagenes_secundarias']['error'][$i] == 0) {
                // Generar nombre único para cada foto de la galería
                $nombre_sec = time() . "_sec_{$i}_" . basename($_FILES['imagenes_secundarias']['name'][$i]);
                $ruta_sec = $dir_img . $nombre_sec;
                
                if (move_uploaded_file($_FILES['imagenes_secundarias']['tmp_name'][$i], $ruta_sec)) {
                    $rutas_secundarias[] = $ruta_sec;
                }
            }
        }
    }
    
    // Convertimos la lista de rutas en una sola cadena de texto separada por comas (ej. "img1.png,img2.png")
    $string_secundarias = implode(',', $rutas_secundarias);

    // --- 5. INSERTAR TODO EN LA BASE DE DATOS ---
    $query = "INSERT INTO productos (id_vendedor, titulo, precio, stock, categoria, subcategoria, descripcion, ruta_imagen_principal, ruta_video, rutas_imagenes_secundarias, estado) 
              VALUES ($id_vendedor, '$titulo', $precio, $stock, '$categoria', '$subcategoria', '$descripcion', '$ruta_imagen_principal', '$ruta_video', '$string_secundarias', '$estado')";

    if (mysqli_query($conexion, $query)) {
        echo "<script>
                alert('¡Artículo publicado con éxito en el catálogo!');
                window.location.href = 'CUENTA_V.html';
              </script>";
    } else {
        echo "<script>
                alert('Hubo un error interno en la base de datos: " . mysqli_error($conexion) . "');
                window.history.back();
              </script>";
    }
} else {
    // Si alguien intenta entrar directo por la URL en vez del formulario
    header("Location: CUENTA_V.html");
}
?>