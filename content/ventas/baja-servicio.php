<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra de la cabecera un service port y una ont                                                                   ║
    ║ Recibe como parametro post a: tiene que ser un string 'borrar_en_olt                                             ║
    ║ el id del registro a borrar y que se encuentra en la tabla aprovisionados, de ahi cogemos los datos de la        ║
    ║ cabecera y todo lo demas como pon, c, t, p, etc                                                                           ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(1);

if(isset($_POST['a']) && $_POST['a']=='baja-servicio')
{
    if(isset($_POST['p']) && $_POST['p']!='')
    {
            echo "baja de ".$_POST['p'];
            $rs=$util->delete("productos_modelos","id",$_POST['p']);
           // echo $rs;
            if($rs!='' && $rs!=1)
                echo "El tipo de producto no se ha podido eliminar";
            //$util->consulta($q);*/
    }
    else echo "no pon";

}


