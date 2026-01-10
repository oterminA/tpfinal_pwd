<?php
class CompraEstadoTipo
{
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeBD;


    public function __construct()
    {
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
        $this->mensajeBD = "";
    }

    public function getIdCompraEstadoTipo()
    {
        return $this->idcompraestadotipo;
    }
    public function getDescripcion()
    {
        return $this->cetdescripcion;
    }
    public function getDetalle()
    {
        return $this->cetdetalle;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdCompraEstadoTipo($idcompraestadotipo)
    {
        $this->idcompraestadotipo = $idcompraestadotipo;
    }
    public function setDescripcion($cetdescripcion)
    {
        $this->cetdescripcion = $cetdescripcion;
    }
    public function setDetalle($cetdetalle)
    {
        $this->cetdetalle = $cetdetalle;
    }
    public function setmensajeoperacion($mensajeBD)
    { 
        $this->mensajeBD = $mensajeBD;
    }

    public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle)
    {
        $this->setIdCompraEstadoTipo($idcompraestadotipo);
        $this->setDescripcion($cetdescripcion);
        $this->setDetalle($cetdetalle);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $this->getIdCompraEstadoTipo();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear(
                        $row['idcompraestadotipo'], 
                        $row['cetdescripcion'], 
                        $row['cetdetalle']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("compraestadotipo->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraestadotipo(cetdescripcion, cetdetalle)  
        VALUES('', '" 
        . $this->getDescripcion() . "', '" 
        . $this->getDetalle() . "')";

        if ($base->Iniciar()) {
            if ($elIdCET = $base->Ejecutar($sql)) {
                $this->setIdCompraEstadoTipo($elIdCET);
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraestadotipo->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestadotipo->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraestadotipo 
        SET cetdescripcion='" . $this->getDescripcion() . "', 
            cetdetalle='" . $this->getDetalle() . "' 
        WHERE idcompraestadotipo='" . $this->getIdCompraEstadoTipo() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraestadotipo->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestadotipo->modificar: " . $base->getError());
        }
        return $resp;
    }


    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getIdCompraEstadoTipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp= true;
            } else {
                $this->setmensajeoperacion("compraestadotipo->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestadotipo->eliminar: " . $base->getError());
        }
        return $resp;
    }


    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new CompraEstadoTipo(); 
                    $obj->setear($row['idcompraestadotipo'], 
                    $row['cetdescripcion'], 
                    $row['cetdetalle']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("compraestadotipo->listar: " . $base->getError());
        }

        return $arreglo;
    }
    

    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID estado de la compra: " . $this->getIdCompraEstadoTipo() . "\n" .
            "Descripción: " . $this->getDescripcion() . "\n" .
            "Detalle: " . $this->getDetalle() . "\n" ;
        return $mensaje;
    }
}
