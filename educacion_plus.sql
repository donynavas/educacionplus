-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2026 a las 21:57:58
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
-- Base de datos: `educacion_plus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_actividad`
--

CREATE TABLE `tbl_actividad` (
  `id` int(11) NOT NULL,
  `id_asignacion_docente` int(11) DEFAULT NULL,
  `tipo` enum('tarea','examen','foro','recurso','laboratorio') DEFAULT NULL,
  `contenido` text NOT NULL,
  `url_recurso` varchar(500) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_limite` datetime DEFAULT NULL,
  `duracion_minutos` time NOT NULL,
  `nota_maxima` decimal(4,2) DEFAULT NULL,
  `recursos_url` varchar(255) DEFAULT NULL,
  `estado` enum('programado','activo','cerrado') DEFAULT 'programado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_actividad`
--

INSERT INTO `tbl_actividad` (`id`, `id_asignacion_docente`, `tipo`, `contenido`, `url_recurso`, `titulo`, `descripcion`, `fecha_programada`, `fecha_limite`, `duracion_minutos`, `nota_maxima`, `recursos_url`, `estado`, `created_at`, `updated_at`) VALUES
(9, 3, '', 'ok2', '', 'ok2', 'ok2', '2026-04-05 01:02:50', NULL, '00:00:00', NULL, '', '', '2026-04-04 23:02:50', '2026-04-04 23:02:50'),
(10, 3, '', 'ok3', '', 'ok', 'ok3', '2026-04-05 02:11:39', NULL, '00:00:00', NULL, '', '', '2026-04-05 00:11:39', '2026-04-05 00:11:39'),
(11, 3, '', 'ok', '', 'Noticia de ultima hora', 'ok', '2026-04-05 03:07:45', NULL, '00:00:00', NULL, '', '', '2026-04-05 01:07:45', '2026-04-05 01:07:45'),
(12, 3, '', 'ok', NULL, 'ok', 'ok', '2026-04-06 06:14:52', NULL, '00:00:00', NULL, '', '', '2026-04-06 04:14:52', '2026-04-06 04:14:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_asignacion_docente`
--

CREATE TABLE `tbl_asignacion_docente` (
  `id` int(11) NOT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `id_asignatura` int(11) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `id_periodo` int(11) DEFAULT NULL,
  `anno` year(4) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_asignacion_docente`
--

INSERT INTO `tbl_asignacion_docente` (`id`, `id_profesor`, `id_asignatura`, `id_seccion`, `id_periodo`, `anno`, `estado`) VALUES
(3, 3, 1, 2, 1, NULL, 0),
(7, 1, 1, 2, 1, '2026', 1),
(8, 2, 1, 2, 1, '2026', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_asignatura`
--

CREATE TABLE `tbl_asignatura` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_asignatura`
--

INSERT INTO `tbl_asignatura` (`id`, `nombre`, `codigo`) VALUES
(1, 'Informática', 'INF-127'),
(2, 'INGLES 2026', 'ING-290');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_asistencia`
--

CREATE TABLE `tbl_asistencia` (
  `id` int(11) NOT NULL,
  `id_matricula` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` enum('presente','ausente','tarde','justificada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_bienestar_alerta`
--

CREATE TABLE `tbl_bienestar_alerta` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `tipo` enum('ausencias','notas','conducta','reporte_docente','autodeteccion') NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `nivel` enum('alta','media','baja') DEFAULT 'media',
  `atendida` tinyint(1) DEFAULT 0,
  `id_seguimiento` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_bienestar_reporte_docente`
--

CREATE TABLE `tbl_bienestar_reporte_docente` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `categoria` enum('comportamiento','rendimiento','asistencia','relaciones','otro') NOT NULL,
  `descripcion` text NOT NULL,
  `derivar` tinyint(1) DEFAULT 0,
  `atendido` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_bienestar_seguimiento`
--

CREATE TABLE `tbl_bienestar_seguimiento` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_orientador` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `motivo` enum('academico','conductual','emocional','familiar','social','otro') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activo','resuelto','derivado','cerrado') DEFAULT 'activo',
  `prioridad` enum('alta','media','baja') DEFAULT 'media',
  `fecha_cierre` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_bienestar_seguimiento`
--

INSERT INTO `tbl_bienestar_seguimiento` (`id`, `id_estudiante`, `id_orientador`, `fecha_inicio`, `motivo`, `descripcion`, `estado`, `prioridad`, `fecha_cierre`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 16, '2026-04-23', 'conductual', 'ok', 'activo', 'alta', NULL, NULL, '2026-04-22 21:36:03', '2026-04-22 21:36:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_bienestar_sesion`
--

CREATE TABLE `tbl_bienestar_sesion` (
  `id` int(11) NOT NULL,
  `id_seguimiento` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('individual','grupal','familiar','telefonica','virtual') DEFAULT 'individual',
  `duracion_min` smallint(6) DEFAULT 30,
  `descripcion` text DEFAULT NULL,
  `acuerdos` text DEFAULT NULL,
  `proxima_sesion` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_calendario_evaluacion`
--

CREATE TABLE `tbl_calendario_evaluacion` (
  `id` int(11) NOT NULL,
  `id_asignacion_docente` int(11) DEFAULT NULL,
  `fecha_evaluacion` date DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_config_examen`
--

CREATE TABLE `tbl_config_examen` (
  `id` int(11) NOT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `tiempo_limite_minutos` int(11) DEFAULT 30,
  `bloquear_ventanas` tinyint(1) DEFAULT 1,
  `aleatorizar_preguntas` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_director`
--

CREATE TABLE `tbl_director` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_entrega_actividad`
--

CREATE TABLE `tbl_entrega_actividad` (
  `id` int(11) NOT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `id_matricula` int(11) DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `nota_obtenida` decimal(4,2) DEFAULT NULL,
  `observacion_docente` text DEFAULT NULL,
  `estado_entrega` enum('pendiente','entregado','calificado','reprobado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_estudiante`
--

CREATE TABLE `tbl_estudiante` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `nie` varchar(50) DEFAULT NULL,
  `estado_familiar` varchar(50) DEFAULT NULL,
  `discapacidad` varchar(100) DEFAULT NULL,
  `trabaja` tinyint(1) DEFAULT 0,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_estudiante`
--

INSERT INTO `tbl_estudiante` (`id`, `id_persona`, `nie`, `estado_familiar`, `discapacidad`, `trabaja`, `estado`) VALUES
(1, 6, '00010', '', 'Ninguna', 0, 'activo'),
(6, 14, '100002026', '', 'Ninguna', 0, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_foro`
--

CREATE TABLE `tbl_foro` (
  `id` int(11) NOT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_grado`
--

CREATE TABLE `tbl_grado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `nivel` enum('basica','bachillerato') NOT NULL,
  `nota_minima_aprobacion` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_grado`
--

INSERT INTO `tbl_grado` (`id`, `nombre`, `nivel`, `nota_minima_aprobacion`) VALUES
(1, 'Septimo', 'basica', 6.0),
(2, 'Octavo', 'basica', 6.0),
(4, 'Quinto', 'basica', 6.0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_asignacion`
--

CREATE TABLE `tbl_ingles_asignacion` (
  `id` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_leccion` int(11) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `fecha_limite` datetime DEFAULT NULL,
  `estado` enum('pendiente','en-progreso','completado','vencido') DEFAULT 'pendiente',
  `instrucciones` text DEFAULT NULL,
  `puntaje_minimo` decimal(4,2) DEFAULT 7.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_conversacion`
--

CREATE TABLE `tbl_ingles_conversacion` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `situacion` varchar(200) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL,
  `dialogo_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dialogo_json`)),
  `audio_url` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `preguntas_practica` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preguntas_practica`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_curso`
--

CREATE TABLE `tbl_ingles_curso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `nivel` enum('beginner','elementary','pre-intermediate','intermediate','upper-intermediate','advanced','proficient') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ingles_curso`
--

INSERT INTO `tbl_ingles_curso` (`id`, `nombre`, `nivel`, `descripcion`, `imagen`, `estado`, `created_at`) VALUES
(1, 'English for Beginners', 'beginner', 'Curso completo de inglés para principiantes desde cero', NULL, 'activo', '2026-03-30 02:21:53'),
(2, 'Elementary English', 'elementary', 'Inglés básico para comunicación diaria', NULL, 'activo', '2026-03-30 02:21:53'),
(3, 'Pre-Intermediate English', 'pre-intermediate', 'Inglés pre-intermedio para mejorar habilidades', NULL, 'activo', '2026-03-30 02:21:53'),
(4, 'Intermediate English', 'intermediate', 'Inglés intermedio para fluidez', NULL, 'activo', '2026-03-30 02:21:53'),
(5, 'Advanced English', 'advanced', 'Inglés avanzado para negocios y académico', NULL, 'activo', '2026-03-30 02:21:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_ejercicio`
--

CREATE TABLE `tbl_ingles_ejercicio` (
  `id` int(11) NOT NULL,
  `id_leccion` int(11) NOT NULL,
  `tipo` enum('multiple-choice','fill-blank','matching','ordering','speaking','listening','reading','writing') NOT NULL,
  `pregunta` text NOT NULL,
  `opciones_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opciones_json`)),
  `respuesta_correcta` varchar(500) DEFAULT NULL,
  `explicacion` text DEFAULT NULL,
  `puntos` int(11) DEFAULT 10,
  `orden` int(11) DEFAULT 0,
  `audio_url` varchar(500) DEFAULT NULL,
  `imagen_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_leccion`
--

CREATE TABLE `tbl_ingles_leccion` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('grammar','vocabulary','speaking','reading','listening','writing','conversation','pronunciation') NOT NULL,
  `orden` int(11) DEFAULT 0,
  `duracion_minutos` int(11) DEFAULT 30,
  `video_url` varchar(500) DEFAULT NULL,
  `video_tipo` enum('youtube','local','vimeo') DEFAULT 'youtube',
  `contenido_html` text DEFAULT NULL,
  `ejercicios_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ejercicios_json`)),
  `estado` enum('borrador','publicado','archivado') DEFAULT 'borrador',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_logros`
--

CREATE TABLE `tbl_ingles_logros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `puntos_requeridos` int(11) DEFAULT 0,
  `lecciones_requeridas` int(11) DEFAULT 0,
  `tipo` enum('lecciones','puntos','rachas','perfeccion','velocidad') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_logros_estudiante`
--

CREATE TABLE `tbl_ingles_logros_estudiante` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_logro` int(11) NOT NULL,
  `fecha_obtenido` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_progreso`
--

CREATE TABLE `tbl_ingles_progreso` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_leccion` int(11) NOT NULL,
  `id_asignacion` int(11) DEFAULT NULL,
  `estado` enum('no-iniciado','en-progreso','completado') DEFAULT 'no-iniciado',
  `puntaje` int(11) DEFAULT 0,
  `intentos` int(11) DEFAULT 0,
  `ultimo_intento` datetime DEFAULT NULL,
  `tiempo_empleado` int(11) DEFAULT 0,
  `respuestas_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`respuestas_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingles_vocabulario`
--

CREATE TABLE `tbl_ingles_vocabulario` (
  `id` int(11) NOT NULL,
  `palabra_ingles` varchar(100) NOT NULL,
  `palabra_espanol` varchar(100) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `ejemplo_ingles` text DEFAULT NULL,
  `ejemplo_espanol` text DEFAULT NULL,
  `audio_pronunciacion` varchar(500) DEFAULT NULL,
  `imagen_url` varchar(500) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL,
  `tags_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_institucion`
--

CREATE TABLE `tbl_institucion` (
  `id` int(11) NOT NULL,
  `nombre_ce` varchar(200) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `codigo_infra` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_institucion`
--

INSERT INTO `tbl_institucion` (`id`, `nombre_ce`, `direccion`, `departamento`, `municipio`, `telefono`, `email`, `codigo_infra`) VALUES
(1, 'Institución por Defecto', 'Dirección Temporal', 'San Salvador', 'San Salvador', '0000-0000', 'default@institucion.edu.sv', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_logs_actividad`
--

CREATE TABLE `tbl_logs_actividad` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(50) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_logs_actividad`
--

INSERT INTO `tbl_logs_actividad` (`id`, `id_usuario`, `accion`, `fecha_hora`, `ip_address`) VALUES
(8, 2, 'Login Exitoso', '2026-03-16 22:12:05', '::1'),
(9, 2, 'Login Exitoso', '2026-03-16 22:22:07', '::1'),
(10, 2, 'Login Exitoso', '2026-03-16 22:24:33', '::1'),
(11, 2, 'Login Exitoso', '2026-03-16 22:37:15', '::1'),
(19, 2, 'Login Exitoso', '2026-03-17 08:10:40', '::1'),
(20, 2, 'Login Exitoso', '2026-03-17 09:11:34', '::1'),
(27, 2, 'Login Exitoso', '2026-03-17 11:02:24', '::1'),
(28, 2, 'Login Exitoso', '2026-03-17 11:02:39', '::1'),
(29, 2, 'Login Exitoso', '2026-03-17 11:03:17', '::1'),
(30, 2, 'Login Exitoso', '2026-03-17 11:10:27', '::1'),
(34, 2, 'Login Exitoso', '2026-03-17 19:06:34', '::1'),
(58, 15, 'Login Exitoso', '2026-04-02 14:09:40', '::1'),
(59, 15, 'Logout', '2026-04-02 14:41:47', '::1'),
(60, 15, 'Login Exitoso', '2026-04-02 14:41:57', '::1'),
(61, 15, 'Logout', '2026-04-02 17:07:39', '::1'),
(62, 15, 'Login Exitoso', '2026-04-02 20:01:22', '::1'),
(63, 15, 'Logout', '2026-04-02 20:12:25', '::1'),
(64, 15, 'Login Exitoso', '2026-04-02 20:12:37', '::1'),
(65, 15, 'Login Exitoso', '2026-04-03 10:22:25', '::1'),
(66, 15, 'Logout', '2026-04-03 20:44:51', '::1'),
(69, 15, 'Login Exitoso', '2026-04-03 20:48:09', '::1'),
(70, 15, 'Login Exitoso', '2026-04-04 16:30:02', '::1'),
(71, 15, 'Login Exitoso', '2026-04-04 17:05:47', '::1'),
(72, 15, 'Logout', '2026-04-04 19:09:09', '::1'),
(75, 15, 'Login Exitoso', '2026-04-04 21:30:47', '::1'),
(76, 15, 'Login Exitoso', '2026-04-05 21:37:46', '::1'),
(77, 15, 'Logout', '2026-04-05 22:32:29', '::1'),
(78, 2, 'Login Exitoso', '2026-04-05 22:33:12', '::1'),
(79, 2, 'Login Exitoso', '2026-04-05 22:33:30', '::1'),
(80, 2, 'Login Exitoso', '2026-04-05 22:40:18', '::1'),
(81, 2, 'Login Exitoso', '2026-04-06 09:53:24', '::1'),
(82, 2, 'Login Exitoso', '2026-04-06 15:16:52', '::1'),
(83, 2, 'Login Exitoso', '2026-04-06 15:17:56', '::1'),
(84, 2, 'Login Exitoso', '2026-04-06 15:18:15', '::1'),
(85, 2, 'Login Exitoso', '2026-04-06 15:32:12', '::1'),
(86, 2, 'Login Exitoso', '2026-04-06 19:35:13', '::1'),
(92, 16, 'Login Exitoso', '2026-04-07 20:26:39', '::1'),
(93, 15, 'Login Exitoso', '2026-04-07 20:30:53', '::1'),
(94, 15, 'Login Exitoso', '2026-04-07 20:32:12', '::1'),
(95, 15, 'Login Exitoso', '2026-04-07 20:50:24', '::1'),
(96, 15, 'Login Exitoso', '2026-04-07 21:21:32', '::1'),
(97, 15, 'Login Exitoso', '2026-04-07 21:22:10', '::1'),
(98, 16, 'Login Exitoso', '2026-04-07 21:23:17', '::1'),
(99, 17, 'Login Exitoso', '2026-04-07 21:26:05', '::1'),
(100, 17, 'Login Exitoso', '2026-04-07 21:33:38', '::1'),
(101, 17, 'Login Exitoso', '2026-04-07 21:46:17', '::1'),
(103, 2, 'Login Exitoso', '2026-04-08 08:28:26', '::1'),
(104, 2, 'Login Exitoso', '2026-04-08 09:01:39', '::1'),
(105, 16, 'Login Exitoso', '2026-04-08 09:20:28', '::1'),
(106, 15, 'Login Exitoso', '2026-04-08 10:00:54', '::1'),
(107, 2, 'Login Exitoso', '2026-04-08 10:12:52', '::1'),
(108, 16, 'Login Exitoso', '2026-04-08 10:13:05', '::1'),
(109, 16, 'Login Exitoso', '2026-04-08 18:10:13', '::1'),
(110, 2, 'Login Exitoso', '2026-04-08 20:23:24', '::1'),
(120, 15, 'Login Exitoso', '2026-04-08 21:23:44', '::1'),
(121, 16, 'Login Exitoso', '2026-04-08 21:25:17', '::1'),
(127, 16, 'Login Exitoso', '2026-04-09 20:44:31', '::1'),
(128, 16, 'Login Exitoso', '2026-04-10 19:09:31', '::1'),
(129, 16, 'Login Exitoso', '2026-04-11 22:21:06', '::1'),
(130, 15, 'Login Exitoso', '2026-04-12 19:07:55', '::1'),
(133, 16, 'Login Exitoso', '2026-04-13 19:50:54', '::1'),
(134, 16, 'Login Exitoso', '2026-04-14 12:23:12', '::1'),
(135, 15, 'Login Exitoso', '2026-04-14 12:27:39', '::1'),
(136, 23, 'Login Exitoso', '2026-04-14 12:28:30', '::1'),
(137, 16, 'Login Exitoso', '2026-04-14 12:38:06', '::1'),
(138, 16, 'Login Exitoso', '2026-04-14 21:56:46', '::1'),
(139, 16, 'Login Exitoso', '2026-04-15 19:02:33', '::1'),
(140, 16, 'Login Exitoso', '2026-04-17 18:44:50', '::1'),
(141, 16, 'Login Exitoso', '2026-04-17 18:54:12', '::1'),
(142, 16, 'Login Exitoso', '2026-04-17 18:55:09', '::1'),
(143, 15, 'Login Exitoso', '2026-04-17 19:14:28', '::1'),
(144, 16, 'Login Exitoso', '2026-04-22 15:05:05', '::1'),
(145, 16, 'Login Exitoso', '2026-04-22 15:28:01', '::1'),
(146, 16, 'Login Exitoso', '2026-04-22 21:28:59', '::1'),
(147, 16, 'Login Exitoso', '2026-04-23 08:36:00', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_matricula`
--

CREATE TABLE `tbl_matricula` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `id_periodo` int(11) DEFAULT NULL,
  `anno` year(4) DEFAULT NULL,
  `estado` enum('activo','retirado') DEFAULT 'activo',
  `fecha_matricula` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_matricula`
--

INSERT INTO `tbl_matricula` (`id`, `id_estudiante`, `id_seccion`, `id_periodo`, `anno`, `estado`, `fecha_matricula`) VALUES
(1, 1, 3, 1, '2026', 'activo', NULL),
(7, 6, 4, 1, '2026', 'activo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_notificacion`
--

CREATE TABLE `tbl_notificacion` (
  `id` int(11) NOT NULL,
  `id_remitente` int(11) DEFAULT NULL,
  `id_destinatario` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_persona`
--

CREATE TABLE `tbl_persona` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `primer_nombre` varchar(100) DEFAULT NULL,
  `segundo_nombre` varchar(100) DEFAULT NULL,
  `tercer_nombre` varchar(250) NOT NULL,
  `primer_apellido` varchar(100) DEFAULT NULL,
  `segundo_apellido` varchar(100) DEFAULT NULL,
  `dui` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono_fijo` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_persona`
--

INSERT INTO `tbl_persona` (`id`, `id_usuario`, `primer_nombre`, `segundo_nombre`, `tercer_nombre`, `primer_apellido`, `segundo_apellido`, `dui`, `fecha_nacimiento`, `sexo`, `nacionalidad`, `direccion`, `telefono_fijo`, `celular`, `email`, `estado`) VALUES
(6, 10, 'Dony', '', '', 'Arce', '', '01000000-0', NULL, NULL, 'Salvadoreña', '', '', '69386007', 'donyhenry@gmail.com', 'activo'),
(7, 11, 'Dony', '', '', 'Navas', '', '01000000-0', '1979-07-05', 'M', 'Salvadoreña', '', '', '69386007', 'donyhenry@gmail.com', 'activo'),
(8, 14, 'Henry', '', '', 'Navas', '', '01200000-0', '1979-07-05', 'M', 'Salvadoreña', '', '', '69386007', 'docente@docente.com', 'activo'),
(10, 15, 'Profesor', NULL, '', 'Sistema', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'profesor@educacionplus.local', 'activo'),
(14, 23, 'Henry', '', '', 'Navas 2026', '', '', '0000-00-00', '', 'Salvadoreña', '', '', '', '', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_profesor`
--

CREATE TABLE `tbl_profesor` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `estado` varchar(250) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `titulo_academico` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_profesor`
--

INSERT INTO `tbl_profesor` (`id`, `id_persona`, `estado`, `especialidad`, `titulo_academico`) VALUES
(1, 7, '', 'Informatica', 'Ingeniero'),
(2, 8, '', 'Estudios Sociales', 'Tecnico en Informatica'),
(3, 10, '1', 'General', 'Licenciatura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_registro_anecdotico`
--

CREATE TABLE `tbl_registro_anecdotico` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo` enum('positivo','negativo','observacion') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_responsable`
--

CREATE TABLE `tbl_responsable` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `parentesco` varchar(50) DEFAULT NULL,
  `ocupacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_seccion`
--

CREATE TABLE `tbl_seccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `id_grado` int(11) DEFAULT NULL,
  `id_institucion` int(11) DEFAULT NULL,
  `anno_lectivo` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_seccion`
--

INSERT INTO `tbl_seccion` (`id`, `nombre`, `id_grado`, `id_institucion`, `anno_lectivo`) VALUES
(2, 'A', 1, 1, '2026'),
(3, 'A', 2, 1, '2026'),
(4, 'B', 4, 1, '2026');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `rol` enum('admin','director','profesor','estudiante','responsable') NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_usuario`
--

INSERT INTO `tbl_usuario` (`id`, `nombre`, `usuario`, `password`, `email`, `rol`, `estado`, `ultimo_acceso`, `fecha_registro`, `created_at`) VALUES
(2, '', 'estudiante', '$2y$10$tCD1tGFprC2D34zVOBthO.o2otGrvhqxUVJmZ0e94Pd.UTnce1Dya', '', 'estudiante', 1, '2026-03-16 22:10:57', '2026-04-07 20:23:53', '2026-03-17 04:11:35'),
(10, '', 'dhnavas', '$2y$10$p.FRDWRed4q4IvQ8SPiZCeav17m/cRGXplibC8wx2kCIY60g7UmDC', '', 'estudiante', 1, NULL, '2026-04-07 20:23:53', '2026-03-30 23:53:11'),
(11, '', 'profesor@profesor.com', '$2y$10$bYXz/SYyqEFUnUVM1sn7lOXj3mC7WAdhFcGg/nSAJ2XVUA1MjvcYK', '', 'profesor', 1, NULL, '2026-04-07 20:23:53', '2026-03-31 02:38:54'),
(14, '', 'docente1', '$2y$10$gyOk0NXN8xRD2bA4T3a1Mu2muGUPJUa8g3y8BgRKXNdU0O5QjfB82', '', 'profesor', 1, NULL, '2026-04-07 20:23:53', '2026-03-31 16:27:02'),
(15, 'Dony Navas', 'teacher', '$2y$10$6Mdsjb13lOQFD67D8rmLS.ONNa0HZ0p6qY4.yAG54F9hy2zHeNzc.', 'teacher@educacionplus.com', 'profesor', 1, NULL, '2026-04-07 20:23:53', '2026-04-02 20:09:22'),
(16, '', 'admin', '$2y$10$f6wkJjBHDOLN32wDDVKeS.A76v9XOQC5oalTx9O3TPgJvHQj4fV8i', 'admin@educacionplus.com', 'admin', 1, NULL, '2026-04-07 20:24:17', '2026-04-08 02:24:17'),
(17, '', 'henry', '$2y$10$ihUuS0Cp9Y.eH8qyNv1RjufSNAa2slbNeOhYqev28FcWqZAyS8CfC', 'henry10@educaplus.com', 'estudiante', 1, NULL, '2026-04-07 21:25:26', '2026-04-08 03:25:26'),
(23, 'Henry Navas 2026', 'henry2026', '$2y$10$VKDhSJSIplLLjyLHfHMrte6F6vyNVflwbvPJ1lymOLBgUs2R7fH8K', '', 'estudiante', 1, NULL, '2026-04-14 12:26:40', '2026-04-14 18:26:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_actividad`
--
ALTER TABLE `tbl_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_asignacion_docente` (`id_asignacion_docente`);

--
-- Indices de la tabla `tbl_asignacion_docente`
--
ALTER TABLE `tbl_asignacion_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_profesor` (`id_profesor`),
  ADD KEY `id_asignatura` (`id_asignatura`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `tbl_asignatura`
--
ALTER TABLE `tbl_asignatura`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_asistencia`
--
ALTER TABLE `tbl_asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_matricula` (`id_matricula`);

--
-- Indices de la tabla `tbl_bienestar_alerta`
--
ALTER TABLE `tbl_bienestar_alerta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_seguimiento` (`id_seguimiento`),
  ADD KEY `idx_alerta_atend` (`atendida`,`nivel`);

--
-- Indices de la tabla `tbl_bienestar_reporte_docente`
--
ALTER TABLE `tbl_bienestar_reporte_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `idx_rep_atendido` (`atendido`);

--
-- Indices de la tabla `tbl_bienestar_seguimiento`
--
ALTER TABLE `tbl_bienestar_seguimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_orientador` (`id_orientador`),
  ADD KEY `idx_seg_estudiante` (`id_estudiante`),
  ADD KEY `idx_seg_estado` (`estado`);

--
-- Indices de la tabla `tbl_bienestar_sesion`
--
ALTER TABLE `tbl_bienestar_sesion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_seguimiento` (`id_seguimiento`);

--
-- Indices de la tabla `tbl_calendario_evaluacion`
--
ALTER TABLE `tbl_calendario_evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_asignacion_docente` (`id_asignacion_docente`);

--
-- Indices de la tabla `tbl_config_examen`
--
ALTER TABLE `tbl_config_examen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `tbl_director`
--
ALTER TABLE `tbl_director`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `tbl_entrega_actividad`
--
ALTER TABLE `tbl_entrega_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`),
  ADD KEY `id_matricula` (`id_matricula`);

--
-- Indices de la tabla `tbl_estudiante`
--
ALTER TABLE `tbl_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nie` (`nie`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `tbl_foro`
--
ALTER TABLE `tbl_foro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tbl_grado`
--
ALTER TABLE `tbl_grado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_ingles_asignacion`
--
ALTER TABLE `tbl_ingles_asignacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_leccion` (`id_leccion`),
  ADD KEY `id_seccion` (`id_seccion`),
  ADD KEY `idx_ingles_asignacion_profesor` (`id_profesor`),
  ADD KEY `idx_ingles_asignacion_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `tbl_ingles_conversacion`
--
ALTER TABLE `tbl_ingles_conversacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_ingles_curso`
--
ALTER TABLE `tbl_ingles_curso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_ingles_ejercicio`
--
ALTER TABLE `tbl_ingles_ejercicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_leccion` (`id_leccion`);

--
-- Indices de la tabla `tbl_ingles_leccion`
--
ALTER TABLE `tbl_ingles_leccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `tbl_ingles_logros`
--
ALTER TABLE `tbl_ingles_logros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_ingles_logros_estudiante`
--
ALTER TABLE `tbl_ingles_logros_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_logro` (`id_logro`);

--
-- Indices de la tabla `tbl_ingles_progreso`
--
ALTER TABLE `tbl_ingles_progreso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ingles_progreso_estudiante` (`id_estudiante`),
  ADD KEY `idx_ingles_progreso_leccion` (`id_leccion`);

--
-- Indices de la tabla `tbl_ingles_vocabulario`
--
ALTER TABLE `tbl_ingles_vocabulario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_institucion`
--
ALTER TABLE `tbl_institucion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_logs_actividad`
--
ALTER TABLE `tbl_logs_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `tbl_notificacion`
--
ALTER TABLE `tbl_notificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remitente` (`id_remitente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Indices de la tabla `tbl_persona`
--
ALTER TABLE `tbl_persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tbl_profesor`
--
ALTER TABLE `tbl_profesor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `tbl_registro_anecdotico`
--
ALTER TABLE `tbl_registro_anecdotico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `tbl_responsable`
--
ALTER TABLE `tbl_responsable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `tbl_seccion`
--
ALTER TABLE `tbl_seccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_institucion` (`id_institucion`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_actividad`
--
ALTER TABLE `tbl_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tbl_asignacion_docente`
--
ALTER TABLE `tbl_asignacion_docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbl_asignatura`
--
ALTER TABLE `tbl_asignatura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_asistencia`
--
ALTER TABLE `tbl_asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_bienestar_alerta`
--
ALTER TABLE `tbl_bienestar_alerta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_bienestar_reporte_docente`
--
ALTER TABLE `tbl_bienestar_reporte_docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_bienestar_seguimiento`
--
ALTER TABLE `tbl_bienestar_seguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_bienestar_sesion`
--
ALTER TABLE `tbl_bienestar_sesion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_calendario_evaluacion`
--
ALTER TABLE `tbl_calendario_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_config_examen`
--
ALTER TABLE `tbl_config_examen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_director`
--
ALTER TABLE `tbl_director`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_entrega_actividad`
--
ALTER TABLE `tbl_entrega_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_estudiante`
--
ALTER TABLE `tbl_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbl_foro`
--
ALTER TABLE `tbl_foro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_grado`
--
ALTER TABLE `tbl_grado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_asignacion`
--
ALTER TABLE `tbl_ingles_asignacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_conversacion`
--
ALTER TABLE `tbl_ingles_conversacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_curso`
--
ALTER TABLE `tbl_ingles_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_ejercicio`
--
ALTER TABLE `tbl_ingles_ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_leccion`
--
ALTER TABLE `tbl_ingles_leccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_logros`
--
ALTER TABLE `tbl_ingles_logros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_logros_estudiante`
--
ALTER TABLE `tbl_ingles_logros_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_progreso`
--
ALTER TABLE `tbl_ingles_progreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ingles_vocabulario`
--
ALTER TABLE `tbl_ingles_vocabulario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_institucion`
--
ALTER TABLE `tbl_institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_logs_actividad`
--
ALTER TABLE `tbl_logs_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT de la tabla `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbl_notificacion`
--
ALTER TABLE `tbl_notificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_persona`
--
ALTER TABLE `tbl_persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tbl_profesor`
--
ALTER TABLE `tbl_profesor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_registro_anecdotico`
--
ALTER TABLE `tbl_registro_anecdotico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_responsable`
--
ALTER TABLE `tbl_responsable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_seccion`
--
ALTER TABLE `tbl_seccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_actividad`
--
ALTER TABLE `tbl_actividad`
  ADD CONSTRAINT `tbl_actividad_ibfk_1` FOREIGN KEY (`id_asignacion_docente`) REFERENCES `tbl_asignacion_docente` (`id`);

--
-- Filtros para la tabla `tbl_asignacion_docente`
--
ALTER TABLE `tbl_asignacion_docente`
  ADD CONSTRAINT `tbl_asignacion_docente_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `tbl_profesor` (`id`),
  ADD CONSTRAINT `tbl_asignacion_docente_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `tbl_asignatura` (`id`),
  ADD CONSTRAINT `tbl_asignacion_docente_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `tbl_seccion` (`id`);

--
-- Filtros para la tabla `tbl_asistencia`
--
ALTER TABLE `tbl_asistencia`
  ADD CONSTRAINT `tbl_asistencia_ibfk_1` FOREIGN KEY (`id_matricula`) REFERENCES `tbl_matricula` (`id`);

--
-- Filtros para la tabla `tbl_bienestar_alerta`
--
ALTER TABLE `tbl_bienestar_alerta`
  ADD CONSTRAINT `tbl_bienestar_alerta_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`),
  ADD CONSTRAINT `tbl_bienestar_alerta_ibfk_2` FOREIGN KEY (`id_seguimiento`) REFERENCES `tbl_bienestar_seguimiento` (`id`);

--
-- Filtros para la tabla `tbl_bienestar_reporte_docente`
--
ALTER TABLE `tbl_bienestar_reporte_docente`
  ADD CONSTRAINT `tbl_bienestar_reporte_docente_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`),
  ADD CONSTRAINT `tbl_bienestar_reporte_docente_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `tbl_usuario` (`id`);

--
-- Filtros para la tabla `tbl_bienestar_seguimiento`
--
ALTER TABLE `tbl_bienestar_seguimiento`
  ADD CONSTRAINT `tbl_bienestar_seguimiento_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`),
  ADD CONSTRAINT `tbl_bienestar_seguimiento_ibfk_2` FOREIGN KEY (`id_orientador`) REFERENCES `tbl_usuario` (`id`);

--
-- Filtros para la tabla `tbl_bienestar_sesion`
--
ALTER TABLE `tbl_bienestar_sesion`
  ADD CONSTRAINT `tbl_bienestar_sesion_ibfk_1` FOREIGN KEY (`id_seguimiento`) REFERENCES `tbl_bienestar_seguimiento` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_calendario_evaluacion`
--
ALTER TABLE `tbl_calendario_evaluacion`
  ADD CONSTRAINT `tbl_calendario_evaluacion_ibfk_1` FOREIGN KEY (`id_asignacion_docente`) REFERENCES `tbl_asignacion_docente` (`id`);

--
-- Filtros para la tabla `tbl_config_examen`
--
ALTER TABLE `tbl_config_examen`
  ADD CONSTRAINT `tbl_config_examen_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `tbl_actividad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_director`
--
ALTER TABLE `tbl_director`
  ADD CONSTRAINT `tbl_director_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `tbl_persona` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_entrega_actividad`
--
ALTER TABLE `tbl_entrega_actividad`
  ADD CONSTRAINT `tbl_entrega_actividad_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `tbl_actividad` (`id`),
  ADD CONSTRAINT `tbl_entrega_actividad_ibfk_2` FOREIGN KEY (`id_matricula`) REFERENCES `tbl_matricula` (`id`);

--
-- Filtros para la tabla `tbl_estudiante`
--
ALTER TABLE `tbl_estudiante`
  ADD CONSTRAINT `tbl_estudiante_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `tbl_persona` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_foro`
--
ALTER TABLE `tbl_foro`
  ADD CONSTRAINT `tbl_foro_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `tbl_actividad` (`id`),
  ADD CONSTRAINT `tbl_foro_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tbl_usuario` (`id`);

--
-- Filtros para la tabla `tbl_ingles_asignacion`
--
ALTER TABLE `tbl_ingles_asignacion`
  ADD CONSTRAINT `tbl_ingles_asignacion_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `tbl_profesor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_ingles_asignacion_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `tbl_ingles_curso` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tbl_ingles_asignacion_ibfk_3` FOREIGN KEY (`id_leccion`) REFERENCES `tbl_ingles_leccion` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tbl_ingles_asignacion_ibfk_4` FOREIGN KEY (`id_seccion`) REFERENCES `tbl_seccion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_ingles_asignacion_ibfk_5` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_ingles_ejercicio`
--
ALTER TABLE `tbl_ingles_ejercicio`
  ADD CONSTRAINT `tbl_ingles_ejercicio_ibfk_1` FOREIGN KEY (`id_leccion`) REFERENCES `tbl_ingles_leccion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_ingles_leccion`
--
ALTER TABLE `tbl_ingles_leccion`
  ADD CONSTRAINT `tbl_ingles_leccion_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `tbl_ingles_curso` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_ingles_logros_estudiante`
--
ALTER TABLE `tbl_ingles_logros_estudiante`
  ADD CONSTRAINT `tbl_ingles_logros_estudiante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_ingles_logros_estudiante_ibfk_2` FOREIGN KEY (`id_logro`) REFERENCES `tbl_ingles_logros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_ingles_progreso`
--
ALTER TABLE `tbl_ingles_progreso`
  ADD CONSTRAINT `tbl_ingles_progreso_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_ingles_progreso_ibfk_2` FOREIGN KEY (`id_leccion`) REFERENCES `tbl_ingles_leccion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_logs_actividad`
--
ALTER TABLE `tbl_logs_actividad`
  ADD CONSTRAINT `tbl_logs_actividad_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tbl_usuario` (`id`);

--
-- Filtros para la tabla `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  ADD CONSTRAINT `tbl_matricula_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`),
  ADD CONSTRAINT `tbl_matricula_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `tbl_seccion` (`id`);

--
-- Filtros para la tabla `tbl_notificacion`
--
ALTER TABLE `tbl_notificacion`
  ADD CONSTRAINT `tbl_notificacion_ibfk_1` FOREIGN KEY (`id_remitente`) REFERENCES `tbl_usuario` (`id`),
  ADD CONSTRAINT `tbl_notificacion_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `tbl_usuario` (`id`);

--
-- Filtros para la tabla `tbl_persona`
--
ALTER TABLE `tbl_persona`
  ADD CONSTRAINT `tbl_persona_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tbl_usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_profesor`
--
ALTER TABLE `tbl_profesor`
  ADD CONSTRAINT `tbl_profesor_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `tbl_persona` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_registro_anecdotico`
--
ALTER TABLE `tbl_registro_anecdotico`
  ADD CONSTRAINT `tbl_registro_anecdotico_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `tbl_estudiante` (`id`),
  ADD CONSTRAINT `tbl_registro_anecdotico_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `tbl_profesor` (`id`);

--
-- Filtros para la tabla `tbl_responsable`
--
ALTER TABLE `tbl_responsable`
  ADD CONSTRAINT `tbl_responsable_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `tbl_persona` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_seccion`
--
ALTER TABLE `tbl_seccion`
  ADD CONSTRAINT `tbl_seccion_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `tbl_grado` (`id`),
  ADD CONSTRAINT `tbl_seccion_ibfk_2` FOREIGN KEY (`id_institucion`) REFERENCES `tbl_institucion` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
