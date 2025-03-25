<?php
namespace es\ucm\fdi\aw\ePark;

/**
 * Clase que mantiene el estado global de la aplicación.
 */
class Aplicacion
{
	const DATOS_SESION = 'datosSesion';

	private static $instancia;

	/**
	 * @var array Almacena los datos de configuración de la BD
	 */
	private $bdDatosConexion;

	/**
	 * Almacena si la Aplicacion ya ha sido inicializada.
	 * 
	 * @var boolean
	 */
	private $inicializada = false;

	/**
	 * @var \mysqli Conexión de BD.
	 */
	private $conn;

	/**
	 * @var array Tabla asociativa con los datos pendientes de la petición.
	 */
	private $datosSesion;

	/**
	 * Evita que se pueda instanciar la clase directamente.
	 */
	private function __construct() {}

	/**
	 * Comprueba si la aplicación está inicializada. Si no lo está muestra un mensaje y termina la ejecución.
	 */
	private function compruebaInstanciaInicializada()
	{
		if (!$this->inicializada) {
			echo "Aplicacion no inicializa";
			exit();
		}
	}

	/**
	 * Devuele una instancia de {@see Aplicacion}.
	 * 
	 * @return Aplicacion Obtiene la única instancia de la <code>Aplicacion</code>
	 */
	public static function getInstance()
	{
		if (!self::$instancia instanceof self) {
			self::$instancia = new static();
		}
		return self::$instancia;
	}

	/**
	 * Inicializa la aplicación.
	 *
	 * Opciones de conexión a la BD:
	 * <table>
	 *   <thead>
	 *     <tr>
	 *       <th>Opción</th>
	 *       <th>Descripción</th>
	 *     </tr>
	 *   </thead>
	 *   <tbody>
	 *     <tr>
	 *       <td>host</td>
	 *       <td>IP / dominio donde se encuentra el servidor de BD.</td>
	 *     </tr>
	 *     <tr>
	 *       <td>bd</td>
	 *       <td>Nombre de la BD que queremos utilizar.</td>
	 *     </tr>
	 *     <tr>
	 *       <td>user</td>
	 *       <td>Nombre de usuario con el que nos conectamos a la BD.</td>
	 *     </tr>
	 *     <tr>
	 *       <td>pass</td>
	 *       <td>Contraseña para el usuario de la BD.</td>
	 *     </tr>
	 *   </tbody>
	 * </table>
	 * 
	 * @param array $bdDatosConexion datos de configuración de la BD
	 */
	public function init($bdDatosConexion)
	{
		if (!$this->inicializada) {
			$this->bdDatosConexion = $bdDatosConexion;
			$this->inicializada = true;
			session_start();
			/* 
			* Se inicializa los datos asociados a la petición en base a la sesión y se eliminan para que
			* no estén disponibles después de la gestión de esta petición.
			*/
			$this->datosSesion = $_SESSION[self::DATOS_SESION] ?? [];
			unset($_SESSION[self::DATOS_SESION]);
		}
	}

	/**
	 * Devuelve una conexión a la BD. Se encarga de que exista como mucho una conexión a la BD por petición.
	 * 
	 * @return \mysqli Conexión a MySQL.
	 */
	public function getConexionBd()
	{
		$this->compruebaInstanciaInicializada();
		if (!$this->conn) {
			$bdHost = $this->bdDatosConexion['host'];
			$bdUser = $this->bdDatosConexion['user'];
			$bdPass = $this->bdDatosConexion['pass'];
			$bdName = $this->bdDatosConexion['bd'];

			$conn = new \mysqli($bdHost, $bdUser, $bdPass, $bdName);
			if ($conn->connect_errno) {
				echo "Error de conexión a la BD ({$conn->connect_errno}):  {$conn->connect_error}";
				exit();
			}
			if (! $conn->set_charset("utf8mb4")) {
				echo "Error al configurar la BD ({$conn->errno}):  {$conn->error}";
				exit();
			}
			$this->conn = $conn;
		}
		return $this->conn;
	}

	/**
	 * Cierre de la aplicación.
	 */
	public function shutdown()
	{
		$this->compruebaInstanciaInicializada();
		if ($this->conn !== null && ! $this->conn->connect_errno) {
			$this->conn->close();
		}
	}

	/**
	 * Añade un <code>$valor</code> para que esté disponible en la siguiente petición bajo la clave <code>$clave</code>.
	 * 
	 * @param string $clave Clave bajo la que almacenar el valor.
	 * @param any    $valor Valor a almacenar.
	 * 
	 */
	public function putAtributoPeticion($clave, $valor)
	{
		// Si no existe la el array de datos de la sesión, la creamos
		if (!isset($_SESSION[self::DATOS_SESION])) {
			$_SESSION[self::DATOS_SESION] = [];
		}
		// Añadimos el atributo a la sesión
		$_SESSION[self::DATOS_SESION][$clave] = $valor;
	}

	/**
	 * Devuelve un dato establecido en la petición actual o en la petición justamente anterior.
	 * 
	 * @param string $clave Clave sobre la que buscar el dato.
	 * 
	 * @return any Dato asociado a la sesión bajo la clave <code>$clave</code> o <code>null</code> si no existe.
	 */
	public function getAtributoPeticion($clave)
	{
		$result = $this->datosSesion[$clave] ?? null;
		if (is_null($result) && isset($_SESSION[self::DATOS_SESION])) {
			$result = $_SESSION[self::DATOS_SESION][$clave] ?? null;
		}
		return $result;
	}
}
