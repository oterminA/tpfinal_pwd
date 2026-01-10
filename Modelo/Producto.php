<?php

class Producto
{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $mensajeBD; 


    public function __construct()
    {
        $this->idproducto = "";
        $this->pronombre = "";
        $this->prodetalle = "";
        $this->procantstock = "";
        $this->mensajeBD = "";
    }

    public function getIdProducto()
    {
        return $this->idproducto;
    }
    public function getNombreProducto()
    {
        return $this->pronombre;
    }
    public function getDetalleProducto()
    {
        return $this->prodetalle;
    }
    public function getStockProducto()
    {
        return $this->procantstock;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdProducto($idproducto)
    {
        $this->idproducto = $idproducto;
    }
    public function setNombreProducto($pronombre)
    {
        $this->pronombre = $pronombre;
    }
    public function setDetalleProducto($prodetalle)
    {
        $this->prodetalle = $prodetalle;
    }
    public function setStockProducto($procantstock)
    {
        $this->procantstock = $procantstock;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock)
    {
        $this->setIdProducto($idproducto);
        $this->setNombreProducto($pronombre);
        $this->setDetalleProducto($prodetalle);
        $this->setStockProducto($procantstock);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto WHERE idproducto = " . $this->getIdProducto();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['idproducto'], 
                    $row['pronombre'], 
                    $row['prodetalle'], 
                    $row['procantstock']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("producto->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO producto(pronombre, prodetalle, procantstock)  
        VALUES('', 
        '" . $this->getNombreProducto() . "', 
        '" . $this->getDetalleProducto() . "', 
        '" . $this->getStockProducto() . "')";

        if ($base->Iniciar()) {
            if ($laidproducto = $base->Ejecutar($sql)) {
                $this->setIdProducto($laidproducto);
                $resp = true;
            } else {
                $this->setmensajeoperacion("producto->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE producto 
        SET pronombre='" . $this->getNombreProducto() . "', 
            prodetalle='" . $this->getDetalleProducto() . "', 
            procantstock='" . $this->getStockProducto() . "'
        WHERE idproducto='" . $this->getIdProducto() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("producto->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM producto WHERE idproducto=" . $this->getIdProducto();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp= true;
            } else {
                $this->setmensajeoperacion("producto->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Producto(); 
                    $obj->setear($row['idproducto'], 
                    $row['pronombre'], 
                    $row['prodetalle'], 
                    $row['procantstock']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("producto->listar: " . $base->getError());
        }

        return $arreglo;
    }
    

    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID producto: " . $this->getIdProducto() . "\n" .
            "Nombre producto: " . $this->getNombreProducto() . "\n" .
            "Detalle producto: " . $this->getDetalleProducto() . "\n" .
            "Stock producto: " . $this->getStockProducto() ;
        return $mensaje;
    }
}
