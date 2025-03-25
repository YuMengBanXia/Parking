-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Abonado`;
DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Plaza`;
DELETE FROM `Parking`;
DELETE FROM `Usuario`;

INSERT INTO `Parking` (`precio`, `nPlazas`, `dir`, `CP`, `ciudad`) VALUES
(6.66, 100, 'Calle Princesa', 28008, 'Madrid'),
(2.10, 20, 'Avenida Valvanera', 28047, 'Madrid');