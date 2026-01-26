<?php
class Rol{
    private $idRol;
    private $rolDescripcion;
    private $mensajeBD;

    public function __construct()
    {
        $this-> idRol= "";
        $this-> rolDescripcion= "";
        $this->mensajeBD = "";
    }

    public function getIdRol(){
        return $this->idRol;
    }
    public function getRolDescripcion(){
        return $this->rolDescripcion;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdRol($idRol){
        $this->idRol=$idRol;
    }
    public function setRolDescripcion($rolDescripcion){
        $this->rolDescripcion=$rolDescripcion;
    }
    public function setmensajeoperacion($mensajeBD)
    { //lo que se muestra si hay o no algun error xq es una variable que viene desde la bd
        $this->mensajeBD = $mensajeBD;
    }


    public function setear($idRol, $rolDescripcion)
    {
        $this->setIdRol($idRol);
        $this->setRolDescripcion($rolDescripcion);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol WHERE idrol = '" . $this->getIdRol() . "'";
    
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    
                    $this->setear($row['idrol'], $row['roldescripcion']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("rol->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO rol (idrol,roldescripcion)
        VALUES (
            '',
            '" . $this->getRolDescripcion() . "'
        )";

        if ($base->Iniciar()) {
            if ($elIdRol = $base->Ejecutar($sql)) {
                $this->setIdRol($elIdRol);
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
        $sql = "UPDATE rol SET
            idrol = '" . $this->getIdRol() . "',
            roldescripcion = '" . $this->getRolDescripcion() . "'
        WHERE idrol = '" . $this->getIdRol() . "'";


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
        $sql = "DELETE FROM rol WHERE idrol='" . $this->getIdRol() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp= true;
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
                    $obj = new Rol();
                
                    $obj->setear($row['idrol'], $row['roldescripcion']);
                    array_push($arreglo, $obj);
                }
                
            }
        } else {
            self::setmensajeoperacion("rol->listar: " . $base->getError()); //la misma forma de arreglar ese error que antes?
        }
        return $arreglo;
    }

    public function buscar($idRol)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM rol WHERE idrol = " . $idRol;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idrol'], $row['roldescripcion']);
                    $resp = true;
                }
            } else {
                self::setmensajeoperacion($base->getError());
            }
        } else {
            self::setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    //to string
    public function __toString()
    {
        $mensaje = 
        "Id rol: " . $this->getIdRol() . "\n" . 
        "Rol descripcion " . $this->getRolDescripcion() . "\n" ;
        return $mensaje;
    }
}

?>  