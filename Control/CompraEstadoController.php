<?php
class CompraEstadoController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['idcompra']) && isset($param['idcompraestadotipo']) && isset($param['cefechaini']) && isset($param['cefechafin'])) {

            $id = $param['idcompraestado'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental

            $fechaFin = $param['cefechafin'] ?? null; // si no existe, null porque puede ser que la fecha de fin no se ingrese

            $objCompra = new Compra();
            $objCompra->setIdCompra($param['idcompra']);

            $objCet = new CompraEstadoTipo();
            $objCet->setIdCompraEstadoTipo($param['idcompraestadotipo']);

            if ($objCompra->cargar() && $objCet->cargar()) {
                $obj = new CompraEstado();
                $obj->setear($id, $objCompra, $objCet, $param['cefechaini'], $fechaFin);
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

        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], null, null, null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }


    /**
     * genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
     */
    public function alta($param)
    {
        $resp = false;
        $laCompraEstado = $this->cargarObjeto($param);
        //        verEstructura($laCompraEstado);
        if ($laCompraEstado != null and $laCompraEstado->insertar()) {
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
            $laCompraEstado = $this->cargarObjetoConClave($param);
            if ($laCompraEstado != null and $laCompraEstado->eliminar()) {
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
            $laCompraEstado = $this->cargarObjeto($param);
            if ($laCompraEstado != null and $laCompraEstado->modificar()) {
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
            if (isset($param['idcompraestado']))
                $where .= " AND idcompraestado =" . $param['idcompraestado'];
            if (isset($param['idcompra']))
                $where .= " AND idcompra ='" . $param['idcompra'] . "'";
            if (isset($param['idcompraestadotipo']))
                $where .= " AND idcompraestadotipo ='" . $param['idcompraestadotipo'] . "'";
            if (isset($param['cefechaini']))
                $where .= " AND cefechaini ='" . $param['cefechaini'] . "'";
            if (isset($param['cefechafin']))
                $where .= " AND cefechafin ='" . $param['cefechafin'] . "'";
        }
        $arreglo = CompraEstado::listar($where);
        return $arreglo;
    }
}
