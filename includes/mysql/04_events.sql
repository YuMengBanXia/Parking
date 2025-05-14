-- Activa el scheduler si aún no lo está, importante consultarlo
SET GLOBAL event_scheduler = ON;

DELIMITER $$
-- Cuando una reserva esta pagada y quedan 5 horas para que inicie la reserva, marcarla como activa lo que resta una plaza libre del garaje
CREATE EVENT IF NOT EXISTS ev_activar_reservas
  ON SCHEDULE EVERY 15 MINUTE
  DO
    UPDATE Reserva
    SET estado = 'ACTIVA'
    WHERE estado = 'PAGADA'
      AND fecha_ini <= DATE_ADD(NOW(), INTERVAL 5 HOUR);
$$

-- Cuando una reserva no ha sido pagada y queda un dia para la fecha de inicio, se elimina de la bbdd (politica de cancelacion)
CREATE EVENT IF NOT EXISTS ev_limpiar_reservas
  ON SCHEDULE EVERY 1 HOUR
  DO
    UPDATE Reserva
    SET estado = 'CANCELADA'
    WHERE estado = 'PENDIENTE'
      AND fecha_ini <= DATE_ADD(NOW(), INTERVAL 1 DAY);
$$

-- Se completa la reserva y se registra el pago
CREATE EVENT IF NOT EXISTS ev_completar_y_facturar_reservas
  ON SCHEDULE EVERY 30 MINUTE
  DO
BEGIN
  -- 1) Insertar en Pago (dni, idParking, importe, fechaPago)
  INSERT INTO Pago (dni, idParking, importe, fechaPago)
  SELECT
    park.dni      AS dni_propietario,
    r.id          AS idParking,
    r.importe     AS importe,
    NOW()         AS fechaPago
  FROM Reserva AS r
  JOIN Parking AS park
    ON r.id = park.id
  WHERE r.estado = 'ACTIVA'
    AND r.fecha_fin <= NOW();

  -- 2) Marcar esas reservas como completadas
  UPDATE Reserva AS r
  SET r.estado = 'COMPLETADA'
  WHERE r.estado = 'ACTIVA'
    AND r.fecha_fin <= NOW();
END$$

DELIMITER ;
