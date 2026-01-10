<?php
class Usuario
{
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usdeshabilitado;
    private $usmail;
    private $mensajeBD;


    public function __construct()
    {
        $this->idusuario = "";
        $this->usnombre = "";
        $this->uspass = "";
        $this->usdeshabilitado = "";
        $this->usmail = "";
        $this->mensajeBD = "";
    }

    public function getIdUsuario()
    {
        return $this->idusuario;
    }
    public function getUsNombre()
    {
        return $this->usnombre;
    }
    public function getUsPass()
    {
        return $this->uspass;
    }
    public function getDeshabilitado()
    {
        return $this->usdeshabilitado;
    }
    public function getUsMail()
    {
        return $this->usmail;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdUsuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function setUsNombre($usnombre)
    {
        $this->usnombre = $usnombre;
    }
    public function setUsPass($uspass)
    {
        $this->uspass = $uspass;
    }
    public function setDeshabilitado($usdeshabilitado)
    {
        $this->usdeshabilitado = $usdeshabilitado;
    }
    public function setUsMail($usmail)
    {
        $this->usmail = $usmail;
    }
    public function setmensajeoperacion($mensajeBD)
    { 
        $this->mensajeBD = $mensajeBD;
    }

    public function setear($idusuario, $usnombre, $uspass, $usdeshabilitado, $usmail)
    {
        $this->setIdUsuario($idusuario);
        $this->setUsNombre($usnombre);
        $this->setUsPass($uspass);
        $this->setDeshabilitado($usdeshabilitado);
        $this->setUsMail($usmail);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE idusuario = " . $this->getIdUsuario();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear(
                        $row['idusuario'], 
                        $row['usnombre'], 
                        $row['uspass'], 
                        $row['usmail'], 
                        $row['usdeshabilitado']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("usuario->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuario(usnombre, uspass, usmail, usdeshabilitado)  
        VALUES('', '" 
        . $this->getUsNombre() . "', '" 
        . $this->getUsPass() . "', '" 
        . $this->getUsMail() . "', '" 
        . $this->getDeshabilitado() . "')";

        if ($base->Iniciar()) {
            if ($elIdUsuario = $base->Ejecutar($sql)) {
                $this->setIdUsuario($elIdUsuario);
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuario 
        SET usnombre='" . $this->getUsNombre() . "', 
            uspass='" . $this->getUsPass() . "', 
            usmail='" . $this->getUsMail() . "', 
            usdeshabilitado='" . $this->getDeshabilitado() . "' 
        WHERE idusuario='" . $this->getIdUsuario() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->modificar: " . $base->getError());
        }
        return $resp;
    }


    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE idusuario=" . $this->getIdUsuario();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp= true;
            } else {
                $this->setmensajeoperacion("usuario->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->eliminar: " . $base->getError());
        }
        return $resp;
    }


    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new Usuario(); 
                    $obj->setear($row['idusuario'], 
                    $row['usnombre'], 
                    $row['uspass'], 
                    $row['usmail'], 
                    $row['usdeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("usuario->listar: " . $base->getError());
        }

        return $arreglo;
    }
    

    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID usuario: " . $this->getIdUsuario() . "\n" .
            "Nombre usuario: " . $this->getUsNombre() . "\n" .
            "Contraseña usuario: " . $this->getUsPass() . "\n" .
            "Mail usuario: " . $this->getUsMail() . "\n" .
            "Deshabilitado: " . $this->getDeshabilitado() . "\n" ;
        return $mensaje;
    }
}
