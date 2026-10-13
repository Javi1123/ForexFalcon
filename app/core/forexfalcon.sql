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
-- Mejoras (control de actividad / último acceso):
--   - Ultimo_login: DATETIME NULL, se actualiza cada vez que el usuario
--     inicia sesión correctamente.
--   - Ultimo_logout: DATETIME NULL, se actualiza al cerrar sesión (o al
--     expirar la sesión, si lo manejas así).
--   - En_linea: TINYINT(1) DEFAULT 0, bandera rápida para saber si está
--     conectado ahora mismo sin tener que comparar fechas.
--   - Se agregan ambas fechas separadas (no una sola "última actividad")
--     porque login y logout responden preguntas distintas: cuánto duró
--     la sesión, cuándo entró la última vez aunque siga conectado, etc.
--   - Para el historial completo de accesos (no solo el último), ver la
--     tabla Registro_actividad más abajo — estas dos columnas solo
--     guardan el dato más reciente para consultas rápidas sin JOIN.
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
  Ultimo_login DATETIME NULL DEFAULT NULL,
  Ultimo_logout DATETIME NULL DEFAULT NULL,
  En_linea TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (Correo),
  INDEX idx_nombre (Nombre),
  INDEX idx_apellido (Apellido),
  INDEX idx_en_linea (En_linea),
  -- Validación básica de email (no 100% fiable pero ayuda)
  CONSTRAINT chk_correo CHECK (Correo LIKE '%_@__%.__%')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: Registro_actividad
-- Mejoras:
--   - Id_registro: INT AUTO_INCREMENT PK.
--   - Correo: FK a Usuarios(Correo) con ON DELETE CASCADE (si se borra el
--     usuario, se borra su historial; usa RESTRICT en vez de CASCADE si
--     necesitas conservar el log para auditoría aunque el usuario se
--     elimine).
--   - Tipo_evento: ENUM('login','logout') — puedes sumar 'intento_fallido'
--     si luego quieres registrar intentos de acceso con contraseña
--     incorrecta.
--   - Fecha_evento: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Ip_address: VARCHAR(45) (soporta IPv4 e IPv6).
--   - User_agent: TEXT, útil para saber desde qué navegador/dispositivo
--     entró.
--   - Índice compuesto en (Correo, Fecha_evento) para traer rápido el
--     historial de un usuario ordenado por fecha.
--   - Esta tabla es el log completo (cada entrada y salida); las columnas
--     Ultimo_login/Ultimo_logout en Usuarios son solo un "caché" del
--     último evento para no tener que hacer JOIN en cada consulta simple.
-- ------------------------------------------------------------
CREATE TABLE Registro_actividad (
  Id_registro INT AUTO_INCREMENT,
  Correo VARCHAR(100) NOT NULL,
  Tipo_evento ENUM('login','logout') NOT NULL,
  Fecha_evento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Ip_address VARCHAR(45) NULL DEFAULT NULL,
  User_agent TEXT NULL DEFAULT NULL,
  PRIMARY KEY (Id_registro),
  FOREIGN KEY (Correo) REFERENCES Usuarios(Correo)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  INDEX idx_correo_fecha (Correo, Fecha_evento)
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

-- ------------------------------------------------------------
-- Tabla: Chats
-- Mejoras:
--   - Id_chat: INT AUTO_INCREMENT PK.
--   - Correo_usuario: FK a Usuarios(Correo) con ON DELETE CASCADE (si se borra
--     el usuario, se borran sus chats).
--   - Correo_admin: NULL hasta que un admin tome el chat; FK a Usuarios(Correo)
--     con ON DELETE SET NULL (si se borra el admin, el chat no se pierde,
--     solo queda sin asignar).
--   - Estado: ENUM('esperando','activo','cerrado') en vez de TINYINT, para
--     dejar explícitos los tres momentos del ciclo de vida del chat.
--   - Fecha_creacion: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Fecha_asignacion y Fecha_cierre: DATETIME NULL, se completan cuando
--     ocurre cada evento (no al crear la fila).
--   - Índice en Estado para que el lobby de admins filtre rápido los chats
--     en espera.
-- ------------------------------------------------------------
CREATE TABLE Chats (
  Id_chat INT AUTO_INCREMENT,
  Correo_usuario VARCHAR(100) NOT NULL,
  Correo_admin VARCHAR(100) NULL DEFAULT NULL,
  Estado ENUM('esperando','activo','cerrado') NOT NULL DEFAULT 'esperando',
  Fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Fecha_asignacion DATETIME NULL DEFAULT NULL,
  Fecha_cierre DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (Id_chat),
  FOREIGN KEY (Correo_usuario) REFERENCES Usuarios(Correo) ON DELETE CASCADE,
  FOREIGN KEY (Correo_admin) REFERENCES Usuarios(Correo) ON DELETE SET NULL,
  INDEX idx_estado (Estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: Mensajes_chat
-- Mejoras:
--   - Id_mensaje: INT AUTO_INCREMENT PK.
--   - Id_chat: FK a Chats(Id_chat) con ON DELETE CASCADE (si se borra el
--     chat, se borran sus mensajes; evita mensajes huérfanos).
--   - Correo_emisor: VARCHAR(100), no se usa FK aquí para no atarlo a un
--     único rol (puede ser usuario o admin, ver Rol_emisor).
--   - Rol_emisor: ENUM('Usuario','Administrador') para saber quién escribió
--     sin tener que hacer join a Usuarios solo por eso.
--   - Mensaje: TEXT (soporta mensajes largos sin límite de VARCHAR).
--   - Fecha_envio: DATETIME con DEFAULT CURRENT_TIMESTAMP.
--   - Índice en Id_chat para traer el historial de una conversación rápido
--     (ORDER BY Id_mensaje).
-- ------------------------------------------------------------
CREATE TABLE Mensajes_chat (
  Id_mensaje INT AUTO_INCREMENT,
  Id_chat INT NOT NULL,
  Correo_emisor VARCHAR(100) NOT NULL,
  Rol_emisor ENUM('Usuario','Administrador') NOT NULL,
  Mensaje TEXT NOT NULL,
  Fecha_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Id_mensaje),
  FOREIGN KEY (Id_chat) REFERENCES Chats(Id_chat) ON DELETE CASCADE,
  INDEX idx_chat (Id_chat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INSERT USUARIOS ADMINs - 
INSERT INTO Usuarios (Correo, Nombre, Apellido, Telefono, Pais, Rol, Fecha_nacimiento, contraseña) VALUES ('admin@admin.com', 'Admin', '', '+34600000000', 'España', 'Administrador', '1995-04-12', SHA2('Contraseña123$', 256));
INSERT INTO Usuarios (Correo, Nombre, Apellido, Telefono, Pais, Rol, Fecha_nacimiento, contraseña) VALUES ('ana@example.com', 'Ana', 'Lopez', '+34600000000', 'España', 'Usuario', '2000-04-12', SHA2('Contraseña123$', 256));

-- INSERT SERVICIOS - Fatal descripcion del servicio
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("AP", "Análisis premium", "", NOW(), "Mensual", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("BT", "Bots de trading", "", NOW(), "Unico", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("M", "Mentorias", "", NOW(), "Unico", "Si");
INSERT INTO servicios (Id_servicio, Nombre_servicio, Descripcion_servicio, Fecha_creacion, Metodo_pago, Activado) VALUES ("C", "Copytrading", "", NOW(), "Mensual", "Si");