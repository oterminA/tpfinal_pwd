<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
//hay delegación
class compraitem
{
    private $idcompraitem;
    private $objcompra; //ref a clase compra
    private $objproducto; //referencia a clase producto
    private $cicantidad;
    private $mensajeBD;

    public function __construct()
    {
        $this->idcompraitem = "";
        $this->objcompra = null;
        $this->objproducto = null;
        $this->cicantidad =  "";
        $this->mensajeBD = "";
    }

    public function getIdCompraItem()
    {
        return $this->idcompraitem;
    }
    public function getObjCompra()
    {
        return $this->objcompra;
    }
    public function getObjProducto()
    {
        return $this->objproducto;
    }
    public function getCantidad()
    {
        return $this->cicantidad;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdCompraItem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }
    public function setObjCompra($objcompra)
    {
        $this->objcompra = $objcompra;
    }
    public function setObjProducto($objproducto)
    {
        $this->objproducto = $objproducto;
    }
    public function setCantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idcompraitem, $objcompra, $objproducto, $cicantidad)
    {
        $this->setIdCompraItem($idcompraitem);
        $this->setObjCompra($objcompra);
        $this->setObjProducto($objproducto);
        $this->setCantidad($cicantidad);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = '" . $this->getIdCompraItem() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objproducto = new Producto();
                    $objproducto->setIdProducto($row['idproducto']);
                    $objproducto->cargar();

                    $this->setear(
                        $row['idcompraitem'], $objcompra, $objproducto,
                        $row['cicantidad']
                    );
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("compraitem->cargar: " . $base->getError());
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
        $sql = "INSERT INTO compraitem(objcompra, idproducto, cicantidad)
        VALUES (
            '',
            '" . $this->getObjCompra()->getIdCompra() . "',
            '" . $this->getObjProducto()->getIdProducto() . "',
            '" . $this->getCantidad() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidcompraitem = $base->Ejecutar($sql)) {
                $this->setIdCompraItem($elidcompraitem);
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraitem->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraitem->insertar: " . $base->getError());
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
        $sql = "UPDATE compraitem SET
            idcompra = '" . $this->getObjCompra()->getIdCompra() . "',
            idproducto = '" . $this->getObjProducto()->getIdProducto() . "',
            cicantidad = '" . $this->getCantidad() . "',
        WHERE idcompraitem = '" . $this->getIdCompraItem() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraitem->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraitem->modificar: " . $base->getError());
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
        $sql = "DELETE FROM compraitem WHERE idcompraitem='" . $this->getIdCompraItem() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraitem->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraitem->eliminar: " . $base->getError());
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
        $sql = "SELECT * FROM compraitem ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new CompraItem();

                   $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objproducto = new Producto();
                    $objproducto->setIdProducto($row['idproducto']);
                    $objproducto->cargar();

                    $obj->setear(
                        $row['idcompraitem'], $objcompra, $objproducto,
                        $row['cicantidad']
                    );
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("compraitem->listar: " . $base->getError()); 
        }

        return $arreglo;
    }

 /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idCompraItem)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraitem WHERE idcompraitem = " . $idCompraItem;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $objcompra = new Compra();
                    $objcompra->setIdCompra($row['idcompra']);
                    $objcompra->cargar();

                    $objproducto = new Producto();
                    $objproducto->setIdProducto($row['idproducto']);
                    $objproducto->cargar();

                    $this->cargar(
                        $row['idcompraitem'], $objcompra, $objproducto,
                        $row['cicantidad']
                    );
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
            "ID ítem compra: " . $this->getIdcompraitem() . "\n" .
            "Datos compra----\n" . $this->getObjCompra() . "\n" . 
            "Datos producto----\n" . $this->getobjproducto() . "\n" . 
            "ítem cantidad: " . $this->getCantidad() . "\n" ;
        return $mensaje;
    }
}
