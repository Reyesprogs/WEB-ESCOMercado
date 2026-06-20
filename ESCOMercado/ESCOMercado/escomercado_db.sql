-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2026 a las 03:06:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `escomercado_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `id_carrito` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`id_carrito`, `id_comprador`, `id_producto`, `cantidad`, `fecha_agregado`) VALUES
(1, 1, 4, 1, '2026-06-19 23:06:27'),
(2, 1, 1, 2, '2026-06-19 23:06:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id_favorito`, `id_comprador`, `id_producto`, `fecha_agregado`) VALUES
(1, 1, 3, '2026-06-19 23:06:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `zona_entrega` varchar(100) DEFAULT 'Por acordar',
  `estado_entrega` enum('pendiente','entregado','cancelado') DEFAULT 'pendiente',
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `horario_entrega` varchar(50) DEFAULT NULL,
  `ruta_evidencia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_comprador`, `id_producto`, `cantidad`, `precio_unitario`, `total`, `zona_entrega`, `estado_entrega`, `fecha_compra`, `horario_entrega`, `ruta_evidencia`) VALUES
(1, 1, 2, 1, 550.00, 550.00, 'Las Palapas', 'entregado', '2026-06-19 23:06:27', NULL, NULL),
(2, 4, 1, 1, 0.00, 150.00, 'area_verde', 'pendiente', '2026-06-19 23:45:31', '12:34', 'IMAGENES/evidencias/1781912731_c7.jpeg'),
(3, 4, 6, 2, 0.00, 160.00, 'area_verde', 'cancelado', '2026-06-20 00:50:22', '13:30', 'IMAGENES/evidencias/1781916622_c11.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 1,
  `categoria` varchar(50) NOT NULL,
  `subcategoria` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `ruta_imagen_principal` varchar(255) NOT NULL,
  `ruta_video` varchar(255) DEFAULT NULL,
  `estado` enum('disponible','apartado','vendido') DEFAULT 'disponible',
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `rutas_imagenes_secundarias` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_vendedor`, `titulo`, `precio`, `stock`, `categoria`, `subcategoria`, `descripcion`, `ruta_imagen_principal`, `ruta_video`, `estado`, `fecha_publicacion`, `rutas_imagenes_secundarias`) VALUES
(1, 2, 'Protoboard grande y Jumpers', 150.00, 2, 'electronica', 'prototipado', 'Protoboard de 830 puntos. Incluye cables macho-macho.', 'IMAGENES/m1.jpg', NULL, 'disponible', '2026-06-19 23:06:27', NULL),
(2, 2, 'Arduino Mega original', 550.00, 1, 'electronica', 'microcontroladores', 'Usado solo un semestre para Instrumentación. Funciona al 100%.', 'IMAGENES/m2.jpg', NULL, 'disponible', '2026-06-19 23:06:27', NULL),
(3, 3, 'Calculadora Casio fx-991', 450.00, 2, 'academicos', 'calculadoras', 'Perfecta para Ecuaciones Diferenciales y Álgebra Lineal.', 'IMAGENES/m3.jpg', NULL, 'disponible', '2026-06-19 23:06:27', NULL),
(4, 3, 'iPhone 17e', 18500.00, 1, 'computacion', 'perifericos', 'Potencia compacta. 8GB de RAM. 95% de batería.', 'IMAGENES/m4.jpg', 'IMAGENES/v1.mp4', 'disponible', '2026-06-19 23:06:27', NULL),
(5, 3, 'Bata de Laboratorio 100% Algodón', 250.00, 5, 'laboratorio', 'vestimenta', 'Talla M, cumple con la norma para laboratorios de Química.', 'IMAGENES/m5.jpg', NULL, 'disponible', '2026-06-19 23:06:27', NULL),
(6, 5, 'Protoboard tamaño chico', 80.00, 7, 'electronica', 'placas', 'Protoboard en buenas condiciones para practicas en laboratorio', 'IMAGENES/productos/1781916280_img_board.jpg', '', '', '2026-06-20 00:44:40', 'IMAGENES/productos/1781916280_sec_0_board.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte`
--

CREATE TABLE `soporte` (
  `id_reporte` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('abierto','en_revision','resuelto') DEFAULT 'abierto',
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `boleta` varchar(10) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('comprador','vendedor') NOT NULL,
  `ruta_foto_perfil` varchar(255) DEFAULT 'IMAGENES/logosf.png',
  `biografia` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `clabe` varchar(20) DEFAULT '012345678901234567'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombres`, `apellidos`, `boleta`, `correo`, `telefono`, `username`, `password`, `rol`, `ruta_foto_perfil`, `biografia`, `fecha_registro`, `clabe`) VALUES
(1, 'Emmanuel', 'Reyes', '2022630123', 'ereyes@alumno.ipn.mx', NULL, 'emma_buyer', '123456', 'comprador', 'IMAGENES/logosf.png', NULL, '2026-06-19 23:06:27', '012345678901234567'),
(2, 'Juan', 'Pérez', '2020630456', 'jperez@alumno.ipn.mx', '5512345678', 'juan_seller', '123456', 'vendedor', 'IMAGENES/vendedorjsjs.jpeg', 'Estudiante de 6to semestre. Vendo material de electrónica.', '2026-06-19 23:06:27', '012345678901234567'),
(3, 'Maria', 'Gomez', '2021630789', 'mgomez@alumno.ipn.mx', '5587654321', 'mary_tech', '123456', 'vendedor', 'IMAGENES/logosf.png', 'Actualizando mi setup. Vendo equipo de cómputo en buen estado.', '2026-06-19 23:06:27', '012345678901234567'),
(4, 'Reyes Caballero', 'Jesús Emmanuel', '2024630665', 'reyescab2104@gmail.com', NULL, 'don_chuy', 'donchuy', 'comprador', 'IMAGENES/logosf.png', NULL, '2026-06-19 23:12:28', '012345678901234567'),
(5, 'Luis', 'Efren', '2024556675', 'pepe@gmail.com', '5548876728', 'pepe', 'pepe', 'vendedor', 'IMAGENES/logosf.png', NULL, '2026-06-19 23:22:41', '012345678901234567');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `fk_carrito_comprador` (`id_comprador`),
  ADD KEY `fk_carrito_producto` (`id_producto`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `fk_favorito_comprador` (`id_comprador`),
  ADD KEY `fk_favorito_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedido_comprador` (`id_comprador`),
  ADD KEY `fk_pedido_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_soporte_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `boleta` (`boleta`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `soporte`
--
ALTER TABLE `soporte`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD CONSTRAINT `fk_carrito_comprador` FOREIGN KEY (`id_comprador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_carrito_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `fk_favorito_comprador` FOREIGN KEY (`id_comprador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorito_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_comprador` FOREIGN KEY (`id_comprador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedido_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD CONSTRAINT `fk_soporte_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
