<?php

require_once __DIR__ . '/Aplicacion.php';

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', 'localhost'); //Si falla sustituir por 127.0.0.1
define('BD_NAME', 'ePark');
define('BD_USER', 'adminDB');
define('BD_PASS', 'adminDBPassword');

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

// Inicializa la aplicación
$app = Aplicacion::getInstance();
$app->init(['host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS]);

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function([$app, 'shutdown']);