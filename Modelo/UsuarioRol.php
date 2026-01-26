
<?php

class UsuarioRol{
    private $id;
    private $objRol;//delegacion x la relacion n:m
    private $objUsuario;//delegacion x la relacion n:m
    private $mensajeBD;

    public function __construct()
    {
        $this-> id= "";
        $this-> objRol= null;
        $this-> objUsuario= null;
        $this->mensajeBD = "";
    }

    public function getId(){
        return $this->id;
    }
    public function getObjUsuario(){
        return $this->objUsuario;
    }
    public function getObjRol(){
        return $this->objRol;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setId($id){
        $this->id=$id;
    }
    public function setObjUsuario($objUsuario){
        $this->objUsuario=$objUsuario;
    }
    public function setObjRol($objRol){
        $this->objRol=$objRol;
    }
    public function setmensajeoperacion($mensajeBD)
    { //lo que se muestra si hay o no algun error xq es una variable que viene desde la bd
        $this->mensajeBD = $mensajeBD;
    }


    public function setear($id, $objRol, $objUsuario)
    {
        $this->setId($id);
        $this->setObjRol($objRol);
        $this->setObjUsuario($objUsuario);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol WHERE id = '" . $this->getId() . "'";
    
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    
                    $this->setear($row['id'], $row['idrol'], $row['idusuario']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("usuariorol->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function buscar($id){
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol WHERE idusuario = " . $id;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['id'], $row['idrol'], $row['idusuario']);
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

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $idUsuario = $this->getObjUsuario()->getIdUsuario();
        $idRol = $this->getObjRol()->getIdRol();
        $sql = "INSERT INTO usuariorol (idrol, idusuario)
        VALUES (
            '" . $idRol . "',
            '" .  $idUsuario . "'
        )";

        if ($base->Iniciar()) {
            if ($elId = $base->Ejecutar($sql)) {
                $this->setId($elId);
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
        $idUsuario = $this->getObjUsuario()->getIdUsuario();
        $idRol = $this->getObjRol()->getIdRol();
        $sql = "UPDATE usuariorol SET
            idrol = 
            '" . $idRol . "',
            idusuario = 
            '" . $idUsuario . "'

        WHERE id = '" . $this->getId() . "'";


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
        $sql = "DELETE FROM usuariorol WHERE id='" . $this->getId() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp= true;
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
        $sql = "SELECT * FROM usuariorol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new UsuarioRol();
                    
                    $objUsuario = new Usuario();
                    $objUsuario->setIdUsuario($row['idusuario']);
                    $objUsuario->cargar(); 
                
                    $objRol = new Rol();
                    $objRol->setIdRol($row['idrol']);
                    $objRol->cargar(); 
                
                    $obj->setear($row['id'], $objRol, $objUsuario);
                    array_push($arreglo, $obj);
                }
                
            }
        } else {
            self::setmensajeoperacion("usuariorol->listar: " . $base->getError()); //la misma forma de arreglar ese error que antes?
        }
        return $arreglo;
    }

    //to string
    public function __toString()
    {
        $mensaje = 
        "Id: " . $this->getId() . "\n" . 
        "Rol---\n" . $this->getObjRol() . "\n" . 
        "Usuario---\n" . $this->getObjUsuario() . "\n" ;
        return $mensaje;
    }
}

?>
