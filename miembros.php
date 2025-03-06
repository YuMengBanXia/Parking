<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$contenidoPrincipal = <<<EOS
    <h1>MIEMBROS</h1>
    <table>
        <tr>
            <th colspan="2">Mengxia Zhou</th>
        </tr>
        <tr>
            <th>CORREO</th>
            <td><a href="mailto:menzho01@ucm.es">menzho01@ucm.es</a></td>
        </tr>
        <tr>
            <th>FOTO</th>
            <td><img src="img/mengxia.jpg" class="foto" alt="Foto de Mengxia Zhou"></td>
        </tr>
        <tr>
            <th>DESCRIPCIÓN</th>
            <td>
                Ingeniera informática apasionada por la tecnología y la innovación.
                Me especializo en desarrollo web y disfruto creando soluciones eficientes y accesibles.
            </td>
        </tr>
    </table>
    <p>&ensp;</p>
    <table>
        <tr>
            <th colspan="2">Javier Zhou Zhu</th>
        </tr>
        <tr>
            <th>CORREO</th>
            <td><a href="mailto:javierzh@ucm.es">javierzh@ucm.es</a></td>
        </tr>
        <tr>
            <th>FOTO</th>
            <td><img src="img/javier.jpg" class="foto" alt="Foto de Javier Zhou Zhu"></td>
        </tr>
        <tr>
            <th>DESCRIPCIÓN</th>
            <td>
                Responsable de diseñar y desarrollar la interfaz y estructura del sitio,
                asegurando una experiencia intuitiva y eficiente para los usuarios.
                Además, encargado de la integración con APIs para el reconocimiento de matrículas y gestión de parkings,
                garantizando un diseño responsive y accesible en múltiples dispositivos.
            </td>
        </tr>
    </table>
    <p>&ensp;</p>
    <table>
        <tr>
            <th colspan="2">Xinyang Wang</th>
        </tr>
        <tr>
            <th>CORREO</th>
            <td><a href="mailto:xinyangw@ucm.es">xinyangw@ucm.es</a></td>
        </tr>
        <tr>
            <th>FOTO</th>
            <td><img src="img/xinyang.jpg" class="foto" alt="Foto de Xinyang Wang"></td>
        </tr>
        <tr>
            <th>DESCRIPCIÓN</th>
            <td>
                Entusiasta del diseño y la comunicación digital, se especializa en crear contenido atractivo y
                estrategias creativas para conectar con la audiencia. Su enfoque dinámico y su pasión por la innovación
                hacen que cada proyecto destaque.
            </td>
        </tr>
    </table>
    <p>&ensp;</p>
    <table>
        <tr>
            <th colspan="2">Da Ye</th>
        </tr>
        <tr>
            <th>CORREO</th>
            <td><a href="mailto:daye@ucm.es">daye@ucm.es</a></td>
        </tr>
        <tr>
            <th>FOTO</th>
            <td><img src="img/da.jpg" class="foto" alt="Foto de Da Ye"></td>
        </tr>
        <tr>
            <th>DESCRIPCIÓN</th>
            <td>
                Apasionado por la tecnología y el desarrollo web, tengo algo de experiencia en diseño y programación.
                Siempre en busca de mejorar la experiencia del usuario, combina creatividad y funcionalidad en cada
                proyecto.
            </td>
        </tr>
    </table>

EOS;

$tituloPagina='Miembros';

require_once __DIR__ ."/includes/vistas/plantilla/plantilla.php";


?>

