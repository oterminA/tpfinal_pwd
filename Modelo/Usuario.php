<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
class Usuario{
    private $idUsuario;
    private $nombre;
    private $contrasenia;
    private $mail;
    private $deshabilitado; 
    private $mensajeBD;

    public function __construct()
    {
        $this-> idUsuario= "";
        $this-> nombre= "";
        $this-> contrasenia= "";
        $this-> mail= "";
        $this-> deshabilitado= "";
        $this->mensajeBD = "";
    }

    public function getIdUsuario(){
        return $this->idUsuario;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getContrasenia(){
        return $this->contrasenia;
    }
    public function getMail(){
        return $this->mail;
    }
    public function getDeshabilitado(){
        return $this->deshabilitado;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdUsuario($idUsuario){
        $this->idUsuario=$idUsuario;
    }
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }
    public function setContrasenia($contrasenia){
        $this->contrasenia=$contrasenia;
    }
    public function setMail($mail){
        $this->mail=$mail;
    }
    public function setDeshabilitado($deshabilitado){
        $this->deshabilitado=$deshabilitado;
    }
    public function setmensajeoperacion($mensajeBD)
    { //lo que se muestra si hay o no algun error xq es una variable que viene desde la bd
        $this->mensajeBD = $mensajeBD;
    }


    public function setear($idUsuario, $nombre, $contrasenia, $mail, $deshabilitado)
    {
        $this->setIdUsuario($idUsuario);
        $this->setNombre($nombre);
        $this->setContrasenia($contrasenia);
        $this->setMail($mail);
        $this->setDeshabilitado($deshabilitado);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE idusuario = '" . $this->getIdUsuario() . "'";
    
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    
                    $this->setear($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("usuario->cargar: " . $base->getError());
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
        $sql = "INSERT INTO usuario (idusuario, usnombre, uspass, usmail, usdeshabilitado)
        VALUES (
            '',
            '" . $this->getNombre() . "',
            '" . $this->getContrasenia() . "',
            '" . $this->getMail() . "',
            '" . $this->getDeshabilitado() . "'
        )";

        if ($base->Iniciar()) {
            if ($elUsId = $base->Ejecutar($sql)) {
                $this->setIdUsuario($elUsId);
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->insertar: " . $base->getError());
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
        $sql = "UPDATE usuario SET
            usnombre = '" . $this->getNombre() . "',
            uspass = '" . $this->getContrasenia() . "',
            usmail = '" . $this->getMail() . "',
            usdeshabilitado = '" . $this->getDeshabilitado() . "'
        WHERE idusuario = '" . $this->getIdUsuario() . "'";


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
    
    /**
     * recibe una consulta SQL del tipo DELETE
    */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE idusuario='" . $this->getIdUsuario() . "'";

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

        /**
     * es como un select con una condición, devuelve el arreglo de esa consulta o null
    */
    public static function listar($parametro = "") //NO MODIFICAR EL PARAMETRO PORQUE SE ROMPEN COSASSSSS
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario";
    
        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }
    
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Usuario();
                    $obj->setear(
                        $row['idusuario'],
                        $row['usnombre'],
                        $row['uspass'],
                        $row['usmail'],
                        $row['usdeshabilitado']
                    );
                    $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }
    
    /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idUsuario)
    {
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE idusuario = " . $idUsuario;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
        "Id usuario: " . $this->getIdUsuario() . "\n" . 
        "Nombre: " . $this->getNombre() . "\n" . 
        "Contraseña " . $this->getContrasenia() . "\n" . 
        "Mail " . $this->getMail() . "\n" . 
        "Deshabilitado " . $this->getDeshabilitado() . "\n" ;
        return $mensaje;
    }
}

?>