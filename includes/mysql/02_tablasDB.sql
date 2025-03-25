-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DROP TABLE IF EXISTS `Usuario`;
DROP TABLE IF EXISTS `Parking`;
DROP TABLE IF EXISTS `Plaza`;
DROP TABLE IF EXISTS `Reserva`;
DROP TABLE IF EXISTS `Ticket`;
DROP TABLE IF EXISTS `Abonado`;

-- Estructura de tabla para la tabla `Usuario`
-- rol = 0 -> Cliente
-- rol = 1 -> Propietario
CREATE TABLE `Usuario` (
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `nomUsuario` varchar(99) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `contrasenia` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` bit(1) NOT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Parking` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `precio` decimal(5,4) NOT NULL,
  `nPlazas` int(11) NOT NULL,
  `dir` varchar(100) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `CP` decimal(5,0) NOT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `Plaza`
-- ocupado = 0 -> Libre
-- ocupado = 1 -> Ocupado
CREATE TABLE `Plaza` (
  `num` int(10) NOT NULL,
  `idParking` int(10) NOT NULL,
  `ocupado` bit(1) DEFAULT b'0',
  PRIMARY KEY (`num`, `idParking`),
  CONSTRAINT `plazaDeUnParking` FOREIGN KEY (`idParking`) REFERENCES `Parking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Reserva` (
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `numPlaza` int(10) NOT NULL,
  `idParking` int(10) NOT NULL,
  `fecha` datetime NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`dni`, `numPlaza`, `idParking`),
  CONSTRAINT `reservaUsuario` FOREIGN KEY (`dni`) REFERENCES `Usuario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservaPlaza` FOREIGN KEY (`numPlaza`, `idParking`) REFERENCES `Plaza` (`num`, `idParking`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Ticket` (
  `codigo` int(10) NOT NULL,
  `idParking` int(11) NOT NULL,
  `fecha_ini` datetime NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`codigo`, `idParking`),
  CONSTRAINT `ticketParking` FOREIGN KEY (`idParking`) REFERENCES `Parking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Abonado` (
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `numPlaza` int(11) NOT NULL,
  `idParking` int(11) NOT NULL,
  `IBAN` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`dni`, `numPlaza`, `idParking`),
  CONSTRAINT `abonoUsuario` FOREIGN KEY (`dni`) REFERENCES `Usuario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `plazaAbonado` FOREIGN KEY (`numPlaza`, `idParking`) REFERENCES `Plaza` (`num`, `idParking`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
