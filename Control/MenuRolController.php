<?php
class MenuRolController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['idmenu']) && isset($param['idrol'])) {

            $id = $param['idmenurol'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental

            $objMenu = new Compra();
            $objMenu->setIdCompra($param['idmenu']);

            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);

            if ($objMenu->cargar() && $objRol->cargar()) {
                $obj = new MenuRol();
                $obj->setear($id, $objMenu, $objRol);
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

        if (isset($param['idmenurol'])) {
            $obj = new MenuRol();
            $obj->setear($param['idmenurol'], null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idmenurol']))
            $resp = true;
        return $resp;
    }


    /**
     * genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     */
    public function alta($param)
    {
        $resp = false;
        $elMenuRol = $this->cargarObjeto($param);
        //        verEstructura($elMenuRol);
        if ($elMenuRol != null and $elMenuRol->insertar()) {
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
            $elMenuRol = $this->cargarObjetoConClave($param);
            if ($elMenuRol != null and $elMenuRol->eliminar()) {
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
            $elMenuRol = $this->cargarObjeto($param);
            if ($elMenuRol != null and $elMenuRol->modificar()) {
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
            if (isset($param['idmenurol']))
                $where .= " AND idmenurol =" . $param['idmenurol'];
            if (isset($param['idmenu']))
                $where .= " AND idmenu ='" . $param['idmenu'] . "'";
            if (isset($param['idrol']))
                $where .= " AND idrol ='" . $param['idrol'] . "'";
        }
        $arreglo = MenuRol::listar($where);
        return $arreglo;
    }
}
