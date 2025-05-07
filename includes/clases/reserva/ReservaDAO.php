<?php
namespace es\ucm\fdi\aw\ePark;

class ReservaDAO extends DAO{
    public static $instancia;
    public $mysqli; 

    public static function getSingleton() { //Patrón Singleton para única instancia de la clase
        if ( !self::$instancia instanceof self) { 
            self::$instancia = new self; 
        } 
        return self::$instancia; 
    }

    public function insert($dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="INSERT INTO Reserva (`dni`, `id`, `fecha_ini`, `fecha_fin`, `matricula`, `importe`) VALUES (?,?,?,?,?,?)";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("sisssd", $dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe);

        $resultado=$stmt->execute();

        $stmt->close();
  
        return $resultado;
    }

    public function ocupacion($id){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="SELECT COUNT(*) FROM Reserva WHERE id=? AND estado='ACTIVA'";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i",$id);

        $stmt->execute();

        $stmt->bind_result($num);

        $stmt->fetch();
        
        $stmt->close();

        return $num;
    }

    public function activar($codigo){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="UPDATE Reserva SET `estado`='ACTIVA' WHERE `codigo`=?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $codigo);

        $resultado=$stmt->execute();

        $stmt->close();
  
        return $resultado;
    }

    public function cancelar($codigo){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="UPDATE Reserva SET `estado`='CANCELADA' WHERE `codigo`=?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $codigo);

        $resultado=$stmt->execute();

        $stmt->close();
  
        return $resultado;
    }

    public function completar($codigo){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query="UPDATE Reserva SET `estado`='COMPLETADA' WHERE `codigo`=?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("i", $codigo);

        $resultado=$stmt->execute();

        $stmt->close();
  
        return $resultado;
    }

    public function getAll() {

        $reservas = [];
    
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "SELECT * FROM Reserva";
    
        $result = $conn->query($query);
    
        while ($row = $result->fetch_assoc()) {
            $reservas[] = new TOReserva(
                $row['codigo'] ?? null,
                $row['dni'] ?? null,
                $row['id'] ?? null,
                $row['fecha_ini'] ?? null,
                $row['fecha_fin'] ?? null,
                $row['matricula'] ?? null,
                $row['importe'] ?? null,
                $row['estado'] ?? null
            );
        }
        
        $result->close();
        
        return $reservas;
    }

    public function getByDni($dni) {

        $reservas = [];

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM Reserva WHERE dni = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("s", $dni);

        $stmt->execute();

        $stmt->bind_result($codigo, $dni, $id, $fecha_ini, $fecha_fin, $matricula, $importe, $estado);

        while ($stmt->fetch()) {
            $reservas[] = new TOReserva(
                $codigo,
                $dni,
                $id,
                $fecha_ini,
                $fecha_fin,
                $matricula,
                $importe,
                $estado
            );
        }

        $stmt->close();
        
        return $reservas;
    }
}
?>