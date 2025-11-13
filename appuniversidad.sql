-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2025 a las 13:12:28
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
-- Base de datos: `appuniversidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` bigint(20) UNSIGNED NOT NULL,..
  `estudiante_id` bigint(20) UNSIGNED DEFAULT NULL,
  `materia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Presente','Ausente','Tarde') NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `inscripcion_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id`, `estudiante_id`, `materia_id`, `fecha`, `estado`, `observaciones`, `inscripcion_id`) VALUES
(1, 1, 1, '2023-10-01', 'Presente', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_carrera` varchar(120) NOT NULL,
  `codigo_carrera` varchar(20) NOT NULL,
  `categoria_id` bigint(20) UNSIGNED DEFAULT NULL,
  `modalidad_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id`, `nombre_carrera`, `codigo_carrera`, `categoria_id`, `modalidad_id`) VALUES
(1, 'Desarrollo de Software', 'DS-1', NULL, NULL),
(2, 'Profesorado de Inglés', 'PI-1', NULL, NULL),
(3, 'TECNICATURA SUPERIOR EN CIENCIA DE DATOS E INTELIGENCIA ARTIFICIAL RES:2730/2', 'TSEC-1', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_categoria` varchar(20) NOT NULL,
  `nombre_categoria` varchar(120) NOT NULL,
  `carrera_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `codigo_categoria`, `nombre_categoria`, `carrera_id`) VALUES
(1, 'CAT-TEC', 'TECNICATURas SUPERIORES', NULL),
(2, 'CAT-PROF', 'PROFESORADOS', NULL),
(3, 'CAT-EDUC', 'EDUCACIÓN', NULL),
(4, 'CAT-SALUD', 'SALUD', NULL),
(5, 'CAT-TECNO', 'TECNOLOGÍA', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas_admin`
--

CREATE TABLE `consultas_admin` (
  `id_consulta` int(11) NOT NULL,
  `email_usuario` varchar(255) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `mensaje` varchar(300) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','en proceso','respondida') DEFAULT 'pendiente',
  `asunto` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `consultas_admin`
--

INSERT INTO `consultas_admin` (`id_consulta`, `email_usuario`, `usuario_id`, `rol_id`, `mensaje`, `fecha_creacion`, `estado`, `asunto`) VALUES
(5, 'eliascampos2949@gmail.com', 2, 2, 'sssssssssssssssssssssssssssssssssss', '2025-11-03 23:00:28', 'respondida', 'sssssssssssss'),
(14, 'eliascampos2949@gmail.com', 3, 3, 'dejame de joder otra vez', '2025-11-04 23:28:00', 'respondida', 'dejame de joder '),
(15, 'eliascamposqqqqqq@gmail.com', 3, 3, 'dejame de joder', '2025-11-08 01:40:40', 'respondida', 'no puedo inscribirme'),
(16, 'eliascampos2949@gmail.com', 3, 3, 'no puedo inscribirme eeeeeeeeeeeeeeeeeeeee', '2025-11-08 01:59:20', 'respondida', 'no puedo inscribirme'),
(17, 'eerererros567@gmail.com', 2, 2, 'no puedo hacer nada', '2025-11-08 02:03:45', 'respondida', 'problemas'),
(18, 'erfedwsdc@gmail.com', 3, 3, 'no puedo hacer nada', '2025-11-08 02:08:15', 'respondida', 'no puedo inscribirme'),
(19, 'eliaaaaaaaaaaaaaa@gmail.com', 3, 3, 'aaaaaaaaaaaaassssssssssssss', '2025-11-08 02:17:28', 'respondida', 'aaaaaaaaaaaaaaaaa'),
(20, 'tgyhujik@gmail.com', 3, 3, 'rrrrrrrrrrrrrrrrrrrrreeeeeeeeeeeeeeeeeee', '2025-11-08 02:26:41', 'respondida', '333333333333333333333'),
(21, 'elia3333333333333@gmail.com', 3, 3, '44444444444444444', '2025-11-08 02:34:48', 'respondida', '333333333333');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dni` char(8) NOT NULL,
  `nombre_estudiante` varchar(80) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` char(2) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `carrera_id` bigint(20) UNSIGNED DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `dni`, `nombre_estudiante`, `fecha_nacimiento`, `edad`, `email`, `carrera_id`) VALUES
(1, '12345678', 'Juan Pérez', '2000-05-10', '24', 'juan@email.com', 1),
(2, '23456789', 'Lucía Fernández', '2001-08-22', '23', 'lucia@email.com', 2),
(3, '32345551', 'lionel messi', '1990-07-12', NULL, 'eliascampos2949@gmail.com', 3),
(4, '22222222', 'fatims lopez maciel', '1990-02-13', NULL, 'faty_90@gmail.com', 3),
(5, '29494781', 'elias campos', '1987-07-22', NULL, 'eliascampos111@gmail.com', 3),
(6, '29494782', 'elias campos', '1996-03-14', NULL, 'eliascampos1111@gmail.com', 3),
(7, '12345679', 'dibu martinez', '1991-06-19', NULL, 'eliascampos1234@gmail.com', 3),
(8, '29494780', 'elias campos', '1982-05-29', NULL, 'eliascampos2949@gmail.com', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `materia_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_inscripcion` date NOT NULL DEFAULT curdate(),
  `estado_inscripcion` enum('Pendiente','Confirmada','Anulada','Aprobada','Reprobada') NOT NULL DEFAULT 'Pendiente',
  `observaciones_inscripcion` varchar(255) DEFAULT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `cupo_asignado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`id`, `estudiante_id`, `materia_id`, `fecha_inscripcion`, `estado_inscripcion`, `observaciones_inscripcion`, `fecha_aprobacion`, `cupo_asignado`) VALUES
(1, 1, 1, '2023-08-01', 'Confirmada', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_materia` varchar(120) NOT NULL,
  `codigo_materia` varchar(20) NOT NULL,
  `carrera_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id`, `nombre_materia`, `codigo_materia`, `carrera_id`) VALUES
(1, 'Programación I', 'PROG-101', 1),
(2, 'Lógica Computacional ', 'LC-1', 3),
(3, 'Administracion y Gestion de Bases de Datos', 'AYGD-1', 3),
(4, 'Elementos de Analisis Matemáticos', 'EDAM-1', 3),
(6, 'Técnicas de Programación', 'TDP-1', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

-- INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
-- (1, '2025-09-27-010101', 'CreateTablaCategoria', '', 'App', 1761416175, 1),
-- (2, '2025-09-27-010102', 'CreateTablaModalidad', '', 'App', 1761416175, 1),
-- (3, '2025-09-27-010103', 'CreateTablaCarrera', '', 'App', 1761416175, 1),
-- (4, '2025-09-27-010104', 'CreateTablaProfesor', '', 'App', 1761416175, 1),
-- (5, '2025-09-27-010105', 'CreateTablaAlumno', '', 'App', 1761416175, 1),
-- (6, '2025-09-27-010106', 'CreateTablaInscripcion', '', 'App', 1761416175, 1),
-- (7, '2025-09-28-000001', 'CreateTablaRol', '', 'App', 1761416175, 1),
-- (8, '2025-09-28-000002', 'CreateTablaUsuarios', '', 'App', 1761416175, 1),
-- (9, '2025-09-27-010101', 'CreateTablaCategoria', '', 'App', 1761417490, 1),
-- (10, '2025-09-27-010102', 'CreateTablaModalidad', '', 'App', 1761417490, 1),
-- (11, '2025-09-27-010103', 'CreateTablaCarrera', '', 'App', 1761417490, 1),
-- (12, '2025-09-27-010104', 'CreateTablaProfesor', '', 'App', 1761417490, 1),
-- (13, '2025-09-27-010105', 'CreateTablaAlumno', '', 'App', 1761417490, 1),
-- (14, '2025-09-27-010106', 'CreateTablaInscripcion', '', 'App', 1761417490, 1),
-- (15, '2025-09-28-000001', 'CreateTablaRol', '', 'App', 1761417490, 1),
-- (16, '2025-09-28-000002', 'CreateTablaUsuarios', '', 'App', 1761417490, 1),
-- (17, '2025-09-27-010101', 'CreateTablaCategoria', '', 'App', 1761418352, 1),
-- (18, '2025-09-27-010102', 'CreateTablaModalidad', '', 'App', 1761418352, 1),
-- (19, '2025-09-27-010103', 'CreateTablaCarrera', '', 'App', 1761418352, 1),
-- (20, '2025-09-27-010104', 'CreateTablaProfesor', '', 'App', 1761418352, 1),
-- (21, '2025-09-27-010105', 'CreateTablaAlumno', '', 'App', 1761418352, 1),
-- (22, '2025-09-27-010106', 'CreateTablaInscripcion', '', 'App', 1761418352, 1),
-- (23, '2025-09-28-000001', 'CreateTablaRol', '', 'App', 1761418352, 1),
-- (24, '2025-09-28-000002', 'CreateTablaUsuarios', '', 'App', 1761418352, 1),
-- (25, '2025-09-27-010101', 'CreateTablaCategoria', '', 'App', 1761431362, 1),
-- (26, '2025-09-27-010102', 'CreateTablaModalidad', '', 'App', 1761431362, 1),
-- (27, '2025-09-27-010103', 'CreateTablaCarrera', '', 'App', 1761431362, 1),
-- (28, '2025-09-27-010104', 'CreateTablaProfesor', '', 'App', 1761431362, 1),
-- (29, '2025-09-27-010105', 'CreateTablaAlumno', '', 'App', 1761431362, 1),
-- (30, '2025-09-27-010106', 'CreateTablaInscripcion', '', 'App', 1761431362, 1),
-- (31, '2025-09-28-000001', 'CreateTablaRol', '', 'App', 1761431362, 1),
-- (32, '2025-09-28-000002', 'CreateTablaUsuarios', '', 'App', 1761431362, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidad`
--

CREATE TABLE `modalidad` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_modalidad` varchar(20) NOT NULL,
  `nombre_modalidad` varchar(120) NOT NULL,
  `carrera_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modalidad`
--

INSERT INTO `modalidad` (`id`, `codigo_modalidad`, `nombre_modalidad`, `carrera_id`) VALUES
(1, 'MOD-PRES', 'PRESENCIAL', NULL),
(2, 'MOD-SEMI', 'SEMIPRESENCIAL', NULL),
(3, 'MOD-VIRT', 'VIRTUAL', NULL),
(4, 'MOD-LIB', 'LIBRE', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota`
--

CREATE TABLE `nota` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED DEFAULT NULL,
  `materia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `calificacion` decimal(4,2) DEFAULT NULL,
  `fecha_evaluacion` date DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `inscripcion_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `nota`
--

INSERT INTO `nota` (`id`, `estudiante_id`, `materia_id`, `calificacion`, `fecha_evaluacion`, `observaciones`, `inscripcion_id`) VALUES
(1, 1, 1, 8.50, '2023-10-15', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `legajo` int(11) NOT NULL,
  `nombre_profesor` varchar(80) NOT NULL,
  `materia_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`id`, `legajo`, `nombre_profesor`, `materia_id`) VALUES
(1, 1001, 'María García', NULL),
(2, 1002, 'Carlos López', NULL),
(3, 1003, 'Carla Carrera', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre_rol`) VALUES
(1, 'admin'),
(3, 'alumno'),
(4, 'Estudiante'),
(2, 'profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ultimo_ingreso` timestamp NULL DEFAULT NULL,
  `rol_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `fecha_registro`, `fecha_ultimo_ingreso`, `rol_id`, `activo`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '2025-11-05 03:27:07', '2025-11-08 05:35:10', 1, 1),
(2, 'profesor1', 'e10adc3949ba59abbe56e057f20f883e', '2025-11-05 03:27:07', '2025-11-08 05:03:18', 2, 1),
(3, 'alumno1', 'e10adc3949ba59abbe56e057f20f883e', '2025-11-05 03:27:07', '2025-11-08 05:34:31', 3, 1),
(4, 'eliascampos2949@gmail.com', '2182c228ca4f32d8ef37e84a13fda0dd', '2025-11-08 02:49:15', '2025-11-08 05:49:46', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_asistencia_estudiante` (`estudiante_id`),
  ADD KEY `fk_asistencia_materia` (`materia_id`),
  ADD KEY `fk_asistencia_inscripcion` (`inscripcion_id`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_carrera` (`codigo_carrera`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_categoria` (`codigo_categoria`),
  ADD KEY `fk_categoria_carrera` (`carrera_id`);

--
-- Indices de la tabla `consultas_admin`
--
ALTER TABLE `consultas_admin`
  ADD PRIMARY KEY (`id_consulta`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `fk_estudiante_carrera` (`carrera_id`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_inscripcion` (`estudiante_id`,`materia_id`),
  ADD KEY `fk_inscripcion_materia` (`materia_id`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_materia` (`codigo_materia`),
  ADD KEY `fk_materia_carrera` (`carrera_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_modalidad` (`codigo_modalidad`),
  ADD KEY `fk_modalidad_carrera` (`carrera_id`);

--
-- Indices de la tabla `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nota_estudiante` (`estudiante_id`),
  ADD KEY `fk_nota_materia` (`materia_id`),
  ADD KEY `fk_nota_inscripcion` (`inscripcion_id`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `legajo` (`legajo`),
  ADD KEY `fk_profesor_materia` (`materia_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_usuario_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `consultas_admin`
--
ALTER TABLE `consultas_admin`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `nota`
--
ALTER TABLE `nota`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_inscripcion` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripcion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_materia` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_categoria_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `fk_inscripcion_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_materia` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `materia`
--
ALTER TABLE `materia`
  ADD CONSTRAINT `fk_materia_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `modalidad`
--
ALTER TABLE `modalidad`
  ADD CONSTRAINT `fk_modalidad_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `fk_nota_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nota_inscripcion` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripcion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nota_materia` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `fk_profesor_materia` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
