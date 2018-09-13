<?php

include_once("config/util.php");

check_session(4);
$telnet = new PHPTelnet();
$util = new util();


//ini_set('display_errors',1);
//error_reporting('E_ALL');


ini_set('max_execution_time', 120);
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


//vozip
/*
 *             userpppoe : userpppoe,
                passpppoe : passpppoe,
                uservoip : uservoip,
                passvoip : passvoip,
                numtel : numtel
 */

$userpppoe = $_POST['userpppoe'];
$passpppoe = $_POST['passpppoe'];

$uservoip = $_POST['uservoip'];
$passvoip = $_POST['passvoip'];

$numtel = $_POST['numtel'];

// ruben borrar esta linea
// cuando termine de editar asignacion.php, se podrá pasar los datos de vozip y ppoe
// personalizados por el instalador mientras cojo los de siempre

$numtel = $_POST['num_pon'];
$uservoip = $_POST['num_pon'];
$passvoip = $_POST['num_pon']."**";



setcookie('c', $c, time() + (86400 * 30), "/");
setcookie('t', $t, time() + (86400 * 30), "/");
setcookie('p', $p, time() + (86400 * 30), "/");
setcookie('caja', $caja, time() + (86400 * 30), "/");
setcookie('puerto', $puerto, time() + (86400 * 30), "/");
setcookie('cabecera', $id_olt, time() + (86400 * 30), "/");
setcookie('activarvoz', $act_voz, time() + (600), "/");

$command = $_POST['command'];
$serial = $_POST['serial'];  // este es el serial
$num_pon = $_POST['num_pon']; // y este el numero PON

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
$ipfija = $_POST['ipfija'];
$mascara = $_POST['mascara'];
$pppoe_profile = $_POST['pppoe_profile'];
setcookie('profile', $pppoe_profile, time() + (86400 * 30), "/");


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

$comando = 'ont add ' . $p . ' sn-auth "' . $num_pon . '" omci ont-lineprofile-id ' . $lineprofile . ' ont-srvprofile-name ' . $serverprofile . ' desc "' . $descripcion . '"';

$result_acs = $util->selectWhere('config_acs', array('ip_radius', 'user_radius', 'pass_radius','profile','vlan_acs'), ' id_cabecera=' . $id_olt);
while ($row = mysqli_fetch_array($result_acs)) {

    $routerIP=$row['ip_radius'];
    $routerUsuario=$row['user_radius'];
    $routerPassword=$row['pass_radius'];
    $profile=$row['profile'];
    $vlan_acs=$row['vlan_acs'];

//        $util->log($routerIP.$routerUsuario.$routerPassword);

}


$respuesta_olt = $telnet->Connect($server, $user, $pass);

if ($respuesta_olt == 0) {
    $telnet->DoCommand('enable', $void);
    $telnet->DoCommand(PHP_EOL, $void);

    $telnet->DoCommand('config', $void);
    $telnet->DoCommand(PHP_EOL, $void);

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
    $util->log($respuesta_olt);

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
        //$ont_id = intval(substr($respuesta_olt, intval($ini) + 7, 3));
        $ont_id = substr($respuesta_olt, intval($ini) + 7, 3);
        $err_num = 0;
//        echo "1: " .$ont_id;

    }


//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");
        $util->log($respuesta_olt);

//    if (str_replace(' ', '', $ont_id) == '') {
        $telnet->DoCommand("quit", $void);

        $comando_1 = 'display ont info by-sn ' . str_replace(' ', '', $num_pon) . PHP_EOL . "q";
        $telnet->DoCommand($comando_1, $respuesta_olt);
        $rows = explode("-----------------------------------------------------------------------------", $respuesta_olt);
        $rows = explode(PHP_EOL, $rows[1]);

        $ont_ids = explode(':', $rows[2]);
        $ont_id = explode(':', $ont_ids[1]);
        $ont_id = str_replace(':','',$ont_id[0]);
//        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando_1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
        $util->log($respuesta_olt);
        $err_num = 0;
//        echo "2: " .$ont_id;
        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
        $telnet->DoCommand(PHP_EOL, $void);

//        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '2:" . $ont_id . "','" . $respuesta_olt . "','" . $id_olt . "');");
        $util->log($respuesta_olt);
//    }


//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");
    $util->log($respuesta_olt);
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

            $id_acs = intval($util->selectMax('control_id_ont', 'id_acs', "olt=" . $id_olt));
            if (intval($id_acs) < 200)
                $id_acs = 200;
            else
                $id_acs++;


            $telnet->DoCommand("quit" . PHP_EOL, $void);
            // creamos servicio en la vlan 100 para datos
//            if(intval($gestionada)==1)
//                $comando1 = "service-port " . $id_internet . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index 300 outbound traffic-table index 300 " . PHP_EOL;
//            else
                $comando1 = "service-port " . $id_internet . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;


            $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);
            $util->log($respuesta_olt);

            // creamos servicio en la vlan 200 para el acs
            $comando2 = "service-port " . $id_acs . " vlan 200 gpon " . $gpon . " ont " . $ont_id . " gemport 2 multi-service user-vlan 200 tag-transform translate inbound traffic-table index 0 outbound traffic-table index 0 " . PHP_EOL;
            $telnet->DoCommand($comando2 . PHP_EOL . PHP_EOL, $respuesta_olt);
            $util->log($respuesta_olt);


            if (strpos($respuesta_olt, 'The traffic table does not exist') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            }

            if (strpos($respuesta_olt, 'does not exist') > 0 || strpos($respuesta_olt, 'Parameter error') > 0) {
                $responder = "Error: El / Los perfiles de velocidad no existen en la cabecera";
                $err_num = 3;
            }

//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

            if ($err_num == 3) {
                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand('ont delete ' . $p . " " . $ont_id, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand("quit" . PHP_EOL, $void);
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'interface gpon " . $c . "/" . $t . "','" . $respuesta_olt . "','" . $id_olt . "');");

                $util->log($respuesta_olt);

                if ($nuevo_nom != '')
                    $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);
            } else {
                $respuesta_olt = null;
//                $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'display service-port " . $id_internet . "','" . $respuesta_olt . "','" . $id_olt . "');");
                $telnet->DoCommand('display service-port ' . $id_internet . PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $telnet->DoCommand(PHP_EOL, $respuesta_olt);
                $util->log($respuesta_olt);

            }
            $respuesta_olt = null;

            // Creamos la interfaz para que se pueda conectar con el ACS
            //----------------------------------------------------------------------------------------------------------
            //----------------------------------------------------------------------------------------------------------

            $telnet->DoCommand('  interface gpon ' . $c . "/" . $t . PHP_EOL.PHP_EOL, $respuesta_olt);
            sleep(2);
//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( 'interface gpon " . $respuesta_olt . "','" . $id_olt . "');");
            $util->log($respuesta_olt);

            if ($act_vpn != 'true') {
                $telnet->DoCommand('ont ipconfig ' . $p . ' ' . $ont_id . ' dhcp vlan '.$vlan_acs . PHP_EOL . PHP_EOL, $respuesta_olt1);
                sleep(1);

            }

            // si se han especificado los datos pppoe
            if($userpppoe!='' && $passpppoe!=''){
                $ppoe_usuario=$userpppoe;
                $ppoe_passw=$passpppoe;
            }


            if($uservoip=='' || $passvoip==''){
                $uservoip=$serial;
                $passvoip=($num_pon."**");
            }


//            if(intval($id_olt)!= 4 && intval($id_olt)!= 6 && intval($id_olt)!=13){
                $telnet->DoCommand('  if-sip add ' . $p . ' ' . $ont_id . ' 1 sipagent-profile profile-id 2' . PHP_EOL . PHP_EOL, $respuesta_olt2);
                sleep(1);
                $util->log($respuesta_olt2);

                $telnet->DoCommand('  sippstnuser add ' . $p . ' ' . $ont_id . ' 1 mgid 1 username "' . $uservoip . '" password "' . $passvoip . '" telno "' . $numtel . '"' . PHP_EOL . PHP_EOL, $respuesta_olt3);
                sleep(1);
                $util->log($respuesta_olt3);
//            }
            
                $telnet->DoCommand("  ont tr069-server-config " . $p . " " . $ont_id . " profile-name acs" . PHP_EOL . PHP_EOL, $respuesta_olt4);
                $util->log($respuesta_olt4);

                setcookie('cabecera_acs', $id_olt, time() + 200, "/");
                setcookie('pon_acs', $num_pon, time() + 200, "/");
                setcookie('ssid', $_POST['ssid'], time() + 200, "/");
                setcookie('clavewifi', $_POST['clavewifi'], time() + 200, "/");


        }


        if ($act_vpn == 'true') {
            $idvpn = intval($util->selectMax('control_id_ont', 'id_vpn', "olt=" . $id_olt));
            if (intval($idvpn) < 500)
                $idvpn = 500;
            else
                $idvpn++;

            $telnet->DoCommand('ont ipconfig ' . $p . ' ' . $ont_id . ' dhcp vlan 500 ' . PHP_EOL . PHP_EOL, $respuesta_olt1);
            sleep(1);
            $util->log($respuesta_olt1);


            if ($act_internet == 'false')
                $telnet->DoCommand("quit", $void);

            $comando1 = "service-port " . $idvpn . " vlan 500 gpon " . $gpon . " ont " . $ont_id . " gemport 5 multi-service user-vlan 500 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw;
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand($comando1 . PHP_EOL, $respuesta_olt);
            $util->log( $comando1 . "" . $respuesta_olt . "" . $id_olt);

            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
            $telnet->DoCommand('ont port native-vlan ' . $p . " " . $ont_id . ' eth 1 vlan 500 priority 0' . PHP_EOL, $respuesta_olt);
            $telnet->DoCommand(PHP_EOL, $respuesta_olt);

            $util->log( 'ont port native-vlan ' . $p . " " . $ont_id . ' eth 1 vlan 500 priority 0' . "" . $respuesta_olt . "" . $id_olt);
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

//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
            $util->log($respuesta_olt);

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

//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");

            $util->log($respuesta_olt);

        }
    }

    $aItems = array();

    if ($err_num == 0) {
        // guardo los datos de la provision
        $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
            $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
            $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, $id_internet, $idvoz, $idtv, $id_olt, $num_pon, $idvpn, $pppoe_profile, $id_acs);
        $lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);

        // guardo los numeros de services ports para que la proxima se le sume 1
        $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_datos', 'id_voz', 'id_tv', 'id_vpn','id_acs'), array($id_olt, $c, $t, $p, $ont_id, $id_internet, $idvoz, $idtv, $idvpn, $id_acs));



        // guardo los datos referentes a la provision acs

//        $asign = $asignada  == 0 ? 'DHCP' : 'PPPoE_Bridged';

        // Unconfigured :  IP_Routed  : IP_Bridged
        //$tipoip = $tipoip  == 0 ? 'IP_Routed' : 'IP_Routed';
        $util->delete('provision_acs',"pon",$num_pon);


        $idcfg = $util->insertInto("provision_acs", array('pon','id_provision', 'ppoe_user', 'ppoe_pass', 'sip_user', 'sip_pass','ConnectionType','AddressingType','ExternalIPAddress','SubnetMask','gestionada','descripcion','ssid','wifipass'),
            array($num_pon,$lastid, $ppoe_usuario, $ppoe_passw, $uservoip, $passvoip, $tipoip, $asignada,$ipfija,$mascara,$gestionada,$_POST['descrp'],$_POST['ssid'],$_POST['clavewifi']));


//
//        if($idcfg=='' || $idcfg==0 || $idcfg==null)
//            $util->update("provision_acs", array('id_provision', 'ppoe_user', 'ppoe_pass', 'sip_user', 'sip_pass','ConnectionType','AddressingType','ExternalIPAddress','SubnetMask','gestionada'),
//                array($lastid, $ppoe_usuario, $ppoe_passw, $serial, ($num_pon."**"), $tipoip, $asign,$ipfija,$mascara,$gestionada), "pon='".$num_pon."'");

        // comprobamos la potencia

        $telnet->Disconnect();
        $respuesta_olt = $telnet->Connect($server, $user, $pass);
        $telnet->DoCommand('enable', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);
        $telnet->DoCommand('config', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $cmd = 'display ont wan-info ' . $c ."/" .$t ." ". $p . " " . $ont_id;

        $c=0; $mac='';
        while ($mac!='' || $c<4){
            $c++;
            $respuesta_olt="";

            $telnet->DoCommand($cmd . PHP_EOL .ESPACIO ."q", $respuesta_olt);
    
            //echo $respuesta_olt;
            $rows = explode("---------------------------------------------------------------------", $respuesta_olt);
            if(isset($rows[2])) {
                $rows2 = explode(PHP_EOL, $rows[2]);
                $m = explode(':', $rows2[14]);
                $mac = $m[1];
            }
            if($mac!='')
                break;
        }

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
                'marca' => $vendor,
                'mac' => $mac
            );

        } else {
            $aItem = array(
                'result' => $err_num,
                 'mac' => $mac
            );
        }
        array_push($aItems, $aItem);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->Disconnect();


    } else {
        $aItem = array(
            'result' => $err_num
        );
        array_push($aItems, $aItem);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->DoCommand("quit" . PHP_EOL, $void);
        $telnet->Disconnect();
    }

    // damos de alta al usuario en la microtik


    if($routerIP!='' && $routerUsuario!='' && $routerPassword!='') {
        require('clases/routeros_api.class.php');

        $API = new RouterosAPI();

        $util->log($routerIP . " " .$routerUsuario ." " .$routerPassword." " . $descripcion . " " .$profile);

        if ($API->connect($routerIP, $routerUsuario, $routerPassword)) {
            $descripcion = $_POST['descrp'];

            $r = $API->comm("/ppp/secret/add", array(
                "name" => "$serial",
                "password" => "$num_pon",
                "comment" => "$descripcion",
                "service" => "pppoe",
                "profile" => "$pppoe_profile"     //
            ));
            $API->disconnect();
        }
    }
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}