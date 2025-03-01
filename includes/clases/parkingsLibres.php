<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mostrar Tickets</title>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <?php
        require_once __DIR__.'/TOparking.php';

        // $parkings = SAParking::mostrarParkingsLibres();
        $parkings = [new TOParking(1234, "Calle Juan", "Madrid", 55555, 1, 100)];

        if (empty($parkings)) {
            echo "<p>No hay plazas libres</p>";
        } else {
        ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Direccion</th>
                        <th>Ciudad</th>
                        <th>Tarifa (€)</th>
                        <th>Plazas</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parkings as $parking): ?>
                        <tr>
                            <td><?= htmlspecialchars($parking->getId()); ?></td>
                            <td><?= htmlspecialchars($parking->getDir()); ?></td>
                            <td><?= htmlspecialchars($parking->getCiudad()); ?></td>
                            <td><?= htmlspecialchars($parking->getPrecio()); ?> €</td>
                            <td><?= htmlspecialchars($parking->getNPlazas()); ?></td>
                            <td>
                                <form action="ticket.php" method="POST">
                                    <input type="hidden" name="parking_id" value="<?= htmlspecialchars($parking->getId()); ?>">
                                    <button type="submit">Seleccionar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
</body>
</html>
