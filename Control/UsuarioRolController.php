<?php
class UsuarioRolController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     * @param array $param
     * @return UsuarioRol
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (isset($param['idusuario']) && isset($param['idrol']) && isset($param['cefechaini']) && isset($param['cefechafin'])) {

            $id = $param['idusuariorol'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental

            $fechaFin = $param['cefechafin'] ?? null; // si no existe, null porque puede ser que la fecha de fin no se ingrese

            $objUsuario = new Usuario();
            $objUsuario->setIdUsuario($param['idusuario']);

            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);

            if ($objUsuario->cargar() && $objRol->cargar()) {
                $obj = new UsuarioRol();
                $obj->setear($id, $objUsuario, $objRol);
            }
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * retorna el objeto creado pero solo necesitando su id, no necesita el resto de la info. Lo uso más que nada para dar bajas, verificar que exista el objeto solo buscando su id, donde no preciso del resto de los datos
     * @param array $param
     * @return UsuarioRol
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idusuariorol'])) {
            $obj = new UsuarioRol();
            $obj->setear($param['idusuariorol'], null, null);
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
        if (isset($param['idusuariorol']))
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
        $elUsuarioRol = $this->cargarObjeto($param);
        //        verEstructura($elUsuarioRol);
        if ($elUsuarioRol != null and $elUsuarioRol->insertar()) {
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
            $elUsuarioRol = $this->cargarObjetoConClave($param);
            if ($elUsuarioRol != null and $elUsuarioRol->eliminar()) {
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
            $elUsuarioRol = $this->cargarObjeto($param);
            if ($elUsuarioRol != null and $elUsuarioRol->modificar()) {
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
            if (isset($param['idusuariorol']))
                $where .= " AND idusuariorol =" . $param['idusuariorol'];
            if (isset($param['idusuario']))
                $where .= " AND idusuario ='" . $param['idusuario'] . "'";
            if (isset($param['idrol']))
                $where .= " AND idrol ='" . $param['idrol'] . "'";
        }
        $arreglo = UsuarioRol::listar($where);
        return $arreglo;
    }
}
