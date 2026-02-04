<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
//hay delegación
//creo que esto representa la delacion de una opcion del menú y un rol, por ej, el rol 'admin' tiene una opcion de menú que es 'stock'
class MenuRol
{
    private $idmenurol;
    private $objmenu; //ref a clase menu
    private $objrol; //referencia a clase rol
    private $mensajeBD;


    public function __construct()
    {
        $this->idmenurol = "";
        $this->objmenu = null;
        $this->objrol = null;
        $this->mensajeBD = "";
    }

    public function getIdMenuRol()
    {
        return $this->idmenurol;
    }
    public function getObjMenu()
    {
        return $this->objmenu;
    }
    public function getObjRol()
    {
        return $this->objrol;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdMenuRol($idmenurol)
    {
        $this->idmenurol = $idmenurol;
    }
    public function setObjMenu($objmenu)
    {
        $this->objmenu = $objmenu;
    }
    public function setObjRol($objrol)
    {
        $this->objrol = $objrol;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idmenurol, $objmenu, $objrol)
    {
        $this->setIdMenuRol($idmenurol);
        $this->setObjMenu($objmenu);
        $this->setObjRol($objrol);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menurol WHERE idmenurol = '" . $this->getIdMenuRol() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $objmenu = new Menu();
                    $objmenu->setIdMenu($row['idmenu']);
                    $objmenu->cargar();

                    $objrol = new Rol();
                    $objrol->setIdRol($row['idrol']);
                    $objrol->cargar();

                    $this->setear(
                        $row['idmenurol'], $objmenu, $objrol);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("menurol->cargar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * crea una cadena SQL que corresponde a un INSERT
    */
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol(idmenu, idrol)
        VALUES (
            '" . $this->getObjMenu()->getIdMenu() . "',
            '" . $this->getObjRol()->getIdRol() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidmenurol = $base->Ejecutar($sql)) {
                $this->setIdMenuRol($elidmenurol);
                $resp = true;
            } else {
                $this->setmensajeoperacion("menurol->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menurol->insertar: " . $base->getError());
        }
        return $resp;
    }


    /**
     *se crea una consulta SQL del tipo UPDATE
    */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE menurol SET
        idmenu = '" . $this->getObjMenu()->getIdMenu() . "',
        idrol = '" . $this->getObjRol()->getIdRol() . "'
        WHERE idmenurol = '" . $this->getIdMenuRol() . "'";


        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menurol->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menurol->modificar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * recibe una consulta SQL del tipo DELETE
    */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenurol='" . $this->getIdMenuRol() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menurol->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menurol->eliminar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * es como un select con una condición, devuelve el arreglo de esa consulta o null
    */
    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menurol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new MenuRol();

                    $objmenu = new Menu();
                    $objmenu->setIdMenu($row['idmenu']);
                    $objmenu->cargar();

                    $objrol = new Rol();
                    $objrol->setIdRol($row['idrol']);
                    $objrol->cargar();

                    $obj->setear($row['idmenurol'], $objmenu, $objrol);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("menurol->listar: " . $base->getError()); 
        }

        return $arreglo;
    }

    /**
     * esta funcion lista la info de un id rol, o sea el menú que el id q entra por parametro puede ver
     * la hice estática porque la funcion listar lo es(?)
    */
    public static function listarPorRol($idRol)
{
    $arreglo = [];
    $base = new BaseDatos();

    $sql = "SELECT * 
        FROM menurol 
        WHERE idrol = " . $idRol . " 
        AND deshabilitado = 0";


    if ($base->Iniciar()) {
        if ($base->Ejecutar($sql)) {
            while ($row = $base->Registro()) {
                $menu = new Menu();
                $menu->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'], //no olvidar que cada new de menú es una opcion del mismo, por ej 'productos'
                    null,
                    $row['medeshabilitado'] 
                );
                $arreglo[] = $menu;
            }
        }
    }
    return $arreglo;
}


    /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idMenuRol)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM menurol WHERE idmenurol = " . $idMenuRol;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $objmenu = new Menu();
                    $objmenu->setIdMenu($row['idmenu']);
                    $objmenu->cargar();

                    $objrol = new Rol();
                    $objrol->setIdRol($row['idrol']);
                    $objrol->cargar();
                    $this->cargar($row['idmenurol'], $objmenu, $objrol);
                    $resp = true;
                }
            } else {
                self::setmensajeoperacion($base->getError());
            }
        } else {
            self::setmensajeoperacion($base->getError());
        }
        return $resp;
    }


    ///////////to_String()
    public function __toString()
    {
        $mensaje =
            "ID relación entre menu y rol " . $this->getIdMenuRol() . "\n" .
            "Datos menu relacionado----\n" . $this->getObjMenu() . "\n" . 
            "Datos rol relacionado----\n" . $this->getObjRol() . "\n" ;
        return $mensaje;
    }
}
