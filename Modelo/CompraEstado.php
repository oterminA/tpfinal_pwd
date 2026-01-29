<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
//hay delegación
class CompraEstado
{
    private $idcompraestado;
    private $objcompra; //ref a clase compra
    private $objcet; //referencia a clase compra estado tipo
    private $cefechaini;
    private $cefechafin;
    private $mensajeBD;


    public function __construct()
    {
        $this->idcompraestado = "";
        $this->objcompra = null;
        $this->objcet = null;
        $this->cefechaini =  "";
        $this->cefechafin =  "";
        $this->mensajeBD = "";
    }

    public function getIdCompraEstado()
    {
        return $this->idcompraestado;
    }
    public function getObjCompra()
    {
        return $this->objcompra;
    }
    public function getObjCet()
    {
        return $this->objcet;
    }
    public function getFechaIni()
    {
        return $this->cefechaini;
    }
    public function getFechaFin()
    {
        return $this->cefechafin;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdCompraEstado($idcompraestado)
    {
        $this->idcompraestado = $idcompraestado;
    }
    public function setObjCompra($objcompra)
    {
        $this->objcompra = $objcompra;
    }
    public function setObjCet($objcet)
    {
        $this->objcet = $objcet;
    }
    public function setFechaIni($cefechaini)
    {
        $this->cefechaini = $cefechaini;
    }
    public function setFechaFin($cefechafin)
    {
        $this->cefechafin = $cefechafin;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idcompraestado, $objcompra, $objcet, $cefechaini, $cefechafin)
    {
        $this->setIdCompraEstado($idcompraestado);
        $this->setObjCompra($objcompra);
        $this->setObjCet($objcet);
        $this->setFechaIni($cefechaini);
        $this->setFechaFin($cefechafin);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado WHERE idcompraestado = '" . $this->getIdCompraEstado() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objcet = new CompraEstadoTipo();
                    $objcet->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                    $objcet->cargar();

                    $this->setear(
                        $row['idcompraestado'], $objcompra, $objcet,
                        $row['cefechaini'],
                        $row['cefechafin']
                    );
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("compraestado->cargar: " . $base->getError());
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
        $sql = "INSERT INTO compraestado(objcompra, idcompraestadotipo, cefechaini, cefechafin)
        VALUES (
            '',
            '" . $this->getObjCompra()->getIdCompra() . "',
            '" . $this->getObjCet()->getIdCompraEstadoTipo() . "',
            '" . $this->getFechaIni() . "',
            '" . $this->getFechaFin() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidcompraestado = $base->Ejecutar($sql)) {
                $this->setidcompraestado($elidcompraestado);
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraestado->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestado->insertar: " . $base->getError());
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
        $sql = "UPDATE compraestado SET
            idcompra = '" . $this->getObjCompra()->getIdCompra() . "',
            idcompraestadotipo = '" . $this->getObjCompraEstadoTipo()->getIdUsuario() . "',
            cefechaini = '" . $this->getFechaIni() . "',
            cefechafin = '" . $this->getFechaFin() . "',
        WHERE idcompraestado = '" . $this->getIdCompraEstado() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraestado->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestado->modificar: " . $base->getError());
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
        $sql = "DELETE FROM compraestado WHERE idcompraestado='" . $this->getIdCompraEstado() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraestado->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraestado->eliminar: " . $base->getError());
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
        $sql = "SELECT * FROM compraestado ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new CompraEstado();

                    $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objcet = new CompraEstadoTipo();
                    $objcet->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                    $objcet->cargar();

                    $obj->setear($row['idcompraestado'], $objcompra, $objcet, $row['cefechaini'], $row['cefechafin']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("compraestado->listar: " . $base->getError()); 
        }

        return $arreglo;
    }

        /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idCompraEstado)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraestado WHERE idcompraestado = " . $idCompraEstado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objcet = new CompraEstadoTipo();
                    $objcet->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                    $objcet->cargar();                    

                    $this->cargar($row['idcompraestado'], $objcompra, $objcet, $row['cefechaini'], $row['cefechafin']);
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
            "ID estado compra: " . $this->getIdCompraEstado() . "\n" .
            "Datos compra----\n" . $this->getObjCompra() . "\n" . 
            "Datos tipo estado compra----\n" . $this->getObjCet() . "\n" . 
            "Fecha inicio: " . $this->getFechaIni() . "\n" .
            "Fecha fin: " . $this->getFechaFin() . "\n" ;
        return $mensaje;
    }
}
