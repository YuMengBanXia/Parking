<?php
require_once __DIR__ . '/includes/config.php';


//$tituloPagina = 'Escoger parkings';

$form = new \es\ucm\fdi\aw\ePark\cogerTicket();
$htmlFormCoger = $form->Manage();



$contenidoPrincipal = <<<EOS
   <h3>Coger Ticket</h3>
   <h5> Importante: si usted entra y sale del parking en cuestión de segundos, aún así se le aplicará una tarifa fija de 0.01€.</h5>
    $htmlFormCoger 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
    src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js">
    </script>
    <script src="JS/tabla.js"></script>
   
EOS;



$tituloPagina = 'Coger Ticket';


require_once __DIR__ . '/includes/vistas/plantilla/plantilla.php';

?>

<script>
    $(document).ready(function() {
        // Inicializar DataTables UNA SOLA VEZ
        var table = $('#tablaParkings').DataTable({
            pageLength: 5,
            order: [[1, 'asc']]
        });

        // Función que calcula distancias y muestra mapas
        function calcularDistanciasYMapas() {
            // Obtener solo las filas visibles (las de la página actual)
            var filasVisibles = $('#tablaParkings tbody tr').filter(function() {
                return $(this).css('display') !== 'none'; // DataTables oculta filas no visibles
            });

            filasVisibles.each(function() {
                var $fila = $(this);
                var lat = parseFloat($fila.data('lat'));
                var lng = parseFloat($fila.data('lng'));

                // 🔹 Calcular distancia (ejemplo con Haversine)
                // Asegúrate de que posUsuario esté definido
                if (typeof posUsuario !== 'undefined' && posUsuario.lat && posUsuario.lng) {
                    var distancia = calcularDistancia(lat, lng, posUsuario.lat, posUsuario.lng);
                    $fila.find('.distancia').text(distancia.toFixed(2) + ' km');
                }

                // 🔹 Inicializar mapa (si aplica)
                var mapaContainer = $fila.find('.mapa-container')[0];
                if (mapaContainer && typeof google !== 'undefined') {
                    // Limpiar el contenedor antes de crear un nuevo mapa
                    $(mapaContainer).empty();
                    new google.maps.Map(mapaContainer, {
                        zoom: 15,
                        center: {
                            lat: lat,
                            lng: lng
                        },
                    });
                }
            });
        }

        // Ejecutar al cargar la tabla (primera página)
        calcularDistanciasYMapas();

        // Ejecutar cada vez que la tabla se redibuja (cambio de página, ordenación, filtro...)
        table.on('draw.dt', function() {
            setTimeout(function() {
                calcularDistanciasYMapas(); // Espera un poco a que el DOM se actualice
            }, 50);
        });
    });

    // Función para calcular distancia (ejemplo)
    function calcularDistancia(lat1, lon1, lat2, lon2) {
        // Implementación de la fórmula Haversine
        const R = 6371; // Radio de la Tierra en km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
</script>