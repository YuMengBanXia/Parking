<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Parámetros de conexión a la BD
 */

 
define('BD_HOST', 'vm009.db.swarm.test'); //Si falla sustituir por 127.0.0.1
define('BD_NAME', 'ePark');
define('BD_USER', 'ePark');
define('BD_PASS', 'aw2025');



/*
//Temporal, para la base de datos local hasta que se arregle el servidor

define('BD_HOST', 'localhost'); //Si falla sustituir por 127.0.0.1
define('BD_NAME', 'ePark');
define('BD_USER', 'adminDB');
define('BD_PASS', 'adminDBPassword');
*/


/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 * 
 * @const RAIZ_APP: Ruta hasta el sistema de archivos (directorio includes)
 * @const RUTA_APP: Ruta hasta la aplicación (directorio Parking)
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '/GitHub/Parking');
define('RUTA_IMGS', RUTA_APP . '/img');
define('RUTA_CSS', RUTA_APP . '/CSS');
define('RUTA_JS', RUTA_APP . '/js');

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {
    
    // project-specific namespace prefix
    $prefix = 'es\\ucm\\fdi\\aw\\ePark\\';
    
    // base directory for the namespace prefix
    $base_dirs = [
        __DIR__ . '/',
        __DIR__ . '/clases/',
        __DIR__ . '/clases/login/',
        __DIR__ . '/clases/parking/',
        __DIR__ . '/clases/ticket/',
        __DIR__ . '/clases/usuario/',
        __DIR__ . '/clases/cogerTicket/',
        __DIR__ . '/clases/administracionParkings/',
        __DIR__ . '/vistas/comun/'
    ];
    
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    
    // get the relative class name
    $relative_class = substr($class, $len);
    
    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with ".php"
    $file_path = str_replace('\\', '/', $relative_class) . '.php';
    foreach ($base_dirs as $base_dir) {
        $file = $base_dir . $file_path;
        // if the file exists, require it
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Inicializa la aplicación
$app = es\ucm\fdi\aw\ePark\Aplicacion::getInstance();
$app->init(['host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS]);

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function([$app, 'shutdown']);