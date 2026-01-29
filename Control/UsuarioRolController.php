
<?php
//estos scripts permiten acceder al orm y entregarle informacion a las paginas de la interfaz
//acá trabajo con objetos y claves-valor porque no estoy sacando la información directamente de la fuente porque el control es un intermediario, a diferencia de la capa del modelo
class UsuarioRolController
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['idrol']) && isset($param['idusuario'])) {

            $objUsuario = new Usuario();
            $objUsuario->setIdUsuario($param['idusuario']);

            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);

            if ($objUsuario->cargar() && $objRol->cargar()) {
                $obj = new UsuarioRol();
                $id = $param['id'] ?? null;
                $obj->setear($id, $objRol, $objUsuario);
            }
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

        if (isset($param['id'])) { //creo q solo va la clave de la entidad q está en el sql
            $obj = new UsuarioRol();
            $obj->setear($param['id'], null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['id']))
            $resp = true;
        return $resp;
    }

    /**
     *genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     */
    public function alta($param)
    {
        $resp = false;
        $elObjtUsuarioRol = $this->cargarObjeto($param);
        //        verEstructura($elObjtUsuarioRol);
        if ($elObjtUsuarioRol != null and $elObjtUsuarioRol->insertar()) {
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
            $elObjtUsuarioRol = $this->cargarObjetoConClave($param);
            if ($elObjtUsuarioRol != null and $elObjtUsuarioRol->eliminar()) {
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
            $elObjtUsuarioRol = $this->cargarObjeto($param);
            if ($elObjtUsuarioRol != null and $elObjtUsuarioRol->modificar()) {
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
            if (isset($param['id']))
                $where .= " and id =" . $param['id'];
            if (isset($param['idrol']))
                $where .= " and idrol ='" . $param['idrol'] . "'";
            if (isset($param['idusuario']))
                $where .= " and idusuario ='" . $param['idusuario'] . "'";
        }
        $arreglo = UsuarioRol::listar($where);
        return $arreglo;
    }

    /**
     * devuelve un array con los roles asociados a un user o un array vacio
     */
    public function buscarRoles($idUsuario)
    {
        $arrayRoles = []; //vacio
        $listaUsuarioRol = $this->buscar(['idusuario' => $idUsuario]);//buscar ,e devolvería un listado de roles relacionados con el id que entra x param
        foreach ($listaUsuarioRol as $objUR) {
            $objRol = $objUR->getObjRol(); //recupero el obj rol de usuariorol
            array_push($arrayRoles, $objRol); //meto ese obj rol al array de roles de ese id
        }
        return $arrayRoles; //array vacio o lleno
    }

    /**
     * devuelve falso o el array de usuarios listados
     */
    public function listar($param = "")
    {
        $resp = false;
        $arregloUsuarioRol = UsuarioRol::listar($param);

        if ($arregloUsuarioRol === false) {
            $resp = $arregloUsuarioRol;
        }

        return $resp;
    }
}
