-- Creación del administrador de la base de datos
CREATE USER 'adminDB'@'localhost' IDENTIFIED BY 'adminDBPassword';
GRANT ALL PRIVILEGES ON `ePark` . * TO 'adminDB'@'localhost';

CREATE USER 'adminDB'@'127.0.0.1' IDENTIFIED BY 'adminDBPassword';
GRANT ALL PRIVILEGES ON `ePark` . * TO 'adminDB'@'127.0.0.1';