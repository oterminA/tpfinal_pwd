<?php
//el modelo consulta directamente con la base de datos y le puede llegar a pasar info al control
//hay delegación
//creo que esta clase representaría UNA opcion del menú, por ejemplo "contacto"
class Menu
{
    private $idmenu;
    private $menombre;
    private $medescripcion; //cada new de menú es una opcion del mismo, por ej 'inicio'
    private $objmenupadre; //referencia a menu padre? creo que no, me parece q es algo de recursividad "es una relación recursiva de la entidad menú consigo misma, que permite modelar submenús"
    private $medeshabilitado; //esto está acá por si el admin decide deshabilitar una opción, por ejemplo se hace la seccion '2x1' para la temporada de verano y eso después que pase el tiempo puede deshabilitarse por el admin
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
        $sql = "SELECT * FROM menu WHERE idmenu = '" . $this->getIdMenu() . "'";
    
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                $row = $base->Registro();
    
                $objmenupadre = null;
                if (isset($row['idpadre']) && $row['idpadre'] != null && $row['idpadre'] != 0) {
                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idpadre']);
                    $objmenupadre->cargar();
                }
    
                $this->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $objmenupadre,
                    $row['medeshabilitado']
                );
                $resp = true;
            }
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
        $idpadre = $this->getObjMenuPadre() != null ? "'" . $this->getObjMenuPadre()->getIdMenu() . "'" : "NULL";

        $sql = "INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado)
    VALUES (
        '" . $this->getNombreMenu() . "',
        '" . $this->getMenuDescripcion() . "',
        " . $idpadre . ",
        " . ($this->getDeshabilitado() != null ? "'" . $this->getDeshabilitado() . "'" : "NULL") . "
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
    
        $idPadre = "NULL";
        if ($this->getObjMenuPadre() != null) {
            $idPadre = $this->getObjMenuPadre()->getIdMenu();
        }
    
        $fechaDes = ($this->getDeshabilitado() == null) ? "NULL" : "'" . $this->getDeshabilitado() . "'";
    
        $sql = "UPDATE menu SET 
                menombre = '" . $this->getNombreMenu() . "', 
                medescripcion = '" . $this->getMenuDescripcion() . "', 
                idpadre = " . $idPadre . ", 
                medeshabilitado = " . $fechaDes . " 
                WHERE idmenu = " . $this->getIdMenu();
    
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
        if ($res > 0) {
            while ($row = $base->Registro()) {
                $obj = new Menu();
                
                $objmenupadre = null;
                // Corregido: Referenciar al padre real
                if (isset($row['idpadre']) && $row['idpadre'] != null && $row['idpadre'] != 0) {
                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idpadre']);
                    // Opcional: cargar() aquí podría ser pesado si hay muchos niveles, 
                    // pero es necesario para la recursividad que buscas.
                    $objmenupadre->cargar(); 
                }
    
                $obj->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $objmenupadre,
                    $row['medeshabilitado']
                );
                array_push($arreglo, $obj);
            }
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
                $objmenupadre = null;
                if (isset($row['idpadre']) && $row['idpadre'] != null && $row['idpadre'] != 0) {
                    $objmenupadre = new Menu();
                    $objmenupadre->setIdMenu($row['idpadre']);
                    $objmenupadre->cargar(); 
                }
                $this->setear( 
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $objmenupadre,
                    $row['medeshabilitado']
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
            "Menú deshabilitado: " . $this->getDeshabilitado() . "\n";
        return $mensaje;
    }
}
