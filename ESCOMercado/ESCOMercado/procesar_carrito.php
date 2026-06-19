<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // Si el carrito no existe en la sesión, lo creamos
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Si el producto ya está en el carrito, sumamos la cantidad; si no, lo agregamos
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += $cantidad;
    } else {
        $_SESSION['carrito'][$id_producto] = $cantidad;
    }

    // Redirigimos a la vista del carrito
    header("Location: CARRITO.html");
    exit();
} else {
    header("Location: PRODUCTOS.html");
    exit();
}
?>
