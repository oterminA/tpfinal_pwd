<?php
class CompraController
{
    /**
      * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     * @param array $param
     * @return Compra
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['idcompra']) && isset($param['cofecha']) && isset($param['idusuario'])) {

            $id = $param['idcompra'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
    
            $objUsuario = new Usuario();
            $objUsuario->setIdUsuario($param['idusuario']);
            if ($objUsuario->cargar()) {
                $obj = new Compra();
                $obj->setear($id, $param['cofecha'], $objUsuario); 
            }
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * retorna el objeto creado pero solo necesitando su id, no necesita el resto de la info. Lo uso más que nada para dar bajas, verificar que exista el objeto solo buscando su id, donde no preciso del resto de los datos
     * @param array $param
     * @return Compra
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompra'])) { 
            $obj = new Compra();
            $obj->setear($param['idcompra'], null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompra'])) 
            $resp = true;
        return $resp;
    }


    /**
     * genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $laCompra = $this->cargarObjeto($param);
        //        verEstructura($laCompra);
        if ($laCompra != null and $laCompra->insertar()) {
            $resp = true;
        }
        return $resp;
    }


    /**
     * permite eliminar un objeto mediante su ID usando una funcion que está en la capa de modelo
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $laCompra = $this->cargarObjetoConClave($param);
            if ($laCompra != null and $laCompra->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto por la info que llega por paramentro, se ejecuta la funcion de la capa del modelo
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $laCompra = $this->cargarObjeto($param);
            if ($laCompra != null and $laCompra->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

 
    /**
     * permite Buscar un objeto usando info que entra por parametro y acá tengo que usarlo así porque no puedo acceder directamente a la info sino que tengo q pasar por el modelo
     * usa una función que viene desde el modelo
     * @param array $param
     * @return boolean
     */
    public function Buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra']))
                $where .= " AND idcompra =" . $param['idcompra'];
            if (isset($param['cofecha']))
                $where .= " AND cofecha ='" . $param['cofecha'] . "'";
            if (isset($param['idusuario']))
                $where .= " AND idusuario ='" . $param['idusuario'] . "'";
        }
        $arreglo = Compra::listar($where);
        return $arreglo;
    }
}
