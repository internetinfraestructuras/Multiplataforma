<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 23/08/2018
 * Time: 13:54
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
class Usuarios
{

    public static function getInstaladores()
    {
        $util=new util();
        return $util->selectWhere3('usuarios',
            array("usuarios.id,usuarios.nombre,usuarios.apellidos,usuarios.email"),
            "usuarios.nivel=2 AND id_empresa=".$_SESSION['REVENDEDOR']);
    }
}