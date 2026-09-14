-- ============================================================
-- BASE DE DATOS: ForexFalcon
-- Motor: InnoDB (transacciones + claves foráneas)
-- Charset: utf8mb4 (soporte completo Unicode, emojis)
-- ============================================================

CREATE DATABASE IF NOT EXISTS ForexFalcon
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ForexFalcon;

-- ------------------------------------------------------------
-- Tabla: Correo_descuento
-- Mejoras:
--   - Correo: VARCHAR(100) con CHECK para formato email (opcional, pero se recomienda validación en app).
--   - Porciento_descuento: DECIMAL(5,2) para almacenar 0.00–100.00 (más preciso que INT).
--   - Fecha_creacion: DATETIME con DEFAULT CURRENT_TIMESTAMP (incluye hora).
--   - Activado: TINYINT(1) con DEFAULT 0.
--   - Fecha_activacion: DATETIME (puede ser NULL hasta activación).
--   - Índice en Activado para búsquedas rápidas.
-- ------------------------------------------------------------
CREATE TABLE Correo_descuento (
  Correo VARCHAR(100) NOT NULL,
  Cupon_descuento VARCHAR(50) NOT NULL,
  Nombre_descuento VARCHAR(50) NOT NULL,
  Fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: Usuarios
-- Mejoras:
--   - Contraseña: VARCHAR(64) para almacenar SHA-256 (hash hexadecimal de 64 chars). 
--     En la aplicación usar SHA2(contraseña, 256) o mejor bcrypt (recomendación en comentarios).
--   - Teléfono: VARCHAR(20) en lugar de NUMBER (los números pueden tener prefijos +, espacios).
--   - Rol: ENUM('Administrador','Superusuario','Usuario') con valor por defecto 'Usuario'.
--   - Fecha_nacimiento: DATE (solo fecha, sin hora).
--   - Fecha_creacion: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Índices en Nombre, Apellido para búsquedas.
--   - CHECK para formato de email (validación adicional).
-- ------------------------------------------------------------
CREATE TABLE Usuarios (
  Correo VARCHAR(100) NOT NULL,
  Nombre VARCHAR(50) NOT NULL,
  Apellido VARCHAR(50) NOT NULL,
  Telefono VARCHAR(20) NOT NULL,          -- Para soportar +34 600 000 000
  Pais VARCHAR(50) NOT NULL,
  Rol ENUM('Administrador','Superusuario','Usuario') NOT NULL DEFAULT 'Usuario',
  Fecha_nacimiento DATE NOT NULL,
  contraseña VARCHAR(64) NOT NULL,         -- Hash SHA-256 (64 hex) / o usar CHAR(64)
  Fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Correo),
  INDEX idx_nombre (Nombre),
  INDEX idx_apellido (Apellido),
  -- Validación básica de email (no 100% fiable pero ayuda)
  CONSTRAINT chk_correo CHECK (Correo LIKE '%_@__%.__%')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: Servicios
-- Mejoras:
--   - Id_servicio: VARCHAR(20) PK, podría ser un código corto (ej. 'BASIC', 'PREMIUM').
--   - Nombre_servicio: VARCHAR(100) NOT NULL.
--   - Descripcion_servicio: TEXT (para descripciones largas).
--   - Fecha_creacion: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Metodo_pago: ENUM('Mensual','Semanal','Anual') con valor por defecto 'Mensual'.
--   - Activado: TINYINT(1) NOT NULL DEFAULT 1.
-- ------------------------------------------------------------
CREATE TABLE Servicios (
  Id_servicio VARCHAR(20) NOT NULL,
  Nombre_servicio VARCHAR(100) NOT NULL,
  Descripcion_servicio TEXT NOT NULL,
  Fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Metodo_pago ENUM('Unico','Mensual','Semanal','Anual') NOT NULL DEFAULT 'Mensual',
  Activado TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (Id_servicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: Suscripciones
-- Mejoras:
--   - Id_suscripciones: INT AUTO_INCREMENT PK.
--   - Activado: TINYINT(1) NOT NULL DEFAULT 1.
--   - Fecha_inicio y Fecha_fin: DATETIME (incluyen hora).
--   - Claves foráneas con ON DELETE RESTRICT (evita borrar servicios o usuarios con suscripciones activas).
--   - Índice compuesto en (Correo, Id_servicio) para búsquedas rápidas.
--   - Trigger para actualizar Fecha_fin al desactivar (ver más abajo).
-- ------------------------------------------------------------
CREATE TABLE Suscripciones (
  Id_suscripciones INT AUTO_INCREMENT,
  Activado TINYINT(1) NOT NULL DEFAULT 1,
  Fecha_inicio DATETIME NOT NULL,
  Fecha_fin DATETIME NULL DEFAULT NULL,
  Id_servicio VARCHAR(20) NOT NULL,
  Correo VARCHAR(100) NOT NULL,
  PRIMARY KEY (Id_suscripciones),
  FOREIGN KEY (Id_servicio) REFERENCES Servicios(Id_servicio)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY (Correo) REFERENCES Usuarios(Correo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  INDEX idx_usuario_servicio (Correo, Id_servicio),
  INDEX idx_activado (Activado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Trigger: Al desactivar una suscripción (Activado = 0), 
--           actualiza Fecha_fin al momento exacto del cambio.
-- ------------------------------------------------------------
DELIMITER //
CREATE TRIGGER trg_suscripcion_desactivar
BEFORE UPDATE ON Suscripciones
FOR EACH ROW
BEGIN
  IF NEW.Activado = 0 AND OLD.Activado = 1 THEN
    SET NEW.Fecha_fin = NOW();
  END IF;
END//
DELIMITER ;

-- ------------------------------------------------------------
-- Tabla: Videos
-- Mejoras:
--   - Id: INT AUTO_INCREMENT PK.
--   - Nombre_video: VARCHAR(200) NOT NULL.
--   - Duracion_segundos: INT UNSIGNED (no negativo).
--   - Video_url: TEXT (o VARCHAR(2048) si prefieres, pero TEXT es seguro para URLs largas).
--   - Fecha_subida: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Id_servicios: FK a Servicios con ON DELETE CASCADE (si se borra servicio, se borran sus videos) 
--     o RESTRICT según prefieras; pongo CASCADE para mantener limpieza.
--   - Índice en Id_servicios para joins rápidos.
-- ------------------------------------------------------------
CREATE TABLE Videos (
  Id INT AUTO_INCREMENT,
  Nombre_video VARCHAR(200) NOT NULL,
  Duracion_segundos INT UNSIGNED NOT NULL,
  Video_url TEXT NOT NULL,
  Fecha_subida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Id_servicios VARCHAR(20) NOT NULL,
  PRIMARY KEY (Id),
  FOREIGN KEY (Id_servicios) REFERENCES Servicios(Id_servicio)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  INDEX idx_servicio (Id_servicios)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INSERT SERVICIOS - Fatal descripcion del servicio
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("AP", "Análisis premium", "", NOW(), "Mensual", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("BT", "Bots de trading", "", NOW(), "Unico", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("M", "Mentorias", "", NOW(), "Unico", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("C", "Copytrading", "", NOW(), "Mensual", "Si");