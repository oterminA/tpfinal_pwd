<?php
class CompraItemController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['idcompraitem']) && isset($param['idcompra']) && isset($param['idproducto']) && isset($param['cicantidad'])) {

            $id = $param['idcompraitem'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental

            $objCompra = new Compra();
            $objCompra->setIdCompra($param['idcompra']);

            $objProducto = new Producto();
            $objProducto->setIdProducto($param['idproducto']);

            if ($objCompra->cargar() && $objProducto->cargar()) {
                $obj = new CompraItem();
                $obj->setear($id, $objCompra, $objProducto, $param['cicantidad']);
            }
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * retorna el objeto creado pero solo necesitando su id, no necesita el resto de la info. Lo uso más que nada para dar bajas, verificar que exista el objeto solo buscando su id, donde no preciso del resto de los datos
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompraitem'])) {
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], null, null, null);
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
        if (isset($param['idcompraitem']))
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
        $laCompraItem = $this->cargarObjeto($param);
        //        verEstructura($laCompraItem);
        if ($laCompraItem != null and $laCompraItem->insertar()) {
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
            $laCompraItem = $this->cargarObjetoConClave($param);
            if ($laCompraItem != null and $laCompraItem->eliminar()) {
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
            $laCompraItem = $this->cargarObjeto($param);
            if ($laCompraItem != null and $laCompraItem->modificar()) {
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
            if (isset($param['idcompraitem']))
                $where .= " AND idcompraitem =" . $param['idcompraitem'];
            if (isset($param['idcompra']))
                $where .= " AND idcompra ='" . $param['idcompra'] . "'";
            if (isset($param['idproducto']))
                $where .= " AND idproducto ='" . $param['idproducto'] . "'";
            if (isset($param['cicantidad']))
                $where .= " AND cicantidad ='" . $param['cicantidad'] . "'";
        }
        $arreglo = CompraItem::listar($where);
        return $arreglo;
    }
}
