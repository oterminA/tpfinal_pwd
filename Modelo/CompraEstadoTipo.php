<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
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

        /**
     * crea una cadena SQL que corresponde a un INSERT
    */
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

        /**
     *se crea una consulta SQL del tipo UPDATE
    */
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

    /**
     * recibe una consulta SQL del tipo DELETE
    */
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

    /**
     * es como un select con una condición, devuelve el arreglo de esa consulta o null
    */
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

        /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idCompraEstado)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $idCompraEstado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idcompraestadotipo'], 
                    $row['cetdescripcion'], 
                    $row['cetdetalle']);
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
