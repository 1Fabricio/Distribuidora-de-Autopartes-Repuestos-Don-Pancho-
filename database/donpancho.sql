-- Creacion de la BD
CREATE DATABASE IF NOT EXISTS don_pancho;
USE don_pancho;

-- Usuarios del sistema (login)
CREATE TABLE usuarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) NOT NULL,
    usuario       VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol           ENUM('admin','bodeguero','cliente') NOT NULL DEFAULT 'cliente',
    activo        TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Categorías del catalogo
CREATE TABLE categorias (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE
);

-- Marcas vehiculares aliadas
CREATE TABLE marcas (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL UNIQUE
);

-- Sucursales fisicas
CREATE TABLE sucursales (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    ciudad VARCHAR(60) NOT NULL,
    zona   VARCHAR(80) NOT NULL
);

-- Productos
CREATE TABLE productos (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(120)  NOT NULL,
    especificaciones VARCHAR(255)  NULL,
    descripcion      TEXT          NULL,
    precio           DECIMAL(10,2) NOT NULL,
    stock            INT UNSIGNED  NOT NULL DEFAULT 0,
    fotografia       VARCHAR(100)  NULL,
    destacado        TINYINT(1)    NOT NULL DEFAULT 0,
    categoria_id     INT UNSIGNED  NOT NULL,
    creado_en        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Opciones del cotizador
CREATE TABLE kit_opciones (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo   ENUM('vehiculo','marca_aceite','viscosidad','filtro','extra') NOT NULL,
    nombre VARCHAR(80)   NOT NULL,
    costo  DECIMAL(10,2) NOT NULL DEFAULT 0.00
);

-- Pedidos
CREATE TABLE pedidos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id      INT UNSIGNED NULL,      -- NULL si compró como visitante
    sucursal_id     INT UNSIGNED NULL,
    flete_modalidad ENUM('retiro','moto','camion') NOT NULL DEFAULT 'retiro',
    subtotal        DECIMAL(10,2) NOT NULL,
    descuento       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    isv             DECIMAL(10,2) NOT NULL,
    flete           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total           DECIMAL(10,2) NOT NULL,
    creado_en       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedido_usuario
        FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)   ON DELETE SET NULL,
    CONSTRAINT fk_pedido_sucursal
        FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE SET NULL
);

-- Detalle de cada pedido
CREATE TABLE pedido_detalle (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id       INT UNSIGNED  NOT NULL,
    producto_id     INT UNSIGNED  NULL,
    descripcion     VARCHAR(150)  NOT NULL,
    cantidad        INT UNSIGNED  NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_pedido
        FOREIGN KEY (pedido_id)   REFERENCES pedidos(id)   ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

-- Facturas SAR
CREATE TABLE facturas (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id      INT UNSIGNED NOT NULL UNIQUE,
    nombre_cliente VARCHAR(120) NOT NULL,
    rtn            CHAR(14)     NOT NULL,
    telefono       VARCHAR(15)  NOT NULL,
    fecha          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_factura_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

-- Usuarios (contraseñas guardadas con bcrypt, nunca en texto plano)
--   admin      / Admin123!
--   bodeguero  / Bodega123!
--   cliente    / Cliente123!
INSERT INTO usuarios (nombre, usuario, password_hash, rol) VALUES
('Administrador', 'admin',     '$2y$10$QsnSRhvoO5aFUjQRUPkO8OZFbD5gouYR8C3XIj1WIwP6bsXy9BSXG', 'admin'),
('Bodeguero',     'bodeguero', '$2y$10$cxxHKFbi3AHdMQnrpkhYUuwQO1450HDS7MOpJW4AXWhtBZmET2Fjy', 'bodeguero'),
('Cliente Demo',  'cliente',   '$2y$10$oQewFZspbB451DoHUUIvhumJ2nzoI7OVdAkKeBiawE6Hnq9DZjHzW', 'cliente');

-- Categorias
INSERT INTO categorias (nombre) VALUES
('Frenos y Suspensión'),     -- id 1
('Motor y Transmisión'),     -- id 2
('Sistema Eléctrico'),       -- id 3
('Fluidos y Lubricantes');   -- id 4

-- Marcas vehiculos
INSERT INTO marcas (nombre) VALUES
('Toyota'), ('Nissan'), ('Honda'), ('Hyundai'), ('Isuzu'), ('Ford');

-- Sucursales
INSERT INTO sucursales (nombre, ciudad, zona) VALUES
('Sucursal Toncontín', 'Tegucigalpa',    'Toncontín'),
('Sucursal Suyapa',    'Tegucigalpa',    'Suyapa'),
('Sucursal Medina',    'San Pedro Sula', 'Medina'),
('Sucursal CA-13',     'La Ceiba',       'CA-13');

-- Repuestos destacados del index.
INSERT INTO productos
    (nombre, especificaciones, precio, stock, fotografia, destacado, categoria_id) VALUES
('Pastillas de freno cerámicas',
 'Juego delantero, compuesto cerámico de bajo polvo',
 850.00, 25, 'Pastillas-freno.webp', 1, 1),

('Filtro de aceite',
 'Rosca estándar, para vehículos japoneses y coreanos',
 180.00, 60, 'Filtro-aceite.webp', 1, 2),

('Bujías de iridio (juego)',
 'Juego de 4 bujías de iridio de larga duración',
 1200.00, 15, 'bujillas.jpg', 1, 3),

('Aceite sintético 5W-30',
 'Aceite 100% sintético, envase de 1 cuarto',
 650.00, 40, 'aceite.webp', 1, 4),

('Refrigerante concentrado',
 'Refrigerante para motor, mezclar según indicaciones',
 320.00, 30, 'refrigerante-castrol.jpg', 1, 4);

 -- Opciones del kit Tune-Up
INSERT INTO kit_opciones (tipo, nombre, costo) VALUES
('vehiculo', 'Sedán',        850.00),
('vehiculo', 'SUV',         1100.00),
('vehiculo', 'Pick-Up 4x4', 1350.00),

('marca_aceite', 'Genérico',      0.00),
('marca_aceite', 'Valvoline',   200.00),
('marca_aceite', 'Shell Helix', 220.00),
('marca_aceite', 'Castrol',     250.00),
('marca_aceite', 'Mobil 1',     350.00),

('viscosidad', '0W-20',  0.00),
('viscosidad', '5W-30',  0.00),
('viscosidad', '5W-40',  0.00),
('viscosidad', '10W-30', 0.00),
('viscosidad', '10W-40', 0.00),
('viscosidad', '20W-50', 0.00),

('filtro', 'Filtro de aceite',       150.00),
('filtro', 'Filtro de aire',         180.00),
('filtro', 'Filtro de combustible',  260.00),
('filtro', 'Filtro de cabina (A/C)', 200.00),

('extra', 'Bujías de iridio',        1200.00),
('extra', 'Pastillas cerámicas',      950.00),
('extra', 'Limpiador de inyectores',  280.00),
('extra', 'Líquido de frenos',        220.00),
('extra', 'Refrigerante',             350.00);