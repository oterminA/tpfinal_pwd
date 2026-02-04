<?php
//estos scripts permiten acceder al orm y entregarle informacion a las paginas de la interfaz
//acá trabajo con objetos y claves-valor porque no estoy sacando la información directamente de la fuente porque el control es un intermediario, a diferencia de la capa del modelo
class ProductoController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['pronombre']) && isset($param['prodetalle']) && isset($param['procantstock'])) {
            $id = $param['idproducto'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
            $precio = $param['proprecio'] ?? null;
            $deshabilitado = $param['prodeshabilitado'] ?? null; 

            $obj = new Producto();
            $obj->setear($id, $param['pronombre'], $param['prodetalle'], $param['procantstock'], $precio, $deshabilitado);
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

        if (isset($param['idproducto'])) {
            $obj = new Producto();
            $obj->setear($param['idproducto'], null, null, null, null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idproducto']))
            $resp = true;
        return $resp;
    }

    /**
     *genera un INSERT basicamente, de lo pasado por parametro, o sea necesita de la funcion insertar() del modelo
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
     *permite eliminar un objeto mediante su ID usando una funcion que está en la capa de modelo
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
     * permite modificar un objeto por la info que llega por paramentro, se ejecuta la funcion de la capa del modelo
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
     * permite Buscar un objeto usando info que entra por parametro y acá tengo que usarlo así porque no puedo acceder directamente a la info sino que tengo q pasar por el modelo
     * usa una función que viene desde el modelo
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
            if (isset($param['proprecio']))
                $where .= " AND proprecio ='" . $param['proprecio'] . "'";
            if (isset($param['prodeshabilitado']))
                $where .= " AND prodeshabilitado ='" . $param['prodeshabilitado'] . "'";
        }
        $arreglo = Producto::listar($where);
        return $arreglo;
    }
}
