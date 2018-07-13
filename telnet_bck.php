<?php

include_once("config/util.php");

check_session(4);
$telnet = new PHPTelnet();
$util = new util();
error_reporting(E_ALL);
ini_set("display_errors", 0);

ini_set('max_execution_time', 300);
ini_set('memory_limit', 1024 * 1024);

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$c = $_POST['c'];
$t = $_POST['t'];
$p = $_POST['p'];
$caja = $_POST['caja'];
$puerto = $_POST['puerto'];
$lat = $_POST['lat'];
$lon = $_POST['lon'];
$id_en_olt = $_POST['idenolt'];
$gpon = $c . "/" . $t . "/" . $p;

$act_internet = $_POST['act_internet'];
$act_voz = $_POST['act_voz'];
$act_tv = $_POST['act_tv'];
$act_vpn = $_POST['act_vpn'];


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

//if($command=='alta'){
//    $respuesta_olt = $util->selec1tWhere('aprovisionados',  array('id'), ' serial="'.$serial.'" or num_pon="'.$num_pon.'";');
//    $row = mysqli_fetch_array($respuesta_olt);
//    $r=intval($row[0]);||
//
//    if($r>0){
//        echo "El PON y/o número serie de la ONT ya está registrado";
//        return;
//    }
//
//}

$lineprofile = $_POST['lineprofile'];
$serverprofile = $_POST['serverprofile'];
$descripcion = str_replace(" ", "_", substr($_POST['descrp'], 0, 20)) . "_" . $c . "_" . $t . "_" . $p . "_" . $caja . "_" . $puerto;
$descripcion = strtoupper($descripcion);

$lineprofile = '11';

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
$gestionada = isset($_POST['gestionada']) ? $_POST['gestionada'] : "";
$tipoip = isset($_POST['tipoip']) ? $_POST['tipoip'] : "";
$asignada = isset($_POST['asignada']) ? $_POST['asignada'] : "";


$ppoe_usuario = isset($_POST['usuario_ppoe']) ? $_POST['usuario_ppoe'] : $serial;
$ppoe_passw = isset($_POST['clave_ppoe']) ? $_POST['clave_ppoe'] : $num_pon;

setcookie('asignada', $asignada, time() + (86400 * 30), "/");
setcookie('tipoip', $tipoip, time() + (86400 * 30), "/");
setcookie('gestionada', $gestionada, time() + (86400 * 30), "/");
if ($nuevo_nom != '') {
    $idcliente = $util->insertInto('clientes', array('nombre,apellidos,user_create,fecha_alta'), array($nuevo_nom, $nuevo_ape, $_SESSION['USER_ID'], date('Y/m/d')));
}


$gemport = substr($servicio, 0, 1);

//$ont_ids = $util->selectWhere2('control_id_ont',  array('distinct(ont_id)'), "olt=". $id_olt." and c=".$c." and t=".$t." and p=".$p,'ont_id');


//for($co=0;$co<=128;$co++){
//
//    if(!in_array($co,$ont_ids)){
//        $ont_id=$co;
//        break;
//    }
//}
//
//if($co==128){
//    $aItem = array(
//        'result' => -2
//    );
//    array_push($aItems, $aItem);
//    header('Content-type: application/json; charset=utf-8');
//    echo json_encode($aItems);
//    return;
//}
//
//if($ont_id=='' || $ont_id==null || $ont_id<0)
//    $ont_id=0;

//if(intval($vpn)==4) {
//    $id_internet = intval($util->selectMax('control_id_ont', 'id_vpn', "olt=" . $id_olt));
//    if(intval($id_internet)<500)
//        $id_internet=500;
//} else if(intval($vpn)==3) {
//    $id_internet = intval($util->selectMax('control_id_ont', 'id_iptv', "olt=" . $id_olt));
//    if(intval($id_internet)<3000)
//        $id_internet=3000;
//} else if(intval($vpn)==2) {
//    $id_internet = intval($util->selectMax('control_id_ont', 'id_voz', "olt=" . $id_olt));
//    if(intval($id_internet)<5000)
//        $id_internet=5000;
//} else {
//    $id_internet = intval($util->selectMax('control_id_ont', 'id_datos', "olt=" . $id_olt));
//    if(intval($id_internet)<10100)
//        $id_internet=10100;
//}

//
//    if($ont_id==0 && isset($_POST['id_ini'])){
//        $ont_id = $_POST['id_ini'];
//    } else {
//        $ont_id++;
//    }

//    if($id_internet==0 && isset($_POST['sp_ini'])){
//        $id_internet = $_POST['sp_ini'];
//    } else {
//        $id_internet++;
//    }


//    $comando="ont add ".$p." ".$ont_id." sn-auth ".$serial." omci ont-lineprofile-id ".$lineprofile." ont-srvprofile-name ".$serverprofile." desc ".$descripcion;

$comando = 'ont add ' . $p . ' sn-auth "' . $num_pon . '" omci ont-lineprofile-id ' . $lineprofile . ' ont-srvprofile-name ' . $serverprofile . ' desc "' . $descripcion . '"';


$respuesta_olt = $telnet->Connect($server, $user, $pass);

if ($respuesta_olt == 0) {
    $telnet->DoCommand('enable', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $telnet->DoCommand('config', $void);
    $telnet->DoCommand(PHP_EOL, $void);

//        if(intval($vpn)==1 || intval($vpn)==4) {
    $err_num = 0;
    $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $respuesta_olt = "";

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

    $ont_id = 0;

    if (strpos($respuesta_olt, 'ONTID :') > 0) {
        $ini = strpos($respuesta_olt, 'ONTID :');
        $ont_id = substr($respuesta_olt, intval($ini) + 7, 3);
        $err_num = 0;
//        echo "1: " .$ont_id;

    }


    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");

//    if (str_replace(' ', '', $ont_id) == '') {
        $telnet->DoCommand("quit", $void);

        $comando_1 = 'display ont info by-sn ' . str_replace(' ', '', $num_pon) . PHP_EOL . "q";
        $telnet->DoCommand($comando_1, $respuesta_olt);
        $rows = explode("-----------------------------------------------------------------------------", $respuesta_olt);
        $rows = explode(PHP_EOL, $rows[1]);

        $ont_ids = explode(':', $rows[2]);
        $ont_id = explode(':', $ont_ids[1]);
        $ont_id = $ont_id[0];
        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando_1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
        $err_num = 0;
//        echo "2: " .$ont_id;
        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
        $telnet->DoCommand(PHP_EOL, $void);

        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '2:" . $ont_id . "','" . $respuesta_olt . "','" . $id_olt . "');");

//    }


    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");

    if ($nuevo_nom != '' && $err_num == 1 && (intval($vpn) == 2) && (intval($vpn) == 3))
        $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);


//        }
//            else $err_num=0;

    if ($err_num == 0) {
        $cuantas_llevo = 0;

        if ($act_internet == 'true') {
            $id_internet = intval($util->selectMax('control_id_ont', 'id_datos', "olt=" . $id_olt));
            if (intval($id_internet) < 10100)
                $id_internet = 10100;
            else
                $id_internet++;

            $telnet->DoCommand("quit" . PHP_EOL, $void);
            $comando1 = "service-port " . $id_internet . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;

            $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);

            if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            }

            if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            }

            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

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
            $respuesta_olt = null;

            $telnet->DoCommand("cls".PHP_EOL, $void);
            $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);

            if($gestionada==0){
                $telnet->DoCommand('ont ipconfig '.$p.' '.$ont_id.' dhcp vlan 100'.PHP_EOL.PHP_EOL.PHP_EOL, $respuesta_olt);
                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'ont ipconfig $p $ont_id dhcp vlan 100','" . $respuesta_olt . "','" . $id_olt . "');");
                $telnet->DoCommand("cls".PHP_EOL, $void);
                $respuesta_olt=null;
            } else {
                if($asignada==0){
                    $c='ont ipconfig '.$p.' '.$ont_id.' dhcp vlan 100';
                    $telnet->DoCommand($c.PHP_EOL.PHP_EOL, $respuesta_olt);
                    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'ont ipconfig $p $ont_id dhcp vlan 100','" . $respuesta_olt . "','" . $id_olt . "');");
                    $telnet->DoCommand("cls".PHP_EOL, $void);
                    $respuesta_olt=null;
                } else {
                    $c='ont ipconfig '.$p.' '.$ont_id.' pppoe vlan 100 user-account username "'.$ppoe_usuario.'" password "'.$ppoe_passw.'"';
                    $telnet->DoCommand($c.PHP_EOL.PHP_EOL, $respuesta_olt);
                    $telnet->DoCommand("cls".PHP_EOL, $void);
                    $respuesta_olt=null;
                }
                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( $c,'" . $respuesta_olt . "','" . $id_olt . "');");
                $telnet->DoCommand("cls".PHP_EOL, $void);
                $respuesta_olt=null;
            }
            // para ip fija
            //ont ipconfig 0 2 static ip-address 10.20.20.20 mask 255.255.255.0 gateway 10.10.20.1 vlan 100

            $telnet->DoCommand('if-sip add '.$p.' '.$ont_id.' 1 sipagent-profile profile-id 2'.PHP_EOL.PHP_EOL.PHP_EOL, $respuesta_olt);
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'if-sip add $p $ont_id 1 sipagent-profile profile-id 2','" . $respuesta_olt."','".$id_olt."' );");

            $telnet->DoCommand('sippstnuser add '.$p.' '.$ont_id.' 1 mgid 1 username '.$serial.' password '.$num_pon.' telno '. $serial .PHP_EOL.PHP_EOL.PHP_EOL, $respuesta_olt);
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'sippstnuser add ".$p ." " .$ont_id." mgid 1 username ".$ppoe_usuario." password ".$ppoe_passw." telno ".$ppoe_usuario."','". $serial."','". $respuesta_olt. "','". $id_olt."');");

            $telnet->DoCommand("ont tr069-server-config ".$p." ".$ont_id." profile-id 1" .PHP_EOL.PHP_EOL.PHP_EOL, $respuesta_olt);
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'ont tr069-server-config $p $ont_id profile-id 1', '".$respuesta_olt."','". $id_olt."');");

        }


        if ($act_vpn == 'true') {
            $idvpn = intval($util->selectMax('control_id_ont', 'id_vpn', "olt=" . $id_olt));
            if (intval($idvpn) < 500)
                $idvpn = 500;
            else
                $idvpn++;

            if ($act_internet == 'false')
                $telnet->DoCommand("quit", $void);

            $comando1 = "service-port " . $idvpn . " vlan 500 gpon " . $gpon . " ont " . $ont_id . " gemport 5 multi-service user-vlan 500 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw;
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand($comando1 . PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand('ont port native-vlan ' . $t . " " . $ont_id . ' eth 1 vlan 500 priority 0' . PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
        }

        if ($act_voz == 'true') {
            $idvoz = intval($util->selectMax('control_id_ont', 'id_voz', "olt=" . $id_olt));
            if (intval($idvoz) < 5000)
                $idvoz = 5000;
            else
                $idvoz++;

            if ($act_internet == 'false')
                $telnet->DoCommand("quit" . PHP_EOL, $void);

            $telnet->DoCommand("q" . PHP_EOL . PHP_EOL, $void);
            $respuesta_olt = null;

            $comando1 = "service-port " . $idvoz . " vlan 300 gpon " . $gpon . " ont " . $ont_id . " gemport 3 multi-service user-vlan 300 tag-transform translate inbound traffic-table index 9 outbound traffic-table index 9" . PHP_EOL . PHP_EOL;

            $telnet->DoCommand($comando1, $respuesta_olt);

            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

//            if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
//                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
//                $err_num = 3;
//            }
//
//            if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
//                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
//                $err_num = 3;
//            }
//
//
//            if ($err_num == 3) {
//                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand('ont delete ' . $p . " " . $ont_id, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand("quit" . PHP_EOL, $void);
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'interface gpon " . $c . "/" . $t . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
//                if ($nuevo_nom != '')
//                    $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);
//            } else {
//                $respuesta_olt = null;
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
//                $telnet->DoCommand('display service-port ' . $id_internet . PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            }

        }

        if ($act_tv == 'true') {
            if ($act_internet == 'false')
                $telnet->DoCommand("quit" . PHP_EOL, $void);

            $idtv = intval($util->selectMax('control_id_ont', 'id_tv', "olt=" . $id_olt));
            if (intval($idtv) < 3000)
                $idtv = 3000;
            else
                $idtv++;
            $comando1 = "service-port " . $idtv . " vlan 400 gpon " . $gpon . " ont " . $ont_id . " gemport 4 multi-service user-vlan 400 tag-transform translate inbound traffic-table index 50 outbound traffic-table index 50 " . PHP_EOL;

            $telnet->DoCommand("q" . PHP_EOL . PHP_EOL, $void);
            $respuesta_olt = null;

            $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);

//            if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
//                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
//                $err_num = 3;
//            }
//
//            if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
//                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
//                $err_num = 3;
//            }

            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");


//            if ($err_num == 3) {
//                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand('ont delete ' . $p . " " . $ont_id, $respuesta_olt);
//                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//                $telnet->DoCommand("quit" . PHP_EOL, $void);
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'interface gpon " . $c . "/" . $t . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
//                if ($nuevo_nom != '')
//                    $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);
//            } else {
//                $respuesta_olt = null;
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
////                    $telnet->DoCommand('display service-port ' . $idtv . PHP_EOL, $respuesta_olt);
////                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
////                    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            }

        }
    }

    $aItems = array();
//echo "error: " .$err_num;

    if ($err_num == 0) {
//        $util->delete('aprovisionados', 'serial', $serial . '" or num_pon="' . $num_pon . '";');

//            if (intval($vpn) == 4) {
//                $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
//                    $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
//                    $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, '0', '0', '0', $id_olt, $num_pon, $id_internet);
//            } else if (intval($vpn) == 3)  {
//                $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
//                    $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
//                    $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto,  '0',$id_internet, '0', $id_olt, $num_pon, '0');
//            } else {

//            }
        // guardo los datos de la provision
        $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
            $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
            $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, $id_internet, $idvoz, $idtv, $id_olt, $num_pon, $idvpn);
        $lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);

        // guardo los numeros de services ports para que la proxima se le sume 1
        $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_datos', 'id_voz', 'id_tv', 'id_vpn'), array($id_olt, $c, $t, $p, $ont_id, $id_internet, $idvoz, $idtv, $idvpn));

        // guardo los datos referentes a la provision acs
        $util->insertInto("provision_acs", array('id_provision', 'ppoe_user', 'ppoe_pass', 'sip_user', 'sip_pass'),
                array($lastid, $ppoe_usuario, $ppoe_passw, $serial, $num_pon));


//            if (strpos($respuesta_olt, 'ONT is not online') !== false) {
//                $responder = "Error: Offline    ";
//                $err_num = 6;
//            } else {
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
        $row = explode("-----------------------------------------------------------------------------", $respuesta_olt);

        if (isset($row[1])) {
            $row = explode(PHP_EOL, $row[1]);
            $a = explode(':', $row[6]);
            $vendor = $a[1];
            $b = explode(':', $row[7]);
            $rx = $b[1];
            $c = explode(':', $row[10]);
            $tx = $c[1];
            $d = explode(':', $row[16]);
            $temp = $d[1];
            $e = explode(':', $row[22]);
            $rx_olt = $e[1];
            $f = explode(':', $row[19]);
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

        } else {
            $aItem = array(
                'result' => $err_num
            );
        }
        array_push($aItems, $aItem);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->Disconnect();

//            }

    } else {
        $aItem = array(
            'result' => $err_num
        );
        array_push($aItems, $aItem);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->Disconnect();
    }


    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);


}


//-----------------------------------------------------------------------------

//


