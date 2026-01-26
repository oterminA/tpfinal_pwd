<?php

//estos scripts permiten acceder al orm y entregarle informacion a las paginas de la interfaz

class UsuarioController{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['usnombre']) && isset($param['uspass']) && isset($param['usmail'])) {
            $obj = new Usuario();
            $id = $param['idusuario'] ?? null;
            $deshabilitado = $param['usdeshabilitado'] ?? null;
            $obj->setear($id, $param['usnombre'], $param['uspass'], $param['usmail'], $deshabilitado);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idusuario'])) { //creo q solo va la clave de la entidad q está en el sql
            $obj = new Usuario();
            $obj->setear($param['idusuario'], null, null, null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario'])) 
            $resp = true;
        return $resp;
    }

    /**
     * 
     */
    public function alta($param)
    {
        // $resp = false;
        // $elObjtUsuario = $this->cargarObjeto($param);
        // //        verEstructura($elObjtUsuario);
        // if ($elObjtUsuario != null and $elObjtUsuario->insertar()) {
        //     $resp = true;
        // }
        // return $resp;
    $elObjtUsuario = $this->cargarObjeto($param);

    if ($elObjtUsuario != null && $elObjtUsuario->insertar()) {
        return $elObjtUsuario->getIdUsuario();
    }
    return false;
}
    
    /**
     * permite eliminar un objeto 
     */
    public function baja($param)
    {
        // $resp = false;
        // if ($this->seteadosCamposClaves($param)) {
        //     $elObjtUsuario = $this->cargarObjetoConClave($param);
        //     if ($elObjtUsuario != null and $elObjtUsuario->eliminar()) {
        //         $resp = true;
        //     }
        // }

        // return $resp;
        //hago un borrado logico donde deshabilito aluser pero igual guardo el codigo del borrado fisico
        $resp = false; //bandera
        if (isset($param['idusuario'])) { //si el idusuario no es null
            $lista = $this->buscar(['idusuario' => $param['idusuario']]); //guardo un arreglo con lo que devuelve buscar que es un array de objetos con el mismo id
            
            if (count($lista) > 0) { //si el array no es vacio
                $objUsuario = $lista[0]; //recupero un obj usuario
                
                $objUsuario->setDeshabilitado(date("Y-m-d H:i:s")); //seteo la deshabilitacion
                
                if ($objUsuario->modificar()) { //modifico al obj usuario
                    $resp = true;
                }
            }
        }
        return $resp; //devuelvo true o false
    }

    /**
     * permite modificar un objeto
     */
    public function modificacion($param)
    {
        //tuve que modificar la funcion por el action actualizarLogin.php
        $resp = false;
    
        if (isset($param['idusuario'])) {
    
            $lista = $this->buscar(['idusuario' => $param['idusuario']]);
            
            if (count($lista) > 0) {
                $objUsuario = $lista[0];
    
                if (isset($param['usnombre'])) {
                    $objUsuario->setNombre($param['usnombre']);
                }
                if (isset($param['uspass'])) {
                    $objUsuario->setContrasenia($param['uspass']);
                }
                if (isset($param['usmail'])) {
                    $objUsuario->setMail($param['usmail']);
                }
                if (array_key_exists('usdeshabilitado', $param)) {
                    $objUsuario->setDeshabilitado($param['usdeshabilitado']);
                }
    
                if ($objUsuario->modificar()) {
                    $resp = true;
                }
            }
        }
    
        return $resp;
    }
    

    /**
     * permite buscar un objeto
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " and usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['uspass']))
                $where .= " and uspass ='" . $param['uspass'] . "'";
            if (isset($param['usmail']))
                $where .= " and usmail ='" . $param['usmail'] . "'";
            if (isset($param['usdeshabilitado']))
                $where .= " and usdeshabilitado ='" . $param['usdeshabilitado'] . "'";
        }
        $arreglo = Usuario::listar($where);
        return $arreglo;
    }

    /**
     * devuelve un array con las credenciales del user o uno vacio
    */
    public function verificarCredenciales($usuario, $contra){
        $arrayUser= []; //vacio
       $param = ["usnombre"=> $usuario, "uspass"=> $contra]; //hago un array con los datos que vienen x parametro
       $usuario = $this->buscar($param); //pongo this xq elmismo abmusuario tiene que buscar en su funcion buscar
       if(count($usuario)>0){ //si el array tiene algo
        $user = $usuario[0]; //recupero un obj usuario
        if ($user->getDeshabilitado()===null){ //si no está deshabilitado
            $arrayUser = $user; 
        }
       }
       return $arrayUser;//devuelvo un array vacio o lleno
    }

    
    /**
     * devuelve falso o el array de usuarios listados
    */
    public function listar($param = "") {
        $resp = false;
        $arregloUsuarios = Usuario::listar($param);
    
        if ($arregloUsuarios != false) {
            $resp = $arregloUsuarios; 
        }
    
        return $resp; 
    }
}
