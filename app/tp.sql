-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2021 a las 01:42:56
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `changelogs`
--

CREATE TABLE `changelogs` (
  `id` int(18) NOT NULL,
  `tabla_afectada` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `id_afectado` int(18) NOT NULL,
  `id_empleado` int(18) NOT NULL,
  `accion` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `changelogs`
--

INSERT INTO `changelogs` (`id`, `tabla_afectada`, `id_afectado`, `id_empleado`, `accion`, `descripcion`, `fecha_hora`, `fecha_de_baja`) VALUES
(1, 'pedidos', 2, 3, 'Modificar', 'pagado', '2021-06-11 17:09:00', NULL),
(2, 'pedidos', 3, 3, 'Modificar', 'pagado', '2021-06-11 17:09:00', NULL),
(3, 'pedidos', 3, 3, 'Modificar', 'pagado', '2021-06-11 17:30:19', NULL),
(4, 'pedidos', 0, 3, 'Obtener datos', 'Datos de todos los pedidos', '2021-06-11 17:54:40', NULL),
(5, 'pedidos', 3, 3, 'Eliminar', 'Se realizo el softdelete de la fila', '2021-06-11 18:17:20', NULL),
(6, 'productos', 5, 3, 'Cargar', 'Stock: 50', '2021-06-11 19:34:34', NULL),
(7, 'productos', 0, 3, 'Obtener datos', 'Datos de todos los producto', '2021-06-11 19:35:18', NULL),
(8, 'empleados', 0, 3, 'Obtener datos', 'Datos de todos los empleados', '2021-06-11 19:53:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `mail` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `dni` int(8) NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `mail`, `dni`, `fecha_de_baja`) VALUES
(1, 'Lucas', 'Valdiviezo', 'lucas@lucas.com', 40091498, NULL),
(2, 'Mauro', 'Ovando', 'mauro@mauro.com', 30030501, '2021-06-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(18) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `mail` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `clave` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `puesto` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `mail`, `clave`, `puesto`, `fecha_de_baja`) VALUES
(1, 'Mauro', 'Ovando', 'mauro@mauro.com', 'contraseña123', 'bartender', NULL),
(2, 'Martin', 'Bottani', 'martin@martin.com', 'chau534', 'cocina', NULL),
(3, 'Nicolas', 'Alvarez', 'nico@nico.com', '123asd', 'socio', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(10) NOT NULL,
  `numero` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero`, `estado`, `fecha_de_baja`) VALUES
(1, 'wst3b', 'cerrada', NULL),
(2, '4qhij', 'cerrada', NULL),
(3, '620ed', 'cerrada', '2021-06-05'),
(4, '4kib9', 'cerrada', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(18) NOT NULL,
  `codigo` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `id_cliente` int(18) NOT NULL,
  `id_mesa` int(18) NOT NULL,
  `datos_productos` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `id_empleado` int(18) NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `total` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `puesto` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_hora_creacion` datetime NOT NULL,
  `ultima_modificacion` time NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigo`, `id_cliente`, `id_mesa`, `datos_productos`, `id_empleado`, `estado`, `total`, `puesto`, `fecha_hora_creacion`, `ultima_modificacion`, `fecha_de_baja`) VALUES
(1, '8dz7o', 1, 2, 'Id: 2 - Cantidad: 4 / Id: 1 - Cantidad: 1 / ', 1, 'pagado', '$52', '-mesa-', '2021-06-07 01:45:01', '17:00:57', NULL),
(2, '1hrgb', 1, 2, 'Id: 2 - Cantidad: 3 / ', 3, 'pagado', '$33', '-mesa-', '2021-06-11 16:59:42', '17:08:16', NULL),
(3, 'qcamt', 1, 1, 'Id: 2 - Cantidad: 5 / ', 3, 'pagado', '$55', '-mesa-', '2021-06-11 17:04:22', '17:30:19', '2021-06-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(18) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `precio` decimal(30,0) NOT NULL,
  `stock` int(18) NOT NULL,
  `tipo` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `stock`, `tipo`, `fecha_de_baja`) VALUES
(1, 'Papas Fritas', '8', 288, 'comida', NULL),
(2, 'Coca Cola', '11', 27, 'bebida', NULL),
(3, 'Sprite', '6', 200, 'bebida', '2021-06-05'),
(4, 'Hamburguesa', '21', 50, 'comida', NULL),
(5, 'Hamburguesa Vegana', '21', 50, 'comida', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `changelogs`
--
ALTER TABLE `changelogs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `changelogs`
--
ALTER TABLE `changelogs`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
