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
    ║ el id del registro a borrar y que se encuentra en la tabla estados_olt, de ahi cogemos los datos de la cabecera  ║
    ║ y todo lo demas como pon, c, t, p, etc                                                                           ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/



if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util(2);
//check_session();

$telnet = new PHPTelnet();


if(isset($_POST['a']) && $_POST['a']=='borrar_en_olt'){

    if(isset($_POST['id']) && $_POST['id']!=''){

        if(isset($_POST['hash']) && $_POST['hash'] == md5($_POST['id'])){
            $id = $_POST['id'];

            $cabeceras = $util->selectJoin('estado_olts', array('id_olt','c','t','p','id_ont','olts.ip','olts.usuario','olts.clave'), ' JOIN olts ON olts.id = estado_olts.id_olt','',' estado_olts.id=' . $id);
            $row = mysqli_fetch_array($cabeceras);

            $server = $row['ip'];
            $user = $row['usuario'];
            $pass = $row['clave'];

            $idont = $row['id_ont'];
            $c = $row['c'];
            $t = $row['t'];
            $p = $row['p'];

            /*
                ╔══════════════════════════════════════════════════════════════════════════════════════════╗
                ║ PHPTelnet es la clase que conecta con una cabecera y lanza comandos telnet  ║
                ╚══════════════════════════════════════════════════════════════════════════════════════════╝
                */
            $result = $telnet->Connect($server, $user, $pass);

            if ($result == 0) {
                $telnet->DoCommand('enable', $result);
                $telnet->DoCommand(PHP_EOL, $result);

                $telnet->DoCommand('config', $result);
                $telnet->DoCommand(PHP_EOL, $result);

                $cmd='display service-port port '.$c.'/'.$t.'/'.$p.' ont '.$idont . PHP_EOL . PHP_EOL;
                $telnet->DoCommand($cmd, $result);
                $rows = explode("-----------------------------------------------------------------------------", $result);
                $rows = explode(PHP_EOL, $rows[2]);
                $rows = explode(" ", $rows[1]);
                $port = intval($rows[3]);
                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '".$cmd."','".$result."','". $id."');");

                if($port>0) {
                    $cmd = 'undo service-port ' . $port . PHP_EOL;
                    $telnet->DoCommand($cmd, $result);
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $cmd . "','" . $result . "','" . $id . "');");

                    $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);

                    $cmd = 'ont delete ' . $p . " " . $idont;

                    $telnet->DoCommand($cmd, $result);
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $cmd . "','" . $result . "','" . $id . "');");
                    $util->delete('estado_olts','id',$id);
                }
            }

        } else echo "no serial";

    } else echo "no pon";

}


