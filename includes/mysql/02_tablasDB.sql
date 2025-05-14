-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DROP TABLE IF EXISTS `Abonado`;
DROP TABLE IF EXISTS `Ticket`;
DROP TABLE IF EXISTS `Reserva`;
DROP TABLE IF EXISTS `Plaza`;
DROP TABLE IF EXISTS `Parking`;
DROP TABLE IF EXISTS `Pago`;
DROP TABLE IF EXISTS `Usuario`;
DROP TABLE IF EXISTS mysql.events;

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

CREATE TABLE mysql.events (
  db CHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  name CHAR(64) NOT NULL DEFAULT '',
  body longblob NOT NULL,
  definer CHAR(93) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  execute_at datetime DEFAULT NULL,
  interval_value int(11) DEFAULT NULL,
  interval_field enum('YEAR','QUARTER','MONTH','DAY','HOUR','MINUTE','WEEK','SECOND','MICROSECOND','YEAR_MONTH','DAY_HOUR','DAY_MINUTE','DAY_SECOND','HOUR_MINUTE','HOUR_SECOND','MINUTE_SECOND','DAY_MICROSECOND','HOUR_MICROSECOND','MINUTE_MICROSECOND','SECOND_MICROSECOND') DEFAULT NULL,
  starts datetime DEFAULT NULL,
  ends datetime DEFAULT NULL,
  status enum('ENABLED','DISABLED','SLAVESIDE_DISABLED') NOT NULL DEFAULT 'ENABLED',
  on_completion enum('DROP','PRESERVE') NOT NULL DEFAULT 'DROP',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  modified timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_executed datetime DEFAULT NULL,
  comment CHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  originator int(10) unsigned NOT NULL,
  time_zone char(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT 'SYSTEM',
  character_set_client char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  collation_connection char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  db_collation char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  body_utf8 longblob DEFAULT NULL,
  PRIMARY KEY (db,name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Events';
