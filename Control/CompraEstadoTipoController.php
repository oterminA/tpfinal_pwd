<?php
class CompraEstadoTipoController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['cetdescripcion']) && isset($param['cetdetalle'])) {
            $id = $param['idcompraestadotipo'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
            $obj = new CompraEstadoTipo();
            $obj->setear($id, $param['cetdescripcion'], $param['cetdetalle']);
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

        if (isset($param['idcompraestadotipo'])) { 
            $obj = new CompraEstadoTipo();
            $obj->setear($param['idcompraestadotipo'], null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraestadotipo'])) 
            $resp = true;
        return $resp;
    }


    /**
     * genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     */
    public function alta($param)
    {
        $resp = false;
        $laCompraEstadoTipo = $this->cargarObjeto($param);
        //        verEstructura($laCompraEstadoTipo);
        if ($laCompraEstadoTipo != null and $laCompraEstadoTipo->insertar()) {
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
            $laCompraEstadoTipo = $this->cargarObjetoConClave($param);
            if ($laCompraEstadoTipo != null and $laCompraEstadoTipo->eliminar()) {
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
            $laCompraEstadoTipo = $this->cargarObjeto($param);
            if ($laCompraEstadoTipo != null and $laCompraEstadoTipo->modificar()) {
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
            if (isset($param['idcompraestadotipo']))
                $where .= " AND idcompraestadotipo =" . $param['idcompraestadotipo'];
            if (isset($param['cetdescripcion']))
                $where .= " AND cetdescripcion ='" . $param['cetdescripcion'] . "'";
            if (isset($param['cetdetalle']))
                $where .= " AND cetdetalle ='" . $param['cetdetalle'] . "'";
        }
        $arreglo = CompraEstadoTipo::listar($where);
        return $arreglo;
    }
}
