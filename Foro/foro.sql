-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-04-2024 a las 21:11:04
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `foro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `cod` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `data_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Categorías: organización dos temas por intereses comúns';

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`cod`, `nome`, `descripcion`, `data_creacion`) VALUES
(1, 'Deportes', 'Categoría do foro dedicado ao mundo dos deportes.', '2024-03-18 16:42:31'),
(2, 'politica', 'Categoría que engloba temas sobre política nacional e internacional.', '2024-03-18 16:43:38'),
(3, 'automobil', 'Categoría relacionada co mundo do automobilismo', '2024-03-20 17:56:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `cod` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `comentario` text NOT NULL,
  `data_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `cod_tema` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Comentarios: seran os comentarios dos temas.';

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`cod`, `titulo`, `comentario`, `data_creacion`, `cod_tema`) VALUES
(1, 'Parque automobilístico 2024', 'Que opinades dos coches de este ano?', '2024-03-22 16:39:09', 12),
(2, 'Eleccións da Xunta', 'Resultados das eleccións da Xunta: un atraso.', '2024-03-22 16:40:56', 17),
(3, 'Tipos de motos', 'Que tipo de motos preferides?', '2024-03-22 16:41:31', 16),
(4, 'O combate do ano', 'Cal considerades que é o combate do ano?', '2024-03-22 16:42:40', 11),
(5, 'Cuartos de final de champions', 'Que equipos considerades que pasarán a semis?', '2024-03-22 16:43:21', 1),
(6, 'Campións da NBA', 'Que equipo levará os aneis da NBA?', '2024-03-22 16:44:26', 2),
(7, 'O clásico', 'cal será o resultado do clásico deste ano?', '2024-03-22 18:05:05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE `temas` (
  `cod` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `n_resp` int(11) NOT NULL,
  `data_creacion` timestamp NULL DEFAULT NULL,
  `cod_categoria` int(11) DEFAULT NULL,
  `cod_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`cod`, `nome`, `descripcion`, `n_resp`, `data_creacion`, `cod_categoria`, `cod_usuario`) VALUES
(1, 'futbol', 'Tema relacionado co mundo do fútbol', 4, NULL, 1, NULL),
(2, 'baloncesto', 'Tema relacionado co mundo do baloncesto', 0, NULL, 1, NULL),
(4, 'politica_nacional', 'Tema relacionado coa política nacional.', 0, NULL, 2, NULL),
(7, 'politica_internacional', 'Tema relacionado coa política internacional.', 0, NULL, 2, NULL),
(11, 'ufc', 'Todo o relacionado coas MMA.', 0, '2024-03-21 17:36:56', 1, NULL),
(12, 'coches', 'Todo o relacionado co mundo dos coches.', 0, '2024-03-21 17:38:47', 3, NULL),
(13, 'rugby', 'Todo o relacionado co mundo do rugby.', 0, '2024-03-21 18:00:10', 1, 2),
(16, 'motos', 'Todo o relacionado co mundo das motos.', 0, '2024-03-21 18:20:56', 3, 2),
(17, 'politica galega', 'Todo o relacionado coa política de Galicia.', 0, '2024-03-22 16:08:56', 2, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(80) NOT NULL,
  `contrasinal` varchar(255) NOT NULL COMMENT 'Habería que activar hash',
  `email` varchar(150) NOT NULL,
  `n_temas` int(11) DEFAULT 0 COMMENT 'Número de temas creados polo usuario.',
  `n_post` int(11) DEFAULT 0,
  `rol_usuario` enum('admin','creador','comentador','lector') NOT NULL COMMENT 'admin (control total), creador (podera manexar os temas pero so os \r\nque el cree), comentador(comentar en temas) e lector (so lectura).',
  `avatar` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='So os admin poderan crear usuarios asignandolle o tipo de us';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasinal`, `email`, `n_temas`, `n_post`, `rol_usuario`, `avatar`) VALUES
(1, 'borja', 'abc123.', 'borja@gmail.com', 6, 7, 'admin', '^_^'),
(2, 'manuel_creador', 'abc123.', 'manuel@gmail.com', 3, 5, 'creador', '*_*'),
(3, 'julio_comentador', 'abc123.', 'julio@gmail.com', 2, 4, 'comentador', '¬_¬'),
(4, 'oscar_lee', 'abc123.', 'oscar@gmail.com', 4, 6, 'lector', '\'_\''),
(5, 'alfonso', '', '', 0, 0, 'comentador', '^_^'),
(6, 'cris', '', '', 0, 0, 'comentador', ''),
(9, 'pepe', 'abc123.', 'pepe@gmail.com', 0, 0, 'creador', ''),
(13, 'admin', 'abc123.', 'admin@gmail.com', 0, 0, 'admin', '*_*');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cod`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_cod_tema` (`cod_tema`);

--
-- Indices de la tabla `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_categoria` (`cod_categoria`),
  ADD KEY `fk_usuario` (`cod_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `temas`
--
ALTER TABLE `temas`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_cod_tema` FOREIGN KEY (`cod_tema`) REFERENCES `temas` (`cod`),
  ADD CONSTRAINT `fk_tema_nuevo` FOREIGN KEY (`cod_tema`) REFERENCES `temas` (`cod`);

--
-- Filtros para la tabla `temas`
--
ALTER TABLE `temas`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`cod_categoria`) REFERENCES `categorias` (`cod`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
