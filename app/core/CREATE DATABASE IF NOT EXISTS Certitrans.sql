CREATE DATABASE IF NOT EXISTS Certitransporte
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE Certitransporte;

CREATE TABLE IF NOT EXISTS Sugerencias (
    ID_sugerencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NULL,
    email VARCHAR(255) NULL,
    telefono VARCHAR(20) NULL,
    sugerencia TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Administradores (
    ID_admin INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    nombre_completo VARCHAR(100) NULL,
    contrasena VARCHAR(255) NOT NULL,
    ultimo_acceso TIMESTAMP NULL DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Cursos (
    claveCurso VARCHAR(5) PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL,
    precioMes INT NULL,
    matricula INT NULL,
    baja ENUM('Si', 'No') DEFAULT "No",
    notas TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Alumnos (
    DNI VARCHAR(9) NOT NULL PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(50) NOT NULL,
    fechaNacimiento VARCHAR(10) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    usuario VARCHAR(20) NULL,
    contrasena VARCHAR(255) NULL,
    CCC VARCHAR(255) NULL,
    matricula ENUM('Pendiente', 'Promoción', 'Precio pagado') DEFAULT "Pendiente"
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Matriculados (
    DNI VARCHAR(9),
    claveCurso VARCHAR(5),
    fechaInscripcion VARCHAR(50) NOT NULL,
    fechaFinal VARCHAR(50) NOT NULL,
    certificado ENUM('Si', 'No') DEFAULT "No",
    alta ENUM('Si', 'No') DEFAULT "Si",
    notas TEXT NULL,
    
    PRIMARY KEY (DNI, claveCurso),

    FOREIGN KEY (DNI) REFERENCES Alumnos(DNI) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (claveCurso) REFERENCES Cursos(claveCurso) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Recibo (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    fechaPago DATE NULL,
    DNI VARCHAR(9),
    claveCurso VARCHAR(5),
    estado ENUM('Pagado', 'No pagado') DEFAULT "No pagado",
    cantidad INT NULL,
    
    FOREIGN KEY (DNI, claveCurso) REFERENCES Matriculados(DNI, claveCurso) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TRIGGER: Sincronizar baja de cursos con alta de matriculados
-- =====================================================
DELIMITER $$

CREATE TRIGGER actualizar_alta_matriculados
AFTER UPDATE ON Cursos
FOR EACH ROW
BEGIN
    IF NEW.baja = 'Si' THEN
        UPDATE Matriculados SET alta = 'No' WHERE claveCurso = NEW.claveCurso;
    END IF;
    
    IF NEW.baja = 'No' AND OLD.baja = 'Si' THEN
        UPDATE Matriculados SET alta = 'Si' WHERE claveCurso = NEW.claveCurso;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- 1. INSERTAR CURSOS (con valores ENUM correctos)
-- =====================================================
INSERT INTO Cursos (claveCurso, descripcion, precioMes, matricula, baja) VALUES 
('M', 'Mercacias', 100, 100, 'No'),
('V', 'Viajeros', 100, 100, 'No'),
('V/M', 'Viajeros y mercancias', 100, 100, 'No');

-- =====================================================
-- 2. INSERTAR ADMINISTRADORES
-- =====================================================
INSERT INTO Administradores (ID_admin, usuario, nombre_completo, contrasena) 
VALUES (1, 'Admin', 'Administrador', SHA2('1234', 256));

-- =====================================================
-- 3. INSERTAR SUGERENCIAS
-- =====================================================
INSERT INTO Sugerencias (nombre, apellido, email, telefono, sugerencia) VALUES
('Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '612345678', 'Excelente servicio de transporte, los conductores son muy profesionales y puntuales. Me gustaría sugerir que implementen una aplicación móvil para hacer seguimiento en tiempo real.'),
('María', 'González', 'maria.gonzalez@email.com', '623456789', 'Los vehículos están impecables, pero sería bueno que agregaran más puntos de recogida en zonas rurales.'),
('José', 'López', 'jose.lopez@email.com', '634567890', 'Sugiero implementar un sistema de fidelización con descuentos para clientes frecuentes.'),
('Ana', 'Martínez', NULL, '645678901', 'El proceso de reserva online es muy sencillo. Solo sugeriría que el horario de atención al cliente se extienda hasta las 8pm.'),
('Luis', 'Fernández', 'luis.fernandez@email.com', '656789012', 'Los precios son competitivos, pero creo que podrían ofrecer tarifas especiales para estudiantes.'),
('Elena', 'Sánchez', 'elena.sanchez@email.com', '667890123', 'El servicio al cliente es excelente. Sugiero agregar un chat en vivo en la página web.'),
('Roberto', 'Díaz', 'roberto.diaz@email.com', '678901234', 'Sería bueno reforzar la política de imagen corporativa con uniformes completos.'),
('Patricia', 'Romero', NULL, '689012345', 'El proceso de pago con tarjeta a veces falla. Sugiero revisar la integración con la pasarela de pagos.'),
('Miguel', 'Torres', 'miguel.torres@email.com', '690123456', 'Sugiero implementar una encuesta de satisfacción después de cada viaje.'),
('Carmen', 'Ruiz', 'carmen.ruiz@email.com', '701234567', 'Sería genial que ofrecieran servicio de Wi-Fi a bordo para los pasajeros.');

-- =====================================================
-- 4. INSERTAR ALUMNOS
-- =====================================================
INSERT INTO Alumnos (DNI, nombre, apellido, correo, telefono, direccion, fechaNacimiento, usuario, contrasena, CCC, matricula) VALUES
('12345678A', 'Juan', 'Pérez García', 'juan.perez@email.com', '611111111', 'C/ Mayor 1, Madrid', '1990-05-15', 'juanpg', SHA2('juan123', 256), 'ES12345678901234567890', 'Pendiente'),
('87654321B', 'María', 'López Ruiz', 'maria.lopez@email.com', '622222222', 'Avda. Libertad 23, Barcelona', '1985-08-22', 'marialr', SHA2('maria456', 256), 'ES09876543210987654321', 'Pendiente'),
('11111111C', 'Carlos', 'Sánchez Díaz', 'carlos.sanchez@email.com', '633333333', 'C/ Sol 45, Valencia', '1995-03-10', 'carlos_s', SHA2('carlos789', 256), 'ES55555555555555555555', 'Pendiente'),
('22222222D', 'Ana', 'González Martín', 'ana.gonzalez@email.com', '644444444', 'Plaza España 7, Sevilla', '1988-11-30', 'agonzalez', SHA2('ana123', 256), 'ES44444444444444444444', 'Promoción'),
('33333333E', 'Luis', 'Fernández Romero', 'luis.fernandez@email.com', '655555555', 'C/ Luna 12, Bilbao', '1992-07-19', 'luisfr', SHA2('luis789', 256), 'ES33333333333333333333', 'Precio pagado'),
('44444444F', 'Laura', 'Martínez Serrano', 'laura.martinez@email.com', '666666666', 'Avda. Constitución 34, Zaragoza', '1987-09-25', 'laurams', SHA2('laura123', 256), 'ES22222222222222222222', 'Precio pagado'),
('55555555G', 'Pedro', 'Ramírez Gómez', 'pedro.ramirez@email.com', '677777777', 'C/ Río 56, Málaga', '1993-01-14', 'pedrorg', SHA2('pedro456', 256), 'ES11111111111111111111', 'Promoción'),
('66666666H', 'Sofia', 'Herrera Vega', 'sofia.herrera@email.com', '688888888', 'Plaza Mayor 8, Murcia', '1991-04-03', 'sofiahv', SHA2('sofia789', 256), 'ES66666666666666666666', 'Pendiente'),
('77777777I', 'Javier', 'Torres Ortiz', 'javier.torres@email.com', '699999999', 'C/ Soledad 22, Palma', '1989-12-17', 'javier_to', SHA2('javier123', 256), 'ES77777777777777777777', 'Precio pagado'),
('88888888J', 'Elena', 'Ruiz Jiménez', 'elena.ruiz@email.com', '600000000', 'Avda. Paz 15, Las Palmas', '1994-06-28', 'elenarj', SHA2('elena456', 256), 'ES88888888888888888888', 'Promoción');

-- =====================================================
-- 5. INSERTAR MATRICULADOS (con valores ENUM correctos)
-- =====================================================
INSERT INTO Matriculados (DNI, claveCurso, fechaInscripcion, fechaFinal, certificado, alta, notas) VALUES
('12345678A', 'M', '2024-01-15', '2024-06-15', 'No', 'No', 'Alumno puntual, buen rendimiento'),
('12345678A', 'V', '2024-09-01', '2025-02-01', 'No', 'No', 'Nuevo curso de viajeros'),
('87654321B', 'V', '2024-01-20', '2024-06-20', 'Si', 'Si', 'Excelente alumno, certificado entregado'),
('11111111C', 'V/M', '2024-02-01', '2024-07-01', 'No', 'No', 'Rendimiento destacado'),
('22222222D', 'M', '2024-02-10', '2024-07-10', 'Si', 'Si', 'Certificado expedido'),
('33333333E', 'V', '2024-03-01', '2024-08-01', 'No', 'No', 'Necesita refuerzo en algunas áreas'),
('44444444F', 'V/M', '2024-03-15', '2024-08-15', 'No', 'No', 'Buen progreso'),
('66666666H', 'M', '2024-04-10', '2024-09-10', 'No', 'No', 'Falta algún día, pero cumple'),
('66666666H', 'V', '2024-11-01', '2025-04-01', 'No', 'No', 'Curso suspendido por baja'),
('88888888J', 'V/M', '2024-05-15', '2024-10-15', 'No', 'No', 'Alumno nuevo, buena actitud');

-- =====================================================
-- 6. INSERTAR RECIBOS (con valores ENUM correctos)
-- =====================================================
INSERT INTO Recibo (fechaPago, DNI, claveCurso, estado, cantidad) VALUES
-- Pagos de Juan Pérez (DNI: 12345678A) - Curso M
('2024-01-15', '12345678A', 'M', 'Pagado', 200),
('2024-02-15', '12345678A', 'M', 'Pagado', 100),
('2024-03-15', '12345678A', 'M', 'Pagado', 100),
('2024-04-15', '12345678A', 'M', 'Pagado', 100),
-- Pagos de Juan Pérez - Curso V
('2024-09-01', '12345678A', 'V', 'Pagado', 200),
('2024-10-01', '12345678A', 'V', 'Pagado', 100),

-- Pagos de María López (DNI: 87654321B) - Curso V
('2024-01-20', '87654321B', 'V', 'Pagado', 200),
('2024-02-20', '87654321B', 'V', 'Pagado', 100),
('2024-03-20', '87654321B', 'V', 'Pagado', 100),
('2024-04-20', '87654321B', 'V', 'Pagado', 100),
('2024-05-20', '87654321B', 'V', 'Pagado', 100),

-- Pagos de Carlos Sánchez (DNI: 11111111C) - Curso V/M
('2024-02-01', '11111111C', 'V/M', 'Pagado', 200),
('2024-03-01', '11111111C', 'V/M', 'Pagado', 100),
('2024-04-01', '11111111C', 'V/M', 'Pagado', 100),
('2024-05-01', '11111111C', 'V/M', 'Pagado', 100),

-- Pagos de Ana González (DNI: 22222222D) - Curso M
('2024-02-10', '22222222D', 'M', 'Pagado', 200),
('2024-03-10', '22222222D', 'M', 'Pagado', 100),
('2024-04-10', '22222222D', 'M', 'Pagado', 100),
('2024-05-10', '22222222D', 'M', 'Pagado', 100),
('2024-06-10', '22222222D', 'M', 'Pagado', 100),

-- Pagos de Luis Fernández (DNI: 33333333E) - Curso V
('2024-03-01', '33333333E', 'V', 'Pagado', 200),
('2024-04-01', '33333333E', 'V', 'No pagado', 100),
('2024-05-01', '33333333E', 'V', 'No pagado', 100),

-- Pagos de Laura Martínez (DNI: 44444444F) - Curso V/M
('2024-03-15', '44444444F', 'V/M', 'Pagado', 200),
('2024-04-15', '44444444F', 'V/M', 'Pagado', 100),
('2024-05-15', '44444444F', 'V/M', 'Pagado', 100),

-- Pagos de Sofia Herrera (DNI: 66666666H) - Curso M
('2024-04-10', '66666666H', 'M', 'Pagado', 200),
('2024-05-10', '66666666H', 'M', 'Pagado', 100),
('2024-06-10', '66666666H', 'M', 'Pagado', 100),

-- Pagos de Elena Ruiz (DNI: 88888888J) - Curso V/M
('2024-05-15', '88888888J', 'V/M', 'Pagado', 200),
('2024-06-15', '88888888J', 'V/M', 'Pagado', 100),
('2024-07-15', '88888888J', 'V/M', 'Pagado', 100);