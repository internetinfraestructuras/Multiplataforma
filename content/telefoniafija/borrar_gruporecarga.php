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
require_once('../../clases/telefonia/classTelefonia.php');
$util = new util();
$tel = new Telefonia();
check_session(1);

if(isset($_POST['a']) && $_POST['a']=='borrar_gruporecarga')
{
    if(isset($_POST['c']) && $_POST['c']!='' && isset($_POST['n']) && $_POST['n']!='')
    {

        try {
            $res = $tel->deleteGrupoRecarga($_POST['c'],$_POST['n']);
            if ($res != 1)
                echo "Error eliminando grupo de recarga";
            //else

        }
        catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
        }

    }
    else echo "no data recarga";

}


