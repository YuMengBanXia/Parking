-- Deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.

DELETE FROM `Abonado`;
DELETE FROM `Ticket`;
DELETE FROM `Reserva`;
DELETE FROM `Plaza`;
DELETE FROM `Parking`;
DELETE FROM `Usuario`;

INSERT INTO `Usuario` (`dni`, `nomUsuario`, `contrasenia`, `tipoUsuario`) VALUES
('12345678A', 'admin', '$2y$10$CgF.pBhLJLBBZ0zeVvhCFOhPEv8Cu4gFfevRjio0bgT5Ov87DURHG', 'administrador'),
('12345678C', 'cliente', '$2y$10$f9zPU665HVi1PYLhEzVGteavDLpVDzGwMt1Wt/eHFP/DK7EByBHaa', 'cliente'),
('12345678P', 'prop', '$2y$10$/s6AbAeeJFos.fNpvhAOXeqtctji4nQXl2tnIZIBXUq0V7Aykw9RG', 'propietario');

INSERT INTO `Parking` (`id`, `dni`, `dir`, `ciudad`, `CP`, `precio`, `nPlazas`) VALUES
(1, '12345678A', 'Calle Princesa', 'Madrid', 28008, 1.23, 3),
(2, '12345678A', 'Avenida Valvanera', 'Madrid', 28047, 1.15, 50);

INSERT INTO 'Pago' ('dni', 'importe', 'fechaPago')
VALUES
  ('12345678A',  25.00, '2025-04-20'),
  ('12345678C',  40.50, '2025-04-22'),
  ('12345678P', 100.75, '2025-05-01');





