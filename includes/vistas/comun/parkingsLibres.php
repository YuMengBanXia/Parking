<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mostrar Tickets</title>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <h2>  Escoja uno de los parkings que tenemos con plazas disponibles para usted</h2>
        <?php
        require_once __DIR__.'/../../clases/TOparking.php';

       
        $parkings = [];
        $parkings = SAParking::mostrarParkingsLibres();

        //Prueba para ver el mostrado 
        //array_push($parkings, new TOParking(1234, "Calle Juan", "Madrid", 55555, 1, 100));
        //array_push($parkings, new TOParking(1111, "Calle Burgos", "Madrid", 55555, 1, 100));
        
        if (empty($parkings)) {
            echo "<p>No hay plazas libres</p>";
        } else {
        ?>
            <table >
            <tr>
                <td>Direccion</th>
                <td>Ciudad</th>
                <td>Tarifa (€)</th>
                <td>Plazas</th>
                <td>Acción</th>
                    
            </tr>           
               
            <tr>
                <?php foreach ($parkings as $parking): ?>
                        
                            <td><?= $parking->getId(); ?></td>
                            <td><?= htmlspecialchars($parking->getDir()); ?></td>
                            <td><?= htmlspecialchars($parking->getCiudad()); ?></td>
                            <td><?= $parking->getPrecio(); ?> €</td>
                            <td><?= htmlspecialchars($parking->getNPlazas()); ?></td>
                            <td>
                                <form action="ticket.php" method="POST">
                                    <input type="hidden" name="parking_id" value="<?= htmlspecialchars($parking->getId()); ?>">
                                    <button type="submit">Seleccionar</button>
                                </form>
                            </td>
                        </tr>
                <?php endforeach; ?>
                
            </table>
        <?php
        }
        ?>
    </div>
</body>
</html>
