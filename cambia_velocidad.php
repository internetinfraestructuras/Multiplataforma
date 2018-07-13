<?php

if (!isset($_SESSION)) {
    @session_start();
}


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Para cambiar la velocidad de una provision tengo que borrarle el service port y crearlo de nuevo                 ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


include_once("config/util.php");


$telnet = new PHPTelnet();
$util = new util();
check_session(2);


error_reporting(E_ALL);
ini_set("display_errors", 0);

ini_set('max_execution_time', 300);
ini_set('memory_limit', 1024 * 1024);

$id_olt = $_POST['c'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];


$serviceport = $_POST['i'];


$lineprofile = '11';
$up = $_POST['up'];
$dw = $_POST['dw'];
$ont_id = $_POST['ont'];
$gpon = $_POST['gpon'];
$pon = $_POST['p'];


$respuesta_olt = $telnet->Connect($server, $user, $pass);

if ($respuesta_olt == 0) {
    $telnet->DoCommand('enable', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $telnet->DoCommand('config', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $respuesta_olt = "";
    /*
    ╔═════════════════════════════════════╗
    ║ Borra el servicio de internet  ║
    ╚═════════════════════════════════════╝
    */
    $telnet->DoCommand("undo service-port " . $serviceport . PHP_EOL . PHP_EOL, $void);

    /*
    ╔═════════════════════════════════════╗
    ║ Creo el servicio de internet   ║
    ╚═════════════════════════════════════╝
    */
    $comando1 = "service-port " . $serviceport . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;

    $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);
    $util->consulta("UPDATE aprovisionados SET velocidad_up='".$up."',velocidad_dw='".$dw."' WHERE num_pon = '".$pon."'");

    /*
   ╔═════════════════════════════════════╗
   ║ Compruebo que se creó bien     ║
   ╚═════════════════════════════════════╝
   */
    if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
        $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
        $err_num = 3;
    }

    if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
        $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
        $err_num = 3;
    }

    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");


//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
//    $telnet->DoCommand('display service-port ' . $id_internet . PHP_EOL, $respuesta_olt);
//    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//    $telnet->DoCommand(PHP_EOL, $respuesta_olt);


    $aItems = array();

    $telnet->DoCommand("quit" . PHP_EOL, $void);
    $telnet->Disconnect();


    echo $err_num;


}



//-----------------------------------------------------------------------------

//


