<?php   
        //Prueba para ver el mostrado 
        //$parkings =[];
        //array_push($parkings, new TOParking(1234, "Calle Juan", "Madrid", 55555, 1, 100));
        //array_push($parkings, new TOParking(1111, "Calle Burgos", "Madrid", 55555, 1, 100));
        SAParking::inicializar();
        $parkings = SAParking::mostrarParkingsLibres();

        if (empty($parkings)) {
            echo "<p>No hay plazas libres</p>";
        } else {
            echo "<p> Los siguientes parkings tienen plazas libres</p>";
        ?>
            <table >
            <tr>
                <td>ID</th>
                <td>DIRECCION</th>
                <td>CIUDAD</th>
                <td>TARIFA</th>
                <td>PLAZAS DISPONIBLES</th>
                    
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