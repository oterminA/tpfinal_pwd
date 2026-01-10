<?php
class UsuarioController
{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto crea al objeto completo y necesita toda la informacion. Lo uso más que nada para dar altas o modificar
     * retorna el objeto que se arma a partir de los parametros
     * @param array $param
     * @return Usuario
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (isset($param['usnombre']) && isset($param['uspass']) && isset($param['usmail']) && isset($param['usdeshabilitado'])) {
            $id = $param['idusuario'] ?? null; // si no existe ese id null porque en realidad acá no viene xq es autoincremental
            $obj = new Usuario();
            $obj->setear($id, $param['usnombre'], $param['uspass'], $param['usmail'], $param['usdeshabilitado']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idusuario'])) { 
            $obj = new Usuario();
            $obj->setear($param['idusuario'], null, null, null, null);
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
        if (isset($param['idusuario'])) 
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
        $elUsuario = $this->cargarObjeto($param);
        //        verEstructura($elUsuario);
        if ($elUsuario != null and $elUsuario->insertar()) {
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
            $elUsuario = $this->cargarObjetoConClave($param);
            if ($elUsuario != null and $elUsuario->eliminar()) {
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
            $elUsuario = $this->cargarObjeto($param);
            if ($elUsuario != null and $elUsuario->modificar()) {
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
            if (isset($param['idusuario']))
                $where .= " AND idusuario =" . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " AND usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['uspass']))
                $where .= " AND uspass ='" . $param['uspass'] . "'";
            if (isset($param['usmail']))
                $where .= " AND usmail ='" . $param['usmail'] . "'";
        }
        $arreglo = Usuario::listar($where);
        return $arreglo;
    }
}
