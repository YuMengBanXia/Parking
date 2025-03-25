-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DROP TABLE IF EXISTS `Abonado`;

CREATE TABLE `Abonado` (
  `dni` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `id` int(11) NOT NULL,
  `matricula` varchar(7) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `banco` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`dni`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;