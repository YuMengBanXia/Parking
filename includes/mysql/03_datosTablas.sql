-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

TRUNCATE TABLE `Abonado`;
TRUNCATE TABLE `Ticket`;
TRUNCATE TABLE `Reserva`;
TRUNCATE TABLE `Plaza`;
TRUNCATE TABLE `Parking`;
TRUNCATE TABLE `Usuario`;

INSERT INTO `Parking` (`precio`, `nPlazas`, `dir`, `CP`, `ciudad`) VALUES
(6.66, 100, 'Calle Princesa', 28008, 'Madrid'),
(2.10, 20, 'Avenida Valvanera', 28047, 'Madrid');