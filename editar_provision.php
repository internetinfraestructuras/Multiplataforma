<?php

if (!isset($_SESSION)) {
    @session_start();
}

include_once("config/util.php");

$telnet = new PHPTelnet();
$util = new util();
check_session(2);

error_reporting(E_ALL);
ini_set("display_errors", 0);

ini_set('max_execution_time', 300);
ini_set('memory_limit', 1024 * 1024);

$id_olt = $_POST['a'];
$serviceport = $_POST['b'];
$ont_id = $_POST['c'];
$gpon = $_POST['d'];
$sino = $_POST['e'];
$s = $_POST['s'];
$up = $_POST['up'];
$dw = $_POST['dw'];
$pon = $_POST['p'];

$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$lineprofile = '11';




$respuesta_olt = $telnet->Connect($server, $user, $pass);

if ($respuesta_olt == 0) {
    $telnet->DoCommand('enable', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $telnet->DoCommand('config', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $respuesta_olt = "";

    if($sino=='false') {

        if($s=='300') {
            $util->consulta("UPDATE aprovisionados SET voip=0 WHERE num_pon = '".$pon."'");
            $serviceport = $util->selectWhere2('aprovisionados',  array('id_voip'), "num_pon='". $pon."'");
        } else if($s=='400') {
            $util->consulta("UPDATE aprovisionados SET iptv=0 WHERE num_pon = '".$pon."'");
            $serviceport = $util->selectWhere2('aprovisionados',  array('id_iptv'), "num_pon='". $pon."'");
        } else if($s=='100') {
            $util->consulta("UPDATE aprovisionados SET internet=0 WHERE num_pon = '".$pon."'");
            $serviceport = $util->selectWhere2('aprovisionados',  array('id_internet'), "num_pon='". $pon."'");
        }
        $telnet->DoCommand("undo service-port " . $serviceport[0] . PHP_EOL . PHP_EOL, $void);
        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . "undo service-port " . $serviceport[0] . "','" . $respuesta_olt . "','" . $id_olt . "');");

    } else {
        if($s=='300') {
            if(intval($serviceport)<=0){
                $serviceport = intval($util->selectMax('control_id_ont', 'id_voz', "olt=" . $id_olt));
                if (intval($serviceport) < 3000)
                    $serviceport = 3000;
                else
                    $serviceport++;
            }
            $comando1 = "service-port " . $serviceport . " vlan 300 gpon " . $gpon . " ont " . $ont_id . " gemport 3 multi-service user-vlan 300 tag-transform translate inbound traffic-table index 9 outbound traffic-table index 9" . PHP_EOL . PHP_EOL;

            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" .  $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
            $util->consulta("UPDATE aprovisionados SET voip=1 WHERE num_pon = '".$pon."'");
        } else if($s=='400') {
            if(intval($serviceport)<=0){
                $serviceport = intval($util->selectMax('control_id_ont', 'id_tv', "olt=" . $id_olt));

                if (intval($serviceport) < 4000)
                    $serviceport = 4000;
                else
                    $serviceport++;
            }
            $comando1 = "service-port " . $serviceport . " vlan 400 gpon " . $gpon . " ont " . $ont_id . " gemport 4 multi-service user-vlan 400 tag-transform translate inbound traffic-table index 50 outbound traffic-table index 50 " . PHP_EOL;

            $util->consulta("UPDATE aprovisionados SET iptv=1 WHERE num_pon = '".$pon."'");
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" .  $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

        } else if($s=='100') {
            if(intval($serviceport)<=0){
                $serviceport = intval($util->selectMax('control_id_ont', 'id_datos', "olt=" . $id_olt));
                if (intval($serviceport) < 1000)
                    $serviceport = 1000;
                else
                    $serviceport++;
            }
            $comando1 = "service-port " . $serviceport . " vlan " . $s . " gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan " . $s . " tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;
            $util->consulta("UPDATE aprovisionados SET internet=1 WHERE num_pon = '".$pon."'");
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" .  $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

        }

        $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);

        if($s=='300') {
            $util->consulta("UPDATE aprovisionados SET id_voip=".$serviceport." WHERE num_pon = '".$pon."'");
            $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_voz'), array($id_olt, $c, $t, $p, $ont_id, $serviceport));
        } else if($s=='400') {
            $util->consulta("UPDATE aprovisionados SET id_iptv=".$serviceport." WHERE num_pon = '".$pon."'");
            $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_tv'), array($id_olt, $c, $t, $p, $ont_id, $serviceport));

        } else if($s=='100') {
            $util->consulta("UPDATE aprovisionados SET id_internet=".$serviceport." WHERE num_pon = '".$pon."'");
            $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_datos'), array($id_olt, $c, $t, $p, $ont_id, $serviceport));

        }


    }

    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");


    $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);

    if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
        $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
        $err_num = 3;
    }

    if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
        $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
        $err_num = 3;
    }



//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
//    $telnet->DoCommand('display service-port ' . $id_internet . PHP_EOL, $respuesta_olt);
//    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//    $telnet->DoCommand(PHP_EOL, $respuesta_olt);


    $aItems = array();

    $telnet->DoCommand("quit" . PHP_EOL, $void);
    $telnet->Disconnect();


    echo $err_num;


}
