<?php


include_once("config/util.php");

$telnet = new PHPTelnet();
$util= new util();
error_reporting(0);

ini_set('max_execution_time', 300);
ini_set('memory_limit', 1024*1024);

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$c =  $_POST['c'];
$t =  $_POST['t'];
$p =  $_POST['p'];
$caja =  $_POST['caja'];
$puerto =  $_POST['puerto'];
$lat =  $_POST['lat'];
$lon =  $_POST['lon'];
$gpon=$c."/".$t."/".$p;


setcookie('c', $c, time() + (86400 * 30), "/");
setcookie('t', $t, time() + (86400 * 30), "/");
setcookie('p', $p, time() + (86400 * 30), "/");
setcookie('caja', $caja, time() + (86400 * 30), "/");
setcookie('puerto', $puerto, time() + (86400 * 30), "/");
setcookie('cabecera', $id_olt, time() + (86400 * 30), "/");



// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1


$command = $_POST['command'];
$serial = $_POST['serial'];  // este es el serial
$num_pon = $_POST['num_pon']; // y este el numero PON

$lineprofile = $_POST['lineprofile'];
$serverprofile = $_POST['serverprofile'];
$descripcion = str_replace(" ", "_",substr($_POST['descrp'],0,20))."_".$c."_".$t."_".$p."_".$caja."_".$puerto;
$descripcion=strtoupper($descripcion);

$lineprofile='11';

$idcliente = $_POST['idcliente'];
$nuevo_nom = $_POST['nuevo_nom'];
$nuevo_ape = $_POST['nuevo_ape'];

$servicio = $_POST['servicio'];
$up = $_POST['up'];
$dw = $_POST['dw'];

$vpn = $_POST['vpn'];

setcookie('up', $up, time() + (86400 * 30), "/");
setcookie('dw', $dw, time() + (86400 * 30), "/");

//datos del tipo de acceso
$gestionada = isset( $_POST['gestionada'])? $_POST['gestionada'] : "";
$tipoip = isset( $_POST['tipoip'])? $_POST['tipoip'] : "";
$asignada = isset( $_POST['asignada'])? $_POST['asignada'] : "";
$ppoe_usuario = isset( $_POST['usuario_ppoe'])? $_POST['usuario_ppoe'] : "";
$ppoe_passw = isset( $_POST['clave_ppoe'])? $_POST['clave_ppoe'] : "";

setcookie('asignada', $asignada, time() + (86400 * 30), "/");

if($nuevo_nom!=''){
    $idcliente=$util->insertInto('clientes',array('nombre,apellidos,user_create,fecha_alta'),array($nuevo_nom,$nuevo_ape,$_SESSION['USER_ID'],date('Y/m/d')));
}


$gemport = substr($servicio,0,1);

$ont_id = intval($util->selectMax('control_id_ont',  'ont_id', "olt=". $id_olt." and c=".$c." and t=".$t." and p=".$p));

if(intval($vpn)==4) {
    $id_internet = intval($util->selectMax('control_id_ont', 'id_vpn', "olt=" . $id_olt));
    if(intval($id_internet)<500)
        $id_internet=500;
}else {
    $id_internet = intval($util->selectMax('control_id_ont', 'id_datos', "olt=" . $id_olt));
    if(intval($id_internet)<10100)
        $id_internet=10100;
}



    if($ont_id==0 && isset($_POST['id_ini'])){
        $ont_id = $_POST['id_ini'];
    } else {
        $ont_id++;
    }

    if($id_internet==0 && isset($_POST['sp_ini'])){
        $id_internet = $_POST['sp_ini'];
    } else {
        $id_internet++;
    }


//    $comando="ont add ".$p." ".$ont_id." sn-auth ".$serial." omci ont-lineprofile-id ".$lineprofile." ont-srvprofile-name ".$serverprofile." desc ".$descripcion;

    $comando='ont add '.$p.' sn-auth "'.$num_pon.'" omci ont-lineprofile-id '.$lineprofile.' ont-srvprofile-name '.$serverprofile.' desc "'.$descripcion.'"';


    $respuesta_olt = $telnet->Connect($server, $user, $pass);

    if ($respuesta_olt == 0) {
        $telnet->DoCommand('enable', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $telnet->DoCommand('config', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $telnet->DoCommand($comando, $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);
        $responder = -1;
        $err_num = -1;

        if (strpos($respuesta_olt, 'success: 1') > 0) {
            $responder = 0;
            $err_num = 0;
        }

        if (strpos($respuesta_olt, 'ONT ID has already') !== false) {
            $responder = "Error: Numero de PON de ONT ya está aprovisionado";
            $err_num = 1;
        }

        if (strpos($respuesta_olt, 'SN already exists') > 0) {
            $responder = "Error: Numero de PON de ONT ya está aprovisionado";
            $err_num = 1;
        }

        if (strpos($respuesta_olt, 'service profile does not exist') !== false) {
            $responder = "Error: El perfil de ONT seleccionado no existe en la cabecera";
            $err_num = 2;
        }

        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");

        if ($nuevo_nom != '' && $err_num == 1)
            $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);

        if ($err_num == 0) {
            $ini = strpos($respuesta_olt, 'ONTID :');
            $ont_id = intval(substr($respuesta_olt, intval($ini) + 7, 3));
            $cuantas_llevo = 0;
            if (intval($vpn) == 4) {
                $comando1 = "service-port " . $id_internet . " vlan 500 gpon " . $gpon . " ont " . $ont_id . " gemport 5 multi-service user-vlan 500 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw;
                $telnet->DoCommand("quit", $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand($comando1 . PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('ont port native-vlan ' . $t . " " . $ont_id . 'eth 1 vlan 500 priority 0' . PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
            } else {
                $telnet->DoCommand("quit" . PHP_EOL, $void);
                $comando1 = "service-port " . $id_internet . " vlan " . $servicio . " gpon " . $gpon . " ont " . $ont_id . " gemport " . $gemport .
                    " multi-service user-vlan " . $servicio . " tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;

                $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);
//                    $telnet->DoCommand(, $respuesta_olt);
//                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);

                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

                if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
                    $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                    $err_num = 3;
                }

                if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
                    $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                    $err_num = 3;
                }


                if ($err_num == 3) {
                    $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                    $telnet->DoCommand('ont delete ' . $p . " " . $ont_id, $respuesta_olt);
                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                    $telnet->DoCommand("quit" . PHP_EOL, $void);
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'interface gpon " . $c . "/" . $t . "','" . $respuesta_olt . "','" . $id_olt . "');");

                    if ($nuevo_nom != '')
                        $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);
                } else {
                    $respuesta_olt = null;
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
                    $telnet->DoCommand('display service-port ' . $id_internet . PHP_EOL, $respuesta_olt);
                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                }

            }
        }

        $aItems = array();

        if ($err_num == 0) {
            $util->delete('aprovisionados', 'serial', $serial . '" or num_pon="' . $num_pon . '";');

            $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
                $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
                $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, $id_internet, '0', '0', $id_olt, $num_pon);

            $lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);

            if (intval($vpn) == 4)
                $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_vpn', 'id_voz', 'id_tv'), array($id_olt, $c, $t, $p, $ont_id, $id_internet, '0', '0'));
            else
                $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_datos', 'id_voz', 'id_tv'), array($id_olt, $c, $t, $p, $ont_id, $id_internet, '0', '0'));


            if (strpos($respuesta_olt, 'ONT is not online') !== false) {
                $responder = "Error: Offline    ";
                $err_num = 6;
            } else {
                // comprobamos la potencia
                $telnet->Disconnect();
                $respuesta_olt = $telnet->Connect($server, $user, $pass);
                $telnet->DoCommand('enable', $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('config', $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('cls ' . PHP_EOL, $void);
                $telnet->DoCommand('display ont optical-info ' . $p . " " . $ont_id . PHP_EOL . ESPACIO, $respuesta_olt);

                $row = explode(PHP_EOL, $respuesta_olt);
                if(isset($row[8])) {
                    $a = explode(':', $row[8]);
                    $vendor = $a[1];
                    $b = explode(':', $row[13]);
                    $rx = $b[1];
                    $c = explode(':', $row[16]);
                    $tx = $c[1];
                    $d = explode(':', $row[22]);
                    $temp = $d[1];
                    $e = explode(':', $row[28]);
                    $rx_olt = $e[1];
                    $f = explode(':', $row[25]);
                    $volt = $f[1];

                    $aItem = array(
                        'result' => $err_num,
                        'rx' => $rx,
                        'tx' => $tx,
                        'rx_olt' => str_replace('out of range', 'rango: ', $rx_olt),
                        'temp' => $temp,
                        'volt' => $volt,
                        'marca' => $vendor
                    );

                } else{
                    $aItem = array(
                        'result' => $err_num
                    );
                }
                array_push($aItems, $aItem);
                $telnet->DoCommand("quit" . PHP_EOL, $respuesta_olt);
                $telnet->DoCommand("quit" . PHP_EOL, $respuesta_olt);
                $telnet->Disconnect();

            }

        } else {
            $aItem = array(
                'result' => $err_num
            );
            array_push($aItems, $aItem);
            $telnet->DoCommand("quit" . PHP_EOL, $respuesta_olt);
            $telnet->DoCommand("quit" . PHP_EOL, $respuesta_olt);
            $telnet->Disconnect();
        }


        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);



    }


//-----------------------------------------------------------------------------

//

