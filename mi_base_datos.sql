-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-08-2025 a las 23:11:06
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
-- Base de datos: `mi_base_datos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `grado` varchar(10) NOT NULL DEFAULT '11-2',
  `barrio` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `genero` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `nombre_completo`, `numero_documento`, `grado`, `barrio`, `ciudad`, `genero`) VALUES
(93, 'AGUDELO ZAPATA MARIA SALOME', '', '11-2', '', '', ''),
(94, 'AGUINAGA BARRIENTOS CHRISTOPHER REY', '', '11-2', '', '', ''),
(95, 'ALVAREZ CALLE DANNA ISABELLA', '', '11-2', '', '', ''),
(96, 'BEDOYA COLORADO DANIEL', '', '11-2', '', '', ''),
(97, 'BEDOYA SANCHEZ ANA SOFIA', '', '11-2', '', '', ''),
(98, 'BOTERO CARDONA JUAN JOSE', '', '11-2', '', '', ''),
(99, 'CALLE RIOS JOHAN', '', '11-2', '', '', ''),
(100, 'CAMPO MESA JUAN JOSE', '', '11-2', '', '', ''),
(101, 'CAÑAS MENESES SIMON', '', '11-2', '', '', ''),
(102, 'CANO BOLIVAR SOFIA', '', '11-2', '', '', ''),
(103, 'COLORADO GALLEGO ALEJANDRA', '', '11-2', '', '', ''),
(104, 'DAVID ZAPATA MIGUEL ANGEL', '', '11-2', '', '', ''),
(105, 'GIRALDO GIRALDO YISELA ANDREA', '', '11-2', '', '', ''),
(106, 'GUERRA GARCES MIGUEL ANGEL', '', '11-2', '', '', ''),
(107, 'HERNANDEZ TENORIO SEBASTIAN', '', '11-2', '', '', ''),
(108, 'LONDOÑO DUQUE ISABELLA', '', '11-2', '', '', ''),
(109, 'LOPEZ LONDOÑO KEVIN ANDRES', '', '11-2', '', '', ''),
(110, 'MOSQUERA PANIAGUA KENDRY NICOL', '', '11-2', '', '', ''),
(111, 'OCHOA ALZATE MARIANGEL', '', '11-2', '', '', ''),
(112, 'ORTIZ GUERRA EMANUEL', '', '11-2', '', '', ''),
(113, 'OSPINA CHAVARRIA LUIS MIGUEL', '', '11-2', '', '', ''),
(114, 'OSPINA VELASQUEZ MARÍA JOSÉ', '', '11-2', '', '', ''),
(115, 'OSSA DAVID SIMON', '', '11-2', '', '', ''),
(116, 'PEREZ GUTIERREZ EMANUEL', '', '11-2', '', '', ''),
(117, 'QUINTERO CLAVIJO STIVEN', '', '11-2', '', '', ''),
(118, 'RAMIREZ PINEDA EMANUEL', '', '11-2', '', '', ''),
(119, 'SALAZAR CORTES DAVID', '', '11-2', '', '', ''),
(120, 'SEPULVEDA CALLEJAS MICHELLE', '', '11-2', '', '', ''),
(121, 'TAMAYO RAMIREZ JUAN JOSE', '', '11-2', '', '', ''),
(122, 'USUGA CRUZ ISABELLA', '', '11-2', '', '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
