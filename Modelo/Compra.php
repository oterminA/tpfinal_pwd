<?php
//hay delegación
class Compra
{
    private $idcompra;
    private $cofecha;
    private $objusuario; //referencia a clase usuario
    private $mensajeBD;


    public function __construct()
    {
        $this->idcompra = "";
        $this->cofecha = "";
        $this->objusuario = null;
        $this->mensajeBD = "";
    }

    public function getIdCompra()
    {
        return $this->idcompra;
    }
    public function getFecha()
    {
        return $this->cofecha;
    }
    public function getObjUsuario()
    {
        return $this->objusuario;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdCompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }
    public function setFecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }
    public function setObjUsuario($objusuario)
    {
        $this->objusuario = $objusuario;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idcompra, $cofecha, $objusuario)
    {
        $this->setIdCompra($idcompra);
        $this->setFecha($cofecha);
        $this->setObjUsuario($objusuario);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compra WHERE idcompra = '" . $this->getIdCompra() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $objusuario = new Usuario();
                    $objusuario->setIdUsuario($row['idusuario']);
                    $objusuario->cargar();

                    $this->setear(
                        $row['idcompra'],
                        $row['cofecha'],
                        $objusuario
                    );
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("compra->cargar: " . $base->getError());
        }
        return $resp;
    }


    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compra(idcompra, cofecha, idusuario)
        VALUES (
            '" . $this->getIdCompra() . "',
            '" . $this->getFecha() . "',
            '" . $this->getObjUsuario()->getIdUsuario() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidcompra = $base->Ejecutar($sql)) {
                $this->setIdCompra($elidcompra);
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->insertar: " . $base->getError());
        }
        return $resp;
    }



    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compra SET
            cofecha = '" . $this->getFecha() . "',
            idusuario = '" . $this->getObjUsuario()->getIdUsuario() . "'
        WHERE idcompra = '" . $this->getIdCompra() . "'";


        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->modificar: " . $base->getError());
        }
        return $resp;
    }


    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compra WHERE idcompra='" . $this->getIdCompra() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->eliminar: " . $base->getError());
        }
        return $resp;
    }


    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compra ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new compra();

                    $objusuario = new Usuario();
                    $objusuario->setIdUsuario($row['idusuario']);
                    $objusuario->cargar();

                    $obj->setear($row['idcompra'], $row['cofecha'], $objusuario);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("compra->listar: " . $base->getError()); 
        }

        return $arreglo;
    }


    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID compra: " . $this->getIdCompra() . "\n" .
            "Fecha: " . $this->getFecha() . "\n" .
            "Datos usuario----\n" . $this->getFecha() . "\n";
        return $mensaje;
    }
}
