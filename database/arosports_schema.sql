-- Arosports Database Schema
CREATE DATABASE IF NOT EXISTS arosports;
USE arosports;

-- Tabla de usuarios con diferentes tipos
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('superadmin', 'club', 'fraccionamiento', 'empresa', 'usuario') NOT NULL DEFAULT 'usuario',
    telefono VARCHAR(20),
    direccion TEXT,
    status ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de clubes
CREATE TABLE clubes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    responsable VARCHAR(100),
    comision_porcentaje DECIMAL(5,2) DEFAULT 10.00,
    status ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de fraccionamientos
CREATE TABLE fraccionamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    responsable VARCHAR(100),
    comision_porcentaje DECIMAL(5,2) DEFAULT 8.00,
    status ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de empresas
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rfc VARCHAR(20),
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    responsable VARCHAR(100),
    comision_porcentaje DECIMAL(5,2) DEFAULT 12.00,
    status ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de reservas (principal para el dashboard financiero)
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_reserva ENUM('club', 'fraccionamiento', 'empresa', 'particular') NOT NULL,
    entidad_id INT NULL, -- ID del club, fraccionamiento o empresa
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    cancha_numero INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    comision DECIMAL(10,2) DEFAULT 0.00,
    descuento DECIMAL(10,2) DEFAULT 0.00,
    precio_final DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'paypal') NOT NULL,
    status_pago ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente',
    status_reserva ENUM('activa', 'completada', 'cancelada') DEFAULT 'activa',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Insertar usuario SuperAdmin
INSERT INTO usuarios (nombre, email, password, tipo_usuario) VALUES 
('SuperAdmin', 'admin@arosports.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin');

-- Insertar datos de ejemplo para clubes
INSERT INTO clubes (nombre, direccion, telefono, email, responsable, comision_porcentaje) VALUES
('Club Deportivo Las Palmas', 'Av. Principal 123, Ciudad', '555-0101', 'info@laspalmas.com', 'Carlos González', 10.00),
('Centro Deportivo Elite', 'Calle Reforma 456, Ciudad', '555-0102', 'contacto@elite.com', 'María López', 12.00),
('Club Raqueta Dorada', 'Blvd. Sports 789, Ciudad', '555-0103', 'admin@raquetadorada.com', 'Juan Pérez', 8.50);

-- Insertar datos de ejemplo para fraccionamientos
INSERT INTO fraccionamientos (nombre, direccion, telefono, email, responsable, comision_porcentaje) VALUES
('Residencial San Miguel', 'Fracc. San Miguel, Ciudad', '555-0201', 'admin@sanmiguel.com', 'Ana Rodríguez', 8.00),
('Fraccionamiento Los Pinos', 'Fracc. Los Pinos, Ciudad', '555-0202', 'info@lospinos.com', 'Roberto Silva', 7.50),
('Villa Hermosa Residencial', 'Fracc. Villa Hermosa, Ciudad', '555-0203', 'contacto@villahermosa.com', 'Laura Martínez', 9.00);

-- Insertar datos de ejemplo para empresas
INSERT INTO empresas (nombre, rfc, direccion, telefono, email, responsable, comision_porcentaje) VALUES
('Corporativo Alpha', 'ALP830101ABC', 'Torre Alpha, Piso 10, Ciudad', '555-0301', 'reservas@alpha.com', 'Diego Ramírez', 12.00),
('Empresa Beta Solutions', 'BET840202DEF', 'Edificio Beta, Centro, Ciudad', '555-0302', 'admin@beta.com', 'Carmen Vega', 15.00),
('Gamma Industries', 'GAM850303GHI', 'Parque Industrial, Ciudad', '555-0303', 'deportes@gamma.com', 'Fernando Castro', 10.50);

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (nombre, email, password, tipo_usuario, telefono) VALUES
('Cliente Particular 1', 'cliente1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', '555-1001'),
('Cliente Particular 2', 'cliente2@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', '555-1002'),
('Cliente Particular 3', 'cliente3@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', '555-1003');

-- Insertar reservas de ejemplo
INSERT INTO reservas (usuario_id, tipo_reserva, entidad_id, fecha_reserva, hora_inicio, hora_fin, cancha_numero, precio_total, comision, descuento, precio_final, metodo_pago, status_pago, status_reserva) VALUES
-- Reservas de clubes
(2, 'club', 1, '2024-01-15', '08:00', '09:30', 1, 500.00, 50.00, 0.00, 500.00, 'tarjeta', 'pagado', 'completada'),
(3, 'club', 1, '2024-01-16', '10:00', '11:30', 2, 500.00, 50.00, 25.00, 475.00, 'efectivo', 'pagado', 'completada'),
(4, 'club', 2, '2024-01-17', '14:00', '15:30', 1, 600.00, 72.00, 0.00, 600.00, 'transferencia', 'pagado', 'completada'),
(2, 'club', 3, '2024-01-18', '16:00', '17:30', 3, 450.00, 38.25, 0.00, 450.00, 'paypal', 'pagado', 'completada'),

-- Reservas de fraccionamientos
(3, 'fraccionamiento', 1, '2024-01-20', '09:00', '10:30', 1, 350.00, 28.00, 0.00, 350.00, 'efectivo', 'pagado', 'completada'),
(4, 'fraccionamiento', 2, '2024-01-21', '11:00', '12:30', 2, 380.00, 28.50, 19.00, 361.00, 'tarjeta', 'pagado', 'completada'),
(2, 'fraccionamiento', 3, '2024-01-22', '15:00', '16:30', 1, 400.00, 36.00, 0.00, 400.00, 'transferencia', 'pagado', 'completada'),

-- Reservas de empresas
(3, 'empresa', 1, '2024-01-25', '08:00', '10:00', 1, 800.00, 96.00, 0.00, 800.00, 'transferencia', 'pagado', 'completada'),
(4, 'empresa', 2, '2024-01-26', '12:00', '14:00', 2, 900.00, 135.00, 45.00, 855.00, 'tarjeta', 'pagado', 'completada'),
(2, 'empresa', 3, '2024-01-27', '16:00', '18:00', 3, 750.00, 78.75, 0.00, 750.00, 'paypal', 'pagado', 'completada'),

-- Reservas particulares
(2, 'particular', NULL, '2024-01-30', '10:00', '11:30', 1, 300.00, 0.00, 0.00, 300.00, 'efectivo', 'pagado', 'completada'),
(3, 'particular', NULL, '2024-01-31', '14:00', '15:30', 2, 300.00, 0.00, 15.00, 285.00, 'tarjeta', 'pagado', 'completada'),
(4, 'particular', NULL, '2024-02-01', '18:00', '19:30', 1, 350.00, 0.00, 0.00, 350.00, 'transferencia', 'pagado', 'completada');

-- Más reservas para reportes (diferentes meses)
INSERT INTO reservas (usuario_id, tipo_reserva, entidad_id, fecha_reserva, hora_inicio, hora_fin, cancha_numero, precio_total, comision, descuento, precio_final, metodo_pago, status_pago, status_reserva) VALUES
-- Febrero 2024
(2, 'club', 1, '2024-02-05', '09:00', '10:30', 1, 500.00, 50.00, 0.00, 500.00, 'tarjeta', 'pagado', 'completada'),
(3, 'empresa', 1, '2024-02-10', '14:00', '16:00', 2, 800.00, 96.00, 0.00, 800.00, 'transferencia', 'pagado', 'completada'),
(4, 'fraccionamiento', 1, '2024-02-15', '11:00', '12:30', 1, 350.00, 28.00, 0.00, 350.00, 'efectivo', 'pagado', 'completada'),
(2, 'particular', NULL, '2024-02-20', '16:00', '17:30', 3, 300.00, 0.00, 0.00, 300.00, 'paypal', 'pagado', 'completada'),

-- Marzo 2024
(3, 'club', 2, '2024-03-05', '08:00', '09:30', 1, 600.00, 72.00, 30.00, 570.00, 'tarjeta', 'pagado', 'completada'),
(4, 'empresa', 2, '2024-03-12', '15:00', '17:00', 2, 900.00, 135.00, 0.00, 900.00, 'transferencia', 'pagado', 'completada'),
(2, 'fraccionamiento', 2, '2024-03-18', '10:00', '11:30', 1, 380.00, 28.50, 0.00, 380.00, 'efectivo', 'pagado', 'completada'),
(3, 'particular', NULL, '2024-03-25', '18:00', '19:30', 3, 350.00, 0.00, 17.50, 332.50, 'tarjeta', 'pagado', 'completada');