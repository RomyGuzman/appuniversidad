


--BASE DE DATOS APPUNIVERSIDAD


CREATE DATABASE IF NOT EXISTS 
appuniversidad CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
USE appuniversidad;


-- ==========================
-- 1️⃣ TABLAS BASE
-- ==========================

-- Carrera
CREATE TABLE Carrera (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_carrera VARCHAR(120) NOT NULL,
  codigo_carrera VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;



-- Rol (independiente)
CREATE TABLE Rol (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;



-- Usuarios (depende de Rol) - CON CAMPOS DE FECHAS INTEGRADOS
CREATE TABLE Usuarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_ultimo_ingreso TIMESTAMP NULL,
  rol_id BIGINT UNSIGNED,
  activo BOOLEAN DEFAULT TRUE,
  CONSTRAINT fk_usuario_rol FOREIGN KEY (rol_id) REFERENCES Rol(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;


-- Estudiante (depende de Carrera)
CREATE TABLE Estudiante (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dni CHAR(8) NOT NULL UNIQUE,
  nombre_estudiante VARCHAR(80) NOT NULL,
  fecha_nacimiento DATE,
  edad CHAR(2),
  email VARCHAR(50) NOT NULL,
  carrera_id BIGINT UNSIGNED,
  CONSTRAINT fk_estudiante_carrera FOREIGN KEY (carrera_id) REFERENCES Carrera(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT chk_dni_valido CHECK (dni REGEXP '^[0-9]{8}$')
) ENGINE=InnoDB;




-- Profesor (depende de Carrera)
CREATE TABLE Profesor (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  legajo INT NOT NULL UNIQUE,
  nombre_profesor VARCHAR(80) NOT NULL,
  carrera_id BIGINT UNSIGNED,
  CONSTRAINT fk_profesor_carrera FOREIGN KEY (carrera_id) REFERENCES Carrera(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;



-- Categoria (depende de Carrera)
CREATE TABLE Categoria (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo_categoria VARCHAR(20) NOT NULL UNIQUE,
  nombre_categoria VARCHAR(120) NOT NULL,
  carrera_id BIGINT UNSIGNED,
  CONSTRAINT fk_categoria_carrera FOREIGN KEY (carrera_id) REFERENCES Carrera(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;



-- Modalidad (depende de Carrera)
CREATE TABLE Modalidad (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo_modalidad VARCHAR(20) NOT NULL UNIQUE,
  nombre_modalidad VARCHAR(120) NOT NULL,
  carrera_id BIGINT UNSIGNED,
  CONSTRAINT fk_modalidad_carrera FOREIGN KEY (carrera_id) REFERENCES Carrera(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;


-- ==========================
-- 2️⃣ TABLAS ACADÉMICAS
-- ==========================

-- Materia (depende de Carrera)
CREATE TABLE Materia (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_materia VARCHAR(120) NOT NULL,
  codigo_materia VARCHAR(20) NOT NULL UNIQUE,
  carrera_id BIGINT UNSIGNED,
  CONSTRAINT fk_materia_carrera FOREIGN KEY (carrera_id) REFERENCES Carrera(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;


-- Inscripcion (reemplaza Estudiante_Materia; depende de Estudiante y Materia)
CREATE TABLE Inscripcion (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  estudiante_id BIGINT UNSIGNED NOT NULL,
  materia_id BIGINT UNSIGNED NOT NULL,
  fecha_inscripcion DATE NOT NULL DEFAULT CURRENT_DATE,
  estado_inscripcion ENUM('Pendiente', 'Confirmada', 'Anulada', 'Aprobada', 'Reprobada') NOT NULL DEFAULT 'Pendiente',
  observaciones_inscripcion VARCHAR(255),
  fecha_aprobacion DATE,
  cupo_asignado INT DEFAULT 1,
  UNIQUE KEY unique_inscripcion (estudiante_id, materia_id),
  CONSTRAINT fk_inscripcion_estudiante FOREIGN KEY (estudiante_id) REFERENCES Estudiante(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_inscripcion_materia FOREIGN KEY (materia_id) REFERENCES Materia(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;


-- Profesor_Materia (depende de Profesor y Materia)
CREATE TABLE Profesor_Materia (
  profesor_id BIGINT UNSIGNED,
  materia_id BIGINT UNSIGNED,
  PRIMARY KEY (profesor_id, materia_id),
  CONSTRAINT fk_profesor_materia_profesor FOREIGN KEY (profesor_id) REFERENCES Profesor(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_profesor_materia_materia FOREIGN KEY (materia_id) REFERENCES Materia(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;




-- Nota (depende de Estudiante y Materia)
CREATE TABLE Nota (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  estudiante_id BIGINT UNSIGNED,
  materia_id BIGINT UNSIGNED,
  calificacion DECIMAL(4,2),
  fecha_evaluacion DATE,
  observaciones VARCHAR(255),
  CONSTRAINT fk_nota_estudiante FOREIGN KEY (estudiante_id) REFERENCES Estudiante(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_nota_materia FOREIGN KEY (materia_id) REFERENCES Materia(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;




-- Asistencia (depende de Estudiante y Materia)
CREATE TABLE Asistencia (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  estudiante_id BIGINT UNSIGNED,
  materia_id BIGINT UNSIGNED,
  fecha DATE NOT NULL,
  estado ENUM('Presente','Ausente','Tarde') NOT NULL,
  observaciones VARCHAR(255),
  CONSTRAINT fk_asistencia_estudiante FOREIGN KEY (estudiante_id) REFERENCES Estudiante(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_asistencia_materia FOREIGN KEY (materia_id) REFERENCES Materia(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;




-- ==========================
-- 3️⃣ MODIFICACIONES ADICIONALES (ALTER TABLE) - SOLO PARA CAMPOS OPCIONALES
-- ==========================

-- Agregar campos opcionales en Nota y Asistencia para referenciar Inscripcion
ALTER TABLE nota ADD COLUMN inscripcion_id BIGINT UNSIGNED NULL;
ALTER TABLE nota ADD CONSTRAINT fk_nota_inscripcion FOREIGN KEY (inscripcion_id) REFERENCES Inscripcion(id)
  ON UPDATE CASCADE ON DELETE SET NULL;
  
  
  
ALTER TABLE asistencia ADD COLUMN inscripcion_id BIGINT UNSIGNED NULL;
ALTER TABLE asistencia ADD CONSTRAINT fk_asistencia_inscripcion FOREIGN KEY (inscripcion_id) REFERENCES Inscripcion(id)
  ON UPDATE CASCADE ON DELETE SET NULL;
  
  
-- ==========================
-- 4️⃣ DATOS INICIALES
-- ==========================

-- Insertar roles
INSERT INTO Rol (nombre_rol) VALUES
('Superadmin'),
('Administrador'),
('Profesor'),
('Estudiante');


-- Insertar usuarios de ejemplo (contraseñas hasheadas en producción; fecha_registro se auto-asigna)
INSERT INTO Usuarios (usuario, password, rol_id) VALUES
('superadmin', 'hash_superadmin', 1),
('admin', 'hash_admin', 2),
('profesor_demo', 'hash_profesor', 3),
('alumno_demo', 'hash_alumno', 4);



-- Datos de ejemplo para probar (Carrera, Estudiante, Materia, Inscripcion)
INSERT INTO Carrera (nombre_carrera, codigo_carrera) VALUES
('Ingeniería en Sistemas', 'ING-SIS');

-- INSERTAR UN ESTUDIANTES PARA INICIALIZAR LA TABLA
INSERT INTO Estudiante (dni, nombre_estudiante, fecha_nacimiento, edad, email, carrera_id) VALUES
('12345678', 'Juan Pérez', '2000-05-15', '23', 'juan@example.com', 1);




-- INSERTAR UN PROFESOR PARA INICIALIZAR LA TABLA
INSERT INTO Profesor (legajo, nombre_profesor, carrera_id) VALUES
(1001, 'María García', 1);



-- INSERTAR UNA MATERIA PARA INICIALIZAR LA TABLA
INSERT INTO Materia (nombre_materia, codigo_materia, carrera_id) VALUES
('Programación I', 'PROG-101', 1);


-- Asignar profesor a materia
INSERT INTO Profesor_Materia (profesor_id, materia_id) VALUES
(1, 1);


-- Inscripcion de ejemplo
INSERT INTO Inscripcion (estudiante_id, materia_id, fecha_inscripcion, estado_inscripcion) VALUES
(1, 1, '2023-08-01', 'Confirmada');


-- Nota de ejemplo
INSERT INTO Nota (estudiante_id, materia_id, calificacion, fecha_evaluacion, inscripcion_id) VALUES
(1, 1, 8.50, '2023-10-15', 1);



-- Asistencia de ejemplo
INSERT INTO Asistencia (estudiante_id, materia_id, fecha, estado, inscripcion_id) VALUES
(1, 1, '2023-10-01', 'Presente', 1);


 -- Nuevo campo para el asunto, con hasta 80 caracteres
CREATE TABLE consultas_admin (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    email_usuario VARCHAR(255) NOT NULL,
    mensaje VARCHAR(300) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'en proceso', 'respondida') DEFAULT 'pendiente',
    asunto VARCHAR(80) NOT NULL 
);

--4 modalidades asociadas a la carrera con id = 1 (Ingeniería en Sistemas).
INSERT INTO Modalidad (codigo_modalidad, nombre_modalidad, carrera_id) VALUES
('MOD-PRE', 'Presencial', 1),
('MOD-SEMI', 'Semipresencial', 1),
('MOD-VIRT', 'Virtual', 1),
('MOD-LIB', 'Libre', 1);




-- modifica el cajón grande llamado 'consultas_admin'"
ALTER TABLE `consultas_admin`

-- "Añade un nuevo cajón llamado 'usuario_id' para guardar números. Ponlo justo después del cajón 'email_usuario'"
ADD COLUMN `usuario_id` INT(11) NULL AFTER `email_usuario`,

-- "Y añade otro cajón llamado 'rol_id' para guardar números. Ponlo justo después del que acabas de crear"
ADD COLUMN `rol_id` INT(11) NULL AFTER `usuario_id`;



ALTER TABLE Carrera ADD COLUMN categoria_id BIGINT UNSIGNED NULL;
ALTER TABLE Carrera ADD COLUMN modalidad_id BIGINT UNSIGNED NULL;

