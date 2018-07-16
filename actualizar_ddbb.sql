CREATE TABLE `impuestos` (
  `id` int(11) NOT NULL,
  `importe` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `impuestos`
--

INSERT INTO `impuestos` (`id`, `importe`) VALUES
(1, '7.0'),
(2, '21.0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/************/

ALTER TABLE `perfil_internet` ADD `coste` DECIMAL(4,1) NOT NULL AFTER `bytes_dw`, ADD `pvp` DECIMAL(4,1) NOT NULL AFTER `coste`, ADD `impuesto` INT(1) NOT NULL AFTER `pvp`;