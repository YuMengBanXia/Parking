-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Abonado`;
DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Plaza`;
DELETE FROM `Parking`;
DELETE FROM `Usuario`;

INSERT INTO `Parking` (`id`,`dir`, `ciudad`, `CP`, `precio`, `nPlazas`) VALUES
(1,'Calle Princesa', 'Madrid', 28008, 1.23, 100),
(2,'Avenida Valvanera', 'Madrid',28047,1.15, 50);



