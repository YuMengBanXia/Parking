<?php

namespace es\ucm\fdi\aw\ePark;

abstract class DAO 
 {
    public function __construct()
    {
    }     

    protected function realEscapeString($field)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        return $conn->real_escape_string($field);
    }

    protected function ExecuteQuery($sql)
    {
        if($sql != "")
        {
            $conn = Aplicacion::getInstance()->getConexionBd();

            $rs = $conn->query($sql);

            $tablaDatos = array();
            
            while ($fila = $rs->fetch_assoc())
            {  
                array_push($tablaDatos, $fila);
            }
                
            return $tablaDatos;
        } 
        else
        {
            return false;
        }
    }

    protected function ExecuteCommand($sql)
    {
        if($sql != "")
        {
            $conn = Aplicacion::getInstance()->getConexionBd();

            if ( $conn->query($sql))
            {
                return $conn;
            }
        }

        return false;
    }
 }

 ?>