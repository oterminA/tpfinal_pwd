<?php
//esta clase es para manejar la autenticación y el estado de la sesión, va atener los metodos que piden los profes en el tp
//segun lo q lei, esta clase no tiene funciones nativas de php sino que tiene las q el programador le ponga lo q ke da flexibilidad 
//desde el action por ejemplo yo voy a hacer una instancia de esta clase y acá es q voy a usar a la superglobal $_SESSION que devuelve un array asociativo
class Session
{

    /** 
    * con este constructor lo que hago es que cuando creo una nueva instancia de sesión inmediatamte se hace un session_start
    */
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); //inicializa una sesión
        }
    }

    /**
     * Inicia sesión VALIDANDO usuario y contraseña, o sea que exista el user y queno esté deshabilitado
     */
    public function iniciar($usuario, $contra)
    {
        $resp = false; //bandera en false x defecto

        $ctrlUsuario = new UsuarioController(); //new de usuario
        $lista = $ctrlUsuario->buscar([ //busco al usuaario
            'usnombre' => $usuario,
            'uspass'   => $contra
        ]);

        // var_dump($lista);
        // exit;

        if (!empty($lista)) { //si no está vacía esa variable = hay usuario
            $objUsuario = $lista[0]; //busco al primer usuario con esos datos o sea es el que estoy buscando
            $deshabilitado = $objUsuario->getDeshabilitado(); //recupero el estado de deshabilitación
            if ($deshabilitado === null || $deshabilitado === '0000-00-00 00:00:00') { //con esto solucioné el tema de que me daba error esta funcion y en el var_dump mostraba que deshabilitado era un string, por eso el problema con null
                //si el usuario NO está deshabilitado
                $_SESSION['idusuario'] = $objUsuario->getIdUsuario(); //guardo en session la info del id del user
                $_SESSION['usnombre']  = $objUsuario->getNombre();
                $resp = true; 
            }
        }
        return $resp; //retorno true si se valida la autenticacion de credenciales o false si no
    }

    /**
     * Valida si hay una sesión válida
     */
    public function validar()
    {
        return isset($_SESSION['idusuario']); //retorno el id del user sessionado
    }

    /**
     * Devuelve true si la sesión está activa
     */
    public function activa()
    {
        return session_status() === PHP_SESSION_ACTIVE && $this->validar();
    }

    /**
     * Devuelve el nombre del usuario logueado
     */
    public function getUsuario()
    {
        $retorno = null; //inicializo
        if (isset($_SESSION['usnombre'])) { //si el nombreno es null
            $retorno = $_SESSION['usnombre']; //retorno el user
        }
        return $retorno;
    }

    /**
     * deevuelve el id del user logueado
     */
    public function getIdUsuario()
    {
        $id = null;
        if (isset($_SESSION['idusuario'])) {// si el id no es null
            $id = $_SESSION['idusuario'];
        }
        return $id; //retorno null o el id del user
    }


    /**
     * Devuelve el rol del usuario logueado
     */
    public function getRol()
    {
        $rolNombre = null; //inicializo en null

        if ($this->validar()) { //si es true es porque la sesión está validada
            $abmUR = new UsuarioRolController(); //new de abmusuariorol
            $listaRoles = $abmUR->buscar([ //hago un array de usuarios en usuariorol que tengan el id que está guardaddo en la session
                'idusuario' => $_SESSION['idusuario']
            ]);

            if (!empty($listaRoles)) { //si no está vacio el array de usuarios
                $objUsuarioRol = $listaRoles[0]; //recupero un obj usuariorol
                $objRol = $objUsuarioRol->getObjRol(); //recupero un obj rol
                $rolNombre = $objRol->getRolDescripcion(); //recupero el tipo de rol que tiene ese obj rol
            }
        }
        return $rolNombre; //retorno null o el rol de ese obj rol(ejemplo 'admin)
    }

    /**
     * Cierra la sesión
     */
    public function cerrar()
    {
        if ($this->activa()) {
            session_unset(); //libera las variables de sesión, lo pongo porque entiendo que es buena practica
            session_destroy(); //destruye la sesion
        }
    }
}


//****
/*Funciones principales
session_start(): Inicia una nueva sesión o reanuda una existente. Debe ser la primera función llamada en un script.
$_SESSION: Es un array superglobal que se utiliza para almacenar y acceder a las variables de la sesión. Se puede usar para guardar datos como $_SESSION['usuario'] = 'nombre'.
isset(): Se usa para verificar si una variable de sesión ya existe antes de intentar leerla, por ejemplo, isset($_SESSION['usuario']).
unset(): Elimina una variable de sesión individual, como unset($_SESSION['nombre']).
session_unset(): Elimina todas las variables de la sesión actual. Es importante usar unset() para variables individuales en lugar de unset($_SESSION).
session_destroy(): Destruye completamente la sesión y todos sus datos. Se usa típicamente para cerrar la sesión de un usuario. */