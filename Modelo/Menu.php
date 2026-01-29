<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
//hay delegación
class Menu
{
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $objmenupadre; //referencia a menu padre?
    private $medeshabilitado;
    private $mensajeBD;


    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->objmenupadre = null;
        $this->medeshabilitado = "";
        $this->mensajeBD = "";
    }

    public function getIdMenu()
    {
        return $this->idmenu;
    }
    public function getNombreMenu()
    {
        return $this->menombre;
    }
    public function getMenuDescripcion()
    {
        return $this->medescripcion;
    }
    public function getObjMenuPadre()
    {
        return $this->objmenupadre;
    }
    public function getDeshabilitado()
    {
        return $this->medeshabilitado;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeBD;
    }


    public function setIdMenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }
    public function setNombreMenu($menombre)
    {
        $this->menombre = $menombre;
    }
    public function setMenuDescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }
    public function setObjMenuPadre($objmenupadre)
    {
        $this->objmenupadre = $objmenupadre;
    }
    public function setDeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;
    }
    public function setmensajeoperacion($mensajeBD)
    {
        $this->mensajeBD = $mensajeBD;
    }
    public function setear($idmenu, $menombre, $medescripcion, $objmenupadre, $medeshabilitado)
    {
        $this->setIdMenu($idmenu);
        $this->setNombreMenu($menombre);
        $this->setMenuDescripcion($medescripcion);
        $this->setObjMenuPadre($objmenupadre);
        $this->setDeshabilitado($medeshabilitado);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu WHERE idmenu = '" . $this->getidmenu() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idmenu']);
                    $objmenupadre->cargar();

                    $this->setear(
                        $row['idmenu'],
                        $row['menombre'],
                        $row['medescripcion'],
                        $objmenupadre,
                        $row['medeshabilitado'],
                    );
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("menu->cargar: " . $base->getError());
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
        $sql = "INSERT INTO menu( menombre, medescripcion, idpadre, medeshabilitado)
        VALUES (
            '
            ',
            '" . $this->getNombreMenu() . "',
            '" . $this->getMenuDescripcion() . "',
            '" . $this->getObjMenuPadre()->getIdMenu() . "',
            '" . $this->getDeshabilitado() . "'
        )";

        if ($base->Iniciar()) {
            if ($elidmenu = $base->Ejecutar($sql)) {
                $this->setidmenu($elidmenu);
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->insertar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->insertar: " . $base->getError());
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
        $sql = "UPDATE menu SET
            menombre = '" . $this->getNombreMenu() . "',
            medescripcion = '" . $this->getMenuDescripcion() . "',
            idpadre = '" . $this->getObjMenuPadre()->getIdMenu() . "',
            medeshabilitado = '" . $this->getDeshabilitado() . "',
        WHERE idmenu = '" . $this->getIdMenu() . "'";


        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->modificar: " . $base->getError());
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
        $sql = "DELETE FROM menu WHERE idmenu='" . $this->getIdMenu() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->eliminar: " . $base->getError());
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
        $sql = "SELECT * FROM menu ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new Menu();

                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idmenu']);
                    $objmenupadre->cargar();

                    $obj->setear(
                        $row['idmenu'],
                        $row['menombre'],
                        $row['medescripcion'],
                        $objmenupadre,
                        $row['medeshabilitado'],
                    );
                    array_push($arreglo, $obj);
                }
            }
        } else {
            self::setmensajeoperacion("menu->listar: " . $base->getError());
        }

        return $arreglo;
    }

        /**
     * recibe un id como parametro y ejecuta la consulta del SELECT buscando lo que coincida con la informacion
    */
    public function buscar($idMenu)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM menu WHERE idmenu = " . $idMenu;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idmenu']);
                    $objmenupadre->cargar();

                    $this->cargar(
                        $row['idmenu'],
                        $row['menombre'],
                        $row['medescripcion'],
                        $objmenupadre,
                        $row['medeshabilitado'],
                    );
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
            "ID menu: " . $this->getidmenu() . "\n" .
            "Menú nombre: " . $this->getNombreMenu() . "\n" .
            "Menú descripcion: " . $this->getMenuDescripcion() . "\n" .
            "Datos menú padre----\n" . $this->getIdMenu() . "\n" .
            "Menú deshabilitado: " . $this->getMenuDescripcion() . "\n" ;
        return $mensaje;
    }
}
