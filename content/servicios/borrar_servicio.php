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

if(isset($_POST['a']) && $_POST['a']=='borrar_servicio'){

    if(isset($_POST['p']) && $_POST['p']!=''){


                $q="DELETE FROM SERVICIOS_ATRIBUTOS WHERE id_servicio='".$_POST['p']."';";
                $util->consulta($q);
                $q="DELETE FROM SERVICIOS WHERE id='".$_POST['p']."';";
                $util->consulta($q);

    } else echo "no pon";

} else if(isset($_POST['a']) && $_POST['a']=='borrar_solo_alta'){

    if(isset($_POST['p']) && $_POST['p']!=''){

        if(isset($_POST['hash']) && $_POST['hash']==md5($_POST['p'])){

            $q="DELETE FROM aprovisionados WHERE id='".$_POST['p']."';";
            $util->consulta($q);
            $telnet->Disconnect();
        } else echo "no serial";

    } else echo "no pon";

} else echo "no control";


