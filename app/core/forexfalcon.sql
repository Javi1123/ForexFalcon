-- ============================================================
--  TRADING PLATFORM — Orden correcto
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. PRIMERO: Usuarios (no tiene FK a nadie)
CREATE TABLE usuarios (
    id              INT             NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(150)    NOT NULL,
    email           VARCHAR(255)    NOT NULL,
    password_hash   TEXT            NOT NULL,
    rol             VARCHAR(30)     NOT NULL DEFAULT 'alumno',
    creado_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_email (email)
);

-- 2. Imagenes (sus FK pueden ser NULL por ahora)
CREATE TABLE imagenes (
    id              INT             NOT NULL AUTO_INCREMENT,
    url             TEXT            NOT NULL,
    alt_text        VARCHAR(255)    NULL,
    tipo            VARCHAR(50)     NULL,
    referencia_id   INT             NULL,
    referencia_tipo VARCHAR(50)     NULL,
    subida_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_imagenes_referencia (referencia_id, referencia_tipo)
);

-- 3. Cursos (depende de usuarios e imagenes)
CREATE TABLE cursos (
    id              INT             NOT NULL AUTO_INCREMENT,
    instructor_id   INT             NOT NULL,
    titulo          VARCHAR(255)    NOT NULL,
    slug            VARCHAR(255)    NOT NULL,
    nivel           VARCHAR(30)     NOT NULL DEFAULT 'principiante',
    categoria       VARCHAR(100)    NULL,
    es_premium      TINYINT(1)      NOT NULL DEFAULT 0,
    imagen_id       INT             NULL,
    publicado_en    DATETIME        NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cursos_slug (slug),
    CONSTRAINT fk_cur_instructor FOREIGN KEY (instructor_id) REFERENCES usuarios (id) ON DELETE RESTRICT,
    CONSTRAINT fk_cur_imagen     FOREIGN KEY (imagen_id)     REFERENCES imagenes (id) ON DELETE SET NULL
);

-- 4. Videos (depende de cursos e imagenes)
CREATE TABLE videos (
    id                  INT             NOT NULL AUTO_INCREMENT,
    curso_id            INT             NOT NULL,
    titulo              VARCHAR(255)    NOT NULL,
    orden               SMALLINT        NOT NULL DEFAULT 1,
    duracion_segundos   INT             NULL,
    video_url           TEXT            NOT NULL,
    es_preview          TINYINT(1)      NOT NULL DEFAULT 0,
    imagen_id           INT             NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_vid_curso  FOREIGN KEY (curso_id)  REFERENCES cursos  (id) ON DELETE CASCADE,
    CONSTRAINT fk_vid_imagen FOREIGN KEY (imagen_id) REFERENCES imagenes (id) ON DELETE SET NULL
);

-- 5. Inscripciones (depende de usuarios y cursos)
CREATE TABLE inscripciones (
    id              INT             NOT NULL AUTO_INCREMENT,
    usuario_id      INT             NOT NULL,
    curso_id        INT             NOT NULL,
    progreso_pct    DECIMAL(5,2)    NOT NULL DEFAULT 0.00,
    inscrito_en     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_inscripcion (usuario_id, curso_id),
    CONSTRAINT fk_ins_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    CONSTRAINT fk_ins_curso   FOREIGN KEY (curso_id)   REFERENCES cursos  (id) ON DELETE CASCADE
);

-- 6. Suscripciones (depende de usuarios)
CREATE TABLE suscripciones (
    id              INT             NOT NULL AUTO_INCREMENT,
    usuario_id      INT             NOT NULL,
    plan            VARCHAR(50)     NOT NULL,
    estado          VARCHAR(30)     NOT NULL DEFAULT 'activa',
    precio_pagado   DECIMAL(10,2)   NULL,
    cupon_aplicado  VARCHAR(50)     NULL,
    inicio          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fin             DATETIME        NULL,
    creado_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_sus_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
);

-- 7. ÚLTIMA: Correo_descuento (depende de usuarios)
CREATE TABLE correo_descuento (
    id              INT             NOT NULL AUTO_INCREMENT,
    email           VARCHAR(255)    NOT NULL,
    cupon_codigo    VARCHAR(50)     NOT NULL,
    descuento_pct   TINYINT         NOT NULL DEFAULT 10,
    usado           TINYINT(1)      NOT NULL DEFAULT 0,
    usuario_id      INT             NULL,
    creado_en       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usado_en        DATETIME        NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cd_email       (email),
    UNIQUE KEY uq_cd_cupon       (cupon_codigo),
    CONSTRAINT fk_cd_usuario     FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE SET NULL
);

-- ÍNDICES
CREATE INDEX idx_suscripciones_usuario  ON suscripciones (usuario_id);
CREATE INDEX idx_inscripciones_usuario  ON inscripciones (usuario_id);
CREATE INDEX idx_inscripciones_curso    ON inscripciones (curso_id);
CREATE INDEX idx_videos_curso_orden     ON videos (curso_id, orden);
CREATE INDEX idx_correo_descuento_email ON correo_descuento (email);

SET FOREIGN_KEY_CHECKS = 1;