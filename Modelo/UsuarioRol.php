<?php
//hay delegación
class UsuarioRol
{
    private $idusuariorol;
    private $idusuario; //ref a clase usuario
    private $idrol; //referencia a clase rol
    private $mensajeBD;


    public function __construct()
    {
        $this->idusuariorol = "";
        $this->idusuario = null;
        $this->idrol = null;
        $this->mensajeBD = "";
    }

    public function getIdUsuarioRol()
    {
        return $this->idusuariorol;
    }
    public function getObjUsuario()
    {
        return $this->idusuario;
    }
    public function getObjRol()
    {
        return $this->idrol;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdUsuarioRol($idusuariorol)
    {
        $this->idusuariorol = $idusuariorol;
    }
    public function setObjUsuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function setObjRol($idrol)
    {
        $this->idrol = $idrol;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idusuariorol, $idusuario, $idrol)
    {
        $this->setIdusuariorol($idusuariorol);
        $this->setObjUsuario($idusuario);
        $this->setObjRol($idrol);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol WHERE idusuariorol = '" . $this->getIdUsuarioRol() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $idusuario = new Usuario();
                    $idusuario->setIdUsuario($row['idusuario']);
                    $idusuario->cargar();

                    $idrol = new Rol();
                    $idrol->setIdRol($row['idrol']);
                    $idrol->cargar();

                    $this->setear(
                        $row['idusuariorol'], $idusuario, $idrol);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("usuariorol->cargar: " . $base->getError());
        }
        return $resp;
    }


    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuariorol(idusuario, idrol)
        VALUES (
            '',
            '" . $this->getObjUsuario()->getIdUsuario() . "',
            '" . $this->getObjRol()->getIdRol() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidusuariorol = $base->Ejecutar($sql)) {
                $this->setIdusuariorol($elidusuariorol);
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuariorol SET
            idusuario = '" . $this->getObjUsuario()->getIdUsuario() . "',
            idrol = '" . $this->getObjRol()->getIdRol() . "',
        WHERE idusuariorol = '" . $this->getIdUsuarioRol() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->modificar: " . $base->getError());
        }
        return $resp;
    }


    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuariorol WHERE idusuariorol='" . $this->getIdUsuarioRol() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->eliminar: " . $base->getError());
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
                    $obj = new UsuarioRol();

                    $idusuario = new Usuario();
                    $idusuario->setIdUsuario($row['idusuario']);
                    $idusuario->cargar();

                    $idrol = new Rol();
                    $idrol->setIdRol($row['idrol']);
                    $idrol->cargar();

                    $obj->setear(
                        $row['idusuariorol'], $idusuario, $idrol);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("usuariorol->listar: " . $base->getError()); 
        }

        return $arreglo;
    }


    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID relación entre usuario y rol " . $this->getIdUsuarioRol() . "\n" .
            "Datos usuario relacionado----\n" . $this->getObjUsuario() . "\n" . 
            "Datos rol relacionado----\n" . $this->getObjRol() . "\n" ;
        return $mensaje;
    }
}
