
<?php

//estos scripts permiten acceder al orm y entregarle informacion a las paginas de la interfaz

class RolController{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['roldescripcion'])) {
            $id = $param['idrol'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
            $obj = new Rol();
            $obj->setear($id, $param['roldescripcion']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idrol'])) { //creo q solo va la clave de la entidad q está en el sql
            $obj = new Rol();
            $obj->setear($param['idrol'], null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idrol'])) //HELP. Tengo q poner todos los atributos?
            $resp = true;
        return $resp;
    }

    /**
     */
    public function alta($param)
    {
        $resp = false;
        $elObjtRol = $this->cargarObjeto($param);
        //        verEstructura($elObjtRol);
        if ($elObjtRol != null and $elObjtRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    
    /**
     * permite eliminar un objeto 
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtRol = $this->cargarObjetoConClave($param);
            if ($elObjtRol != null and $elObjtRol->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     */
    public function modificacion($param)
    {
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtRol = $this->cargarObjeto($param);
            if ($elObjtRol != null and $elObjtRol->modificar()) {
                $resp = true;
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
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
            if (isset($param['roldescripcion']))
                $where .= " and roldescripcion ='" . $param['roldescripcion'] . "'";
        }
        $arreglo = Rol::listar($where);
        return $arreglo;
    }

    /**
     * 
    */
    public function obtenerRol($descripcion){
        $idRol = false; //bandera
        $roles = $this->buscar(['roldescripcion'=>$descripcion]); //buscar me devuelve un array con los roles, ej'admin'
        if ($roles) { //si hay roles
            $objRol = $roles[0]; //recupero un obj rol
            $idRol = $objRol->getIdRol(); //recupero el id de ese rol
        }
        return $idRol; //retorno false o el idrol
}

    /**
     * devuelve falso o el array de usuarios listados
    */
    public function listar($param = "") {
        $resp = false;
        $arregloRoles = Rol::listar($param);
    
        if ($arregloRoles === false) {
            $resp = $arregloRoles; 
        }
    
        return $resp; 
    }
}
