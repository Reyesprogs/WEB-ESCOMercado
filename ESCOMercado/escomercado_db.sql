-- --------------------------------------------------------
-- SCRIPT DE BASE DE DATOS: ESCOMercado
-- --------------------------------------------------------

-- Crear la base de datos si no existe y usarla
CREATE DATABASE IF NOT EXISTS escomercado_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escomercado_db;

-- --------------------------------------------------------
-- 1. TABLA: usuarios (Compradores y Vendedores)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    boleta VARCHAR(10) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(15) DEFAULT NULL, -- Null para compradores, obligatorio para vendedores
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Aquí se guardarán las contraseñas hasheadas
    rol ENUM('comprador', 'vendedor') NOT NULL,
    ruta_foto_perfil VARCHAR(255) DEFAULT 'IMAGENES/logosf.png',
    biografia TEXT DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- 2. TABLA: productos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_vendedor INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 1,
    categoria VARCHAR(50) NOT NULL,
    subcategoria VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    ruta_imagen_principal VARCHAR(255) NOT NULL,
    ruta_video VARCHAR(255) DEFAULT NULL,
    estado ENUM('disponible', 'apartado', 'vendido') DEFAULT 'disponible',
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_vendedor FOREIGN KEY (id_vendedor) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 3. TABLA: carrito_compras (Productos apartados temporalmente)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS carrito_compras (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_carrito_comprador FOREIGN KEY (id_comprador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_carrito_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 4. TABLA: favoritos (Productos guardados con el corazón)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_favorito_comprador FOREIGN KEY (id_comprador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_favorito_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 5. TABLA: pedidos (Historial de compras concretadas)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    zona_entrega VARCHAR(100) DEFAULT 'Por acordar',
    estado_entrega ENUM('pendiente', 'entregado', 'cancelado') DEFAULT 'pendiente',
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedido_comprador FOREIGN KEY (id_comprador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_pedido_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 6. TABLA: soporte (Reportes y quejas)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS soporte (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    asunto VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('abierto', 'en_revision', 'resuelto') DEFAULT 'abierto',
    fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_soporte_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- INSERCIÓN DE DATOS DE PRUEBA (MOCK DATA)
-- --------------------------------------------------------

-- 1. Insertar Usuarios de Prueba (Contraseña simulada '123456')
INSERT INTO usuarios (nombres, apellidos, boleta, correo, telefono, username, password, rol, biografia, ruta_foto_perfil) VALUES 
('Emmanuel', 'Reyes', '2022630123', 'ereyes@alumno.ipn.mx', NULL, 'emma_buyer', '123456', 'comprador', NULL, 'IMAGENES/logosf.png'),
('Juan', 'Pérez', '2020630456', 'jperez@alumno.ipn.mx', '5512345678', 'juan_seller', '123456', 'vendedor', 'Estudiante de 6to semestre. Vendo material de electrónica.', 'IMAGENES/vendedorjsjs.jpeg'),
('Maria', 'Gomez', '2021630789', 'mgomez@alumno.ipn.mx', '5587654321', 'mary_tech', '123456', 'vendedor', 'Actualizando mi setup. Vendo equipo de cómputo en buen estado.', 'IMAGENES/logosf.png');

-- 2. Insertar Productos de Prueba
INSERT INTO productos (id_vendedor, titulo, precio, stock, categoria, subcategoria, descripcion, ruta_imagen_principal, ruta_video) VALUES 
(2, 'Protoboard grande y Jumpers', 150.00, 3, 'electronica', 'prototipado', 'Protoboard de 830 puntos. Incluye cables macho-macho.', 'IMAGENES/m1.jpg', NULL),
(2, 'Arduino Mega original', 550.00, 1, 'electronica', 'microcontroladores', 'Usado solo un semestre para Instrumentación. Funciona al 100%.', 'IMAGENES/m2.jpg', NULL),
(3, 'Calculadora Casio fx-991', 450.00, 2, 'academicos', 'calculadoras', 'Perfecta para Ecuaciones Diferenciales y Álgebra Lineal.', 'IMAGENES/m3.jpg', NULL),
(3, 'iPhone 17e', 18500.00, 1, 'computacion', 'perifericos', 'Potencia compacta. 8GB de RAM. 95% de batería.', 'IMAGENES/m4.jpg', 'IMAGENES/v1.mp4'),
(3, 'Bata de Laboratorio 100% Algodón', 250.00, 5, 'laboratorio', 'vestimenta', 'Talla M, cumple con la norma para laboratorios de Química.', 'IMAGENES/m5.jpg', NULL);

-- 3. Insertar Carrito y Favoritos de Prueba para el Comprador (Emmanuel)
INSERT INTO carrito_compras (id_comprador, id_producto, cantidad) VALUES 
(1, 4, 1), -- Tiene el iPhone en el carrito
(1, 1, 2); -- Tiene 2 protoboards en el carrito

INSERT INTO favoritos (id_comprador, id_producto) VALUES 
(1, 3); -- Tiene la calculadora en favoritos

-- 4. Insertar un Historial de Pedido Pasado
INSERT INTO pedidos (id_comprador, id_producto, cantidad, precio_unitario, total, zona_entrega, estado_entrega) VALUES 
(1, 2, 1, 550.00, 550.00, 'Las Palapas', 'entregado');