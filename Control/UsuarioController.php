<?php
class ProductoController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     * @param array $param
     * @return Producto
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['idproducto']) && isset($param['pronombre']) && isset($param['prodetalle']) && isset($param['procantstock'])) {
            $id = $param['idproducto'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
            $obj = new Producto();
            $obj->setear($id, $param['pronombre'], $param['prodetalle'], $param['procantstock']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Producto
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idproducto'])) { 
            $obj = new Producto();
            $obj->setear($param['idproducto'], null, null, null);
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
        if (isset($param['idproducto'])) 
            $resp = true;
        return $resp;
    }

    /**
     * 
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $elProducto = $this->cargarObjeto($param);
        //        verEstructura($elProducto);
        if ($elProducto != null and $elProducto->insertar()) {
            $resp = true;
        }
        return $resp;
    }


    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elProducto = $this->cargarObjetoConClave($param);
            if ($elProducto != null and $elProducto->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elProducto = $this->cargarObjeto($param);
            if ($elProducto != null and $elProducto->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return boolean
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idproducto']))
                $where .= " AND idproducto =" . $param['idproducto'];
            if (isset($param['pronombre']))
                $where .= " AND pronombre ='" . $param['pronombre'] . "'";
            if (isset($param['prodetalle']))
                $where .= " AND prodetalle ='" . $param['prodetalle'] . "'";
            if (isset($param['procantstock']))
                $where .= " AND procantstock ='" . $param['procantstock'] . "'";
        }
        $arreglo = Producto::listar($where);
        return $arreglo;
    }
}
