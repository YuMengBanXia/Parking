-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Parking`;
DELETE FROM `Pago`;
DELETE FROM `Usuario`;

-- La contraseña de los usuarios es 123
INSERT INTO `Usuario` (`dni`, `nomUsuario`, `contrasenia`, `tipoUsuario`) VALUES
('12345678A', 'admin', '$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'administrador'),
('12345678C', 'cliente', '$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'cliente'),
('12345678P', 'prop', '$$2y$10$s9.jlcAgmCqbsIsZPZrb.OF1LhuIGW8B/Jronb5cg2jI7jcQ37g0u', 'propietario'),
('00000000x', 'prop2', '$2y$10$ZkCmuXYwfyXft.kuktWBJuN0BHHzsH1XR80AzNVIZsCRUK3o70gdS', 'propietario');

INSERT INTO `Parking` (`id`, `dni`, `dir`, `ciudad`, `CP`, `precio`, `nPlazas`, `img`) VALUES
(1, '00000000x', 'Puerta del Sol', 'Madrid', 28013, 0.04, 20, NULL),
(2, '12345678A', 'Avenida Valvanera', 'Madrid', 28047, 0.02, 50, NULL),
(3, '12345678C', 'Calle de Vallehermoso', 'Madrid', 28015, 0.03, 30, NULL),
(4, '12345678A', 'Calle de la Via Carpetana', 'Madrid', 28099, 0.02, 10, NULL),
(5, '12345678P', 'Calle de Velazquez 128', 'Madrid', 28006, 0.0010, 20, 'img/img_6824e65c004fa4.18363440.jpg'),
(6, '12345678P', 'Calle de la Oca', 'Madrid', 28025, 0.03, 60, NULL),
(7, '12345678P', 'Calle de Sinesio Delgado', 'Madrid', 28035, 0.03, 20, NULL),
(8, '00000000x', 'Calle de Caspe', 'Madrid', 28022, 0.05, 10, NULL),
(9, '00000000x', 'Avenida de Manzanares', 'Madrid', 28026, 0.03, 30, NULL);

INSERT INTO `Pago` (`id`, `dni`, `idParking`,`importe`, `fechaPago`) VALUES
(1, '12345678A', 2,25.00, '2025-04-20'),
(2, '12345678C', 3,40.50, '2025-04-22'),
(3, '12345678P',5 ,100.75, '2025-05-01');

INSERT INTO `Ticket` (`codigo`, `idParking`, `fecha_ini`, `matricula`) VALUES 
(NULL, '1', '2025-05-14 11:28:35', '1111AAA'), 
(NULL, '1', '2025-05-15 21:28:35', '2222BBB'), 
(NULL, '2', '2025-05-16 13:18:00', '3333CCC');

INSERT INTO `Reserva` (`dni`, `id`, `fecha_ini`, `fecha_fin`, `matricula`, `importe`, `estado`, `num_orden`) VALUES
('12345678C', 5, '2025-05-14 22:56:00', '2025-05-15 01:26:00', '1234AAA', 0.14, 'PAGADA', '202505142067'),
('12345678C', 5, '2025-05-16 05:56:00', '2025-05-23 07:56:00', '1234BBB', 9.18, 'PAGADA', '202505142077'),
('12345678C', 2, '2025-05-19 20:57:00', '2025-05-25 20:57:00', '1234CCC', 155.52, 'PAGADA', '20250514205F'),
('12345678A', 5, '2025-05-17 20:58:00', '2025-05-19 20:58:00', '5050AAA', 2.59, 'PENDIENTE', NULL);






