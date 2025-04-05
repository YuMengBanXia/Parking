<?php
require_once __DIR__ . '/includes/config.php';

$contenidoPrincipal = <<<EOS
    
      <h1>Detalles</h1>

   
    <div id="containerFlex">
        <div class="box">

            <h2>Introducción</h2>
            <ul>
                <li>
                    <strong>ePark</strong> :
                    Plataforma web innovadora que facilita la búsqueda y gestión de aparcamientos
                    en tiempo real.
                </li>
                <li>
                    <strong>Características</strong> :
                    Interfaz intuitiva, ayuda a reducir la congestión en las ciudades y
                    mejora la experiencia de estacionamiento.
                </li>
                <li>
                    Enfoque en la <strong>tecnología</strong> y la <strong>optimización</strong>
                    de espacios <strong>única</strong> en el mercado.
                </li>
            </ul>
        </div>

    
       <div class="box">

            <h2>Tipos de Usuarios</h2>
            <ul>
                <li>
                    <strong>Propietarios/Administradores:</strong> Son los dueños o los gestores de los aparcamientos
                    y pueden dar de alta sus espacios en la plataforma para optimizar su gestión.
                </li>
                <li>
                    <strong>Usuarios Registrados:</strong> Pueden reservar, consultar y pagar plazas con facilidad.
                </li>
                <li>
                    <strong>Abonados:</strong> Usuarios frecuentes con suscripciones mensuales, trimestrales o anuales.
                </li>
                <li>
                    <strong>Visitantes:</strong> Son usuarios no registrados y solo pueden llevar a cabo la consulta de
                    plazas disponibles, <strong>sin</strong> poder hacer ni <strong>reservas</strong>
                    ni <strong>pagos</strong> hasta su registro
                </li>
            </ul>

        </div>
    </div>
            <br>
            <h2>Funcionalidades</h2>
            <div class="tabla-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Funcionalidad</th>
                        <th>Descripcion</th>
                        <th>Usuarios</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Registro de los usuarios</td>
                        <td>
                            Permite a los usuarios no registrados crear una cuenta en la plataforma
                            para acceder a todas sus funcionalidades
                        </td>
                        <td>Visitantes</td>
                    </tr>
                    
                    <tr>
                        <td>Registro de los parkings</td>
                        <td>Permite a los propietarios dar de alta nuevos aparcamientos y configurar su disponibilidad</td>
                        <td>Propietarios/Administradores</td>
                    </tr>

                    <tr>
                        <td>Entrada y salida de los parkings</td>
                        <td>
                            Control del acceso de vehículos mediante identificación física de la matrícula
                            permitiendo controlar de manera eficiente el acceso y la salida de los vehículos
                            a los aparcamientos registrados en la plataforma, para realizar el posterior cálculo de
                            la ocupación y las plazas disponibles.
                        </td>
                        <td>Usuarios registrados y abonados</td>
                    </tr>

                    <tr>
                        <td>Consulta de plazas disponibles</td>
                        <td>
                            Permite a los usuarios consultar la disponibilidad de plazas en diferentes aparcamientos de la
                            ciudad
                        </td>
                        <td>Todos</td>
                    </tr>

                    <tr>
                        <td>Administración de abonados</td>
                        <td>
                            Gestión de suscripciones de los usuarios que deseen acceder a tarifas especiales y reservas
                            garantizadas
                        </td>
                        <td>Propietarios/Administradores</td>
                    </tr>

                </tbody>

            </table>
    
   
     



EOS;

$tituloPagina='Detalles';

require_once __DIR__ ."/includes/vistas/plantilla/plantilla.php";


?>

