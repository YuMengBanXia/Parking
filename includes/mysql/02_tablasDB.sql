-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DROP TABLE IF EXISTS `Abonado`;
DROP TABLE IF EXISTS `Ticket`;
DROP TABLE IF EXISTS `Reserva`;
DROP TABLE IF EXISTS `Plaza`;
DROP TABLE IF EXISTS `Parking`;
DROP TABLE IF EXISTS `Usuario`;

CREATE TABLE `Usuario` (
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `nomUsuario` varchar(99) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `contrasenia` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `tipoUsuario` ENUM('cliente', 'propietario', 'administrador') NOT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Parking` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `dir` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `CP` decimal(5,0) NOT NULL,
  `precio` decimal(5,4) NOT NULL,
  `nPlazas` int(11) NOT NULL,
  `img` varchar(100) COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  CONSTRAINT `crearUsuario` FOREIGN KEY (`dni`) REFERENCES `Usuario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `parkingCiudad` (`dir`, `ciudad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Reserva` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `id` int(10) NOT NULL,
  `fecha_ini` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `importe`    DECIMAL(10,2) NOT NULL,
  `estado` ENUM('PENDIENTE','PAGADA','ACTIVA','CANCELADA','COMPLETADA') NOT NULL DEFAULT 'PENDIENTE',
  `num_orden` CHAR(12),
  PRIMARY KEY (`codigo`),
  CONSTRAINT `reservaUsuario` FOREIGN KEY (`dni`) REFERENCES `Usuario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservaParking` FOREIGN KEY (`id`) REFERENCES `Parking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Ticket` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `idParking` int(10) NOT NULL,
  `fecha_ini` datetime NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `ticketParking` FOREIGN KEY (`idParking`) REFERENCES `Parking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Pago` (
  `id`         INT           NOT NULL AUTO_INCREMENT,
  `dni`        VARCHAR(9)    NOT NULL,
  `importe`    DECIMAL(10,2) NOT NULL,
  `fechaPago`  DATETIME      NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`dni`) REFERENCES `Usuario`(`dni`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
