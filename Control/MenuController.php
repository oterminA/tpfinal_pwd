<?php
class MenuController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['menombre']) && isset($param['medescripcion'])) { //tengo que poner esto solamente porque acá entran dos posibles null que son idpadre y medeshabilitado entonces rompe el if
            
            $id = $param['idmenu'] ?? null; //porque el id es autoincremental
            
            $objMenuPadre = null; //por si idpadre es null
            if (array_key_exists('idpadre', $param) && $param['idpadre'] != null) {
                $objMenuPadre = new Menu(); //hago como un new de menu para despues pasar los datos
                $objMenuPadre->setIdMenu($param['idpadre']);
                if (!$objMenuPadre->cargar()) {
                    $objMenuPadre = null; 
                }
            }
            
            $medeshabilitado = array_key_exists('medeshabilitado', $param) ? $param['medeshabilitado'] : null; //por si deshabilitado es null q puede pasar
            
            $obj = new Menu();
            $obj->setear(
                $id, 
                $param['menombre'], 
                $param['medescripcion'], 
                $objMenuPadre, 
                $medeshabilitado
            );
        }
        
        return $obj;
    }

    //!!!!!!!

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * retorna el objeto creado pero solo necesitando su id, no necesita el resto de la info. Lo uso más que nada para dar bajas, verificar que exista el objeto solo buscando su id, donde no preciso del resto de los datos
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setear($param['idmenu'], null, null, null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idmenu']))
            $resp = true;
        return $resp;
    }


    /**
     * genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     */
    public function alta($param)
    {
        $resp = false;
        $elMenu = $this->cargarObjeto($param);
        //        verEstructura($elMenu);
        if ($elMenu != null and $elMenu->insertar()) {
            $resp = true;
        }
        return $resp;
    }


    /**
     * permite eliminar un objeto mediante su ID usando una funcion que está en la capa de modelo
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elMenu = $this->cargarObjetoConClave($param);
            if ($elMenu != null and $elMenu->eliminar()) {
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
            $elMenu = $this->cargarObjeto($param);
            if ($elMenu != null and $elMenu->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * permite Buscar un objeto usando info que entra por parametro y acá tengo que usarlo así porque no puedo acceder directamente a la info sino que tengo q pasar por el modelo
     * usa una función que viene desde el modelo
     */
    public function Buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idmenu']))
                $where .= " AND idmenu =" . $param['idmenu'];
            if (isset($param['menombre']))
                $where .= " AND menombre ='" . $param['menombre'] . "'";
            if (isset($param['medescripcion']))
                $where .= " AND medescripcion ='" . $param['medescripcion'] . "'";
            if (isset($param['idpadre']))
                $where .= " AND idpadre ='" . $param['idpadre'] . "'";
            if (isset($param['medeshabilitado']))
                $where .= " AND medeshabilitado ='" . $param['medeshabilitado'] . "'";
        }
        $arreglo = Menu::listar($where);
        return $arreglo;
    }
}
