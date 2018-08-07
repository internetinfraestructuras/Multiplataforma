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

if(isset($_POST['a']) && $_POST['a']=='borrar_proveedor')
{
    if(isset($_POST['p']) && $_POST['p']!='')
    {
            //$q="DELETE FROM PROVEEDORES WHERE id='".$_POST['p']."';";
            $rs=$util->delete("proveedores","id",$_POST['p']);
            if($rs!='')
                echo "El proveedor no se ha podido eliminar, tiene productos relacionados";
            //$util->consulta($q);
    }
    else echo "no pon";

}


