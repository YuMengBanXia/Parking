-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Parking`;
DELETE FROM `Usuario`;

-- La contraseña de los usuarios es 123
INSERT INTO `Usuario` (`dni`, `nomUsuario`, `contrasenia`, `tipoUsuario`) VALUES
('12345678A', 'admin', '$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'administrador'),
('12345678C', 'cliente', '$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'cliente'),
('12345678P', 'prop', '$$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'propietario'),
('00000000x', 'prop2', '$2y$10$ZkCmuXYwfyXft.kuktWBJuN0BHHzsH1XR80AzNVIZsCRUK3o70gdS', 'propietario');

INSERT INTO `Parking` (`id`, `dni`, `dir`, `ciudad`, `CP`, `precio`, `nPlazas`) VALUES
(1, '12345678A', 'Calle Princesa', 'Madrid', 28008, 0.02, 3),
(2, '12345678A', 'Avenida Valvanera', 'Madrid', 28047, 0.02, 50);

INSERT INTO `Pago` (`id`, `dni`, `importe`, `fechaPago`) VALUES
(1, '12345678A', 25.00, '2025-04-20'),
(2, '12345678C', 40.50, '2025-04-22'),
(3, '12345678P', 100.75, '2025-05-01');

INSERT INTO `ticket` (`codigo`, `idParking`, `fecha_ini`, `matricula`) VALUES 
(NULL, '1', '2025-05-06 11:28:35', '1111AAA'), 
(NULL, '1', '2025-05-06 21:28:35', '2222BBB'), 
(NULL, '2', '2025-05-05 13:18:00', '3333CCC');





