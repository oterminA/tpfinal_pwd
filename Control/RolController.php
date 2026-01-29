
<?php
//estos scripts permiten acceder al orm y entregarle informacion a las paginas de la interfaz
//acá trabajo con objetos y claves-valor porque no estoy sacando la información directamente de la fuente porque el control es un intermediario, a diferencia de la capa del modelo
class RolController{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
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
     * retorna el objeto creado pero solo necesitando su id, no necesita el resto de la info. Lo uso más que nada para dar bajas, verificar que exista el objeto solo buscando su id, donde no preciso del resto de los datos
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
     *genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
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
     *permite eliminar un objeto mediante su ID usando una funcion que está en la capa de modelo
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
     * permite modificar un objeto por la info que llega por paramentro, se ejecuta la funcion de la capa del modelo
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
    * permite Buscar un objeto usando info que entra por parametro y acá tengo que usarlo así porque no puedo acceder directamente a la info sino que tengo q pasar por el modelo
     * usa una función que viene desde el modelo
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
