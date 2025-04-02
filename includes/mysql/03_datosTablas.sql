-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Abonado`;
DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Plaza`;
DELETE FROM `Parking`;
DELETE FROM `Usuario`;

INSERT INTO `Usuario` (`dni`, `nomUsuario`, `contrasenia`, `tipoUsuario`) VALUES 
('12345678A', 'nombreEjemplo', 'contraseñaEjemplo', b'0');

INSERT INTO `Parking` (`id`, `dni`, `dir`, `ciudad`, `CP`, `precio`, `nPlazas`) VALUES
(1, '12345678A', 'Calle Princesa', 'Madrid', 28008, 1.23, 3),
(2, '12345678A', 'Avenida Valvanera', 'Madrid', 28047, 1.15, 50);




