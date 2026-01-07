<?php

class Rol
{
    private $idrol;
    private $rodescripcion;
    private $mensajeBD; 


    public function __construct()
    {
        $this->idrol = "";
        $this->rodescripcion = "";
        $this->mensajeBD = "";
    }

    public function getIdRol()
    {
        return $this->idrol;
    }
    public function getRodescripcion()
    {
        return $this->rodescripcion;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdRol($idrol)
    {
        $this->idrol = $idrol;
    }
    public function setRodescripcion($rodescripcion)
    {
        $this->rodescripcion = $rodescripcion;
    }
    public function setmensajeoperacion($mensajeBD)
    { 
        $this->mensajeBD = $mensajeBD;
    }

    public function setear($idrol, $rodescripcion)
    {
        $this->setIdRol($idrol);
        $this->setRodescripcion($rodescripcion);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol WHERE idrol = " . $this->getIdRol();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['idrol'], $row['rodescripcion']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("rol->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO rol(idrol, rodescripcion)  
        VALUES('" . $this->getIdRol() . "', 
        '" . $this->getRodescripcion() . "')";

        if ($base->Iniciar()) {
            if ($elidrol = $base->Ejecutar($sql)) {
                $this->setIdRol($elidrol);
                $resp = true;
            } else {
                $this->setmensajeoperacion("rol->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE rol 
        SET rodescripcion='" . $this->getRodescripcion() . "'
        WHERE idrol='" . $this->getIdRol() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("rol->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM rol WHERE idrol=" . $this->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setmensajeoperacion("rol->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new rol(); 
                    $obj->setear($row['idrol'], $row['rodescripcion']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("rol->listar: " . $base->getError()); 
        }

        return $arreglo;
    }
    

    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID rol: " . $this->getIdRol() . "\n" .
            "Descripción rol: " . $this->getRodescripcion() . "\n" ;
        return $mensaje;
    }
}
