<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);


/**
 * Parámetros de conexión a la BD
 */

/*
define('BD_HOST', 'vm009.db.swarm.test'); //Si falla sustituir por 127.0.0.1
define('BD_NAME', 'ePark');
define('BD_USER', 'ePark');
define('BD_PASS', 'aw2025');
*/


// Temporal, para la base de datos local hasta que se arregle el servidor
 define('BD_HOST', 'localhost'); //Si falla sustituir por 127.0.0.1
 define('BD_NAME', 'ePark');
 define('BD_USER', 'adminDB');
 define('BD_PASS', 'adminDBPassword');
 

 /**
 * Validación de configuración de base de datos
 */
if (!defined('BD_HOST') || !defined('BD_NAME') || !defined('BD_USER') || !defined('BD_PASS')) {
    die('Error: Parámetros de conexión a la base de datos no definidos correctamente.');
}

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 * 
 * @const RAIZ_APP: Ruta hasta el sistema de archivos (directorio includes)
 * @const RUTA_APP: Ruta hasta la aplicación (directorio Parking)
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '/Parking');
//define('RUTA_APP', '/GitHub/Parking');
define('RUTA_IMGS', RUTA_APP . '/img');
define('RUTA_CSS', RUTA_APP . '/CSS');
define('RUTA_JS', RUTA_APP . '/JS');

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
        __DIR__ . '/utils/',
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

    // Agregar un registro si el archivo no se encuentra
    error_log("Autoloader: No se pudo cargar la clase $class. Buscado en: $file");
});

// Inicializa la aplicación
$app = es\ucm\fdi\aw\ePark\Aplicacion::getInstance();
$app->init(['host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS]);

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function([$app, 'shutdown']);


function gestorExcepciones(Throwable $exception) 
{
    error_log(jTraceEx($exception)); 

    http_response_code(500);

    $tituloPagina = 'Error';
    $mensajeError=$exception->getMessage();


    $contenidoPrincipal = <<<EOS
    <h1>Oops</h1>
    <p> Parece que ha habido un fallo. Error en $mensajeError</p>
    <a href="index.php" class="btn-link">Ir al inicio</a>
    
    EOS;

    require("includes/vistas/plantilla/plantilla.php");
}

set_exception_handler('gestorExcepciones');

// http://php.net/manual/es/exception.gettraceasstring.php#114980
/**
 * jTraceEx() - provide a Java style exception trace
 * @param Throwable $exception
 * @param string[] $seen Array passed to recursive calls to accumulate trace lines already seen leave as NULL when calling this function
 * @return string  string stack trace, one entry per trace line
 */
function jTraceEx($e, $seen=null) 
{
    $starter = $seen ? 'Caused by: ' : '';
    $result = array();
    if (!$seen) $seen = array();
    $trace  = $e->getTrace();
    $prev   = $e->getPrevious();
    $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
    $file = $e->getFile();
    $line = $e->getLine();
    while (true) {
        $current = "$file:$line";
        if (is_array($seen) && in_array($current, $seen)) {
            $result[] = sprintf(' ... %d more', count($trace)+1);
            break;
        }
        $result[] = sprintf(' at %s%s%s(%s%s%s)',
            count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
            count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
            count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
            $line === null ? $file : basename($file),
            $line === null ? '' : ':',
            $line === null ? '' : $line);
        if (is_array($seen))
            $seen[] = "$file:$line";
        if (!count($trace))
            break;
        $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
        $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
        array_shift($trace);
    }
    $result = join(PHP_EOL , $result);
    if ($prev)
        $result  .= PHP_EOL . jTraceEx($prev, $seen);
        
    return $result;
}
?>