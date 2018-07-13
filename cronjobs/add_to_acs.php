<?php



//require_once('/var/www/html/fibra/config/util.php');
require_once('../config/util.php');
$util = new util();
error_reporting(1);
$telnet = new PHPTelnet();
ini_set('max_execution_time', 600);
ini_set('memory_limit', 1024 * 1024);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
header('Content-Type: text/html; charset=utf-8');


error_reporting(E_ALL);
ini_set("display_errors", 1);


$campos = array(
    'aprovisionados.id_en_olt as idolt, aprovisionados.c as c ,aprovisionados.t as t,aprovisionados.num_pon as numpon,
            aprovisionados.p as p, clientes.nombre as nombre, clientes.apellidos as apellidos,
            aprovisionados.cabecera as cabecera');


$resultsql = $util->selectJoin("aprovisionados", $campos,
    " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", 'cabecera,c,t,p');


$num_cabecera = 0;


//while ($rows = mysqli_fetch_array($resultsql)) {
//    $chas = $rows['c'];
//    $tarjetas = $rows['t'];
//    $pons = $rows['p'];
//    $idenolt = $rows['idolt'];
//    $pon = $rows['numpon'];
        $pon = '4857544399251A9B';

//    if (intval($rows['cabecera']) != $num_cabecera) {
        $telnet->Disconnect();

//        $cabeceras = $util->selectWhere('olts', $t_cabeceras, 'id=' . $rows['cabecera']);

//        $row = mysqli_fetch_array($cabeceras);

//        $id_olt = $row['id'];
//        $server = $row['ip'];
//        $user = $row['usuario'];
//        $pass = $row['clave'];
//        $ch = $row['chasis'];
//        $ta = $row['tarjeta'];
//        $po = $row['pon'];

//        $result_acs = $util->selectWhere('config_acs', array('vlan_acs'), ' id_cabecera=' . $id_olt);
//        $result_acs = $util->selectWhere('config_acs', array('vlan_acs'), ' id_cabecera=5');
//        while ($row = mysqli_fetch_array($result_acs)) {
//            $vlan_acs=$row['vlan_acs'];
//        }

        $serie= $util->selectWhere2('etiquetas.series',array('pathnumber'),'etiquetas.series.PON like "%'.$pon.'%"');
        $serial=$serie[0];

//        $id_acs = intval($util->selectMax('control_id_ont', 'id_acs', "olt=" . $id_olt));
        $id_acs = intval($util->selectMax('control_id_ont', 'id_acs', "olt=5"));

        if (intval($id_acs) < 200)
            $id_acs = 200;
        else
            $id_acs++;


        $id_olt = '0';
        $server = '10.201.112.2';
        $user = 'puertosur';
        $pass = '1ppserrano*';
        $ch = '0';
        $ta = '0';
        $po = '6';
        $ont_id='4';

        $result1 = $telnet->Connect($server, $user, $pass);

        $telnet->DoCommand('enable', $result1);
        $telnet->DoCommand(PHP_EOL, $result1);
        $telnet->DoCommand('config', $result1);
        $telnet->DoCommand(PHP_EOL, $result1);
//        $num_cabecera = $rows['cabecera'];
//    }

    $result = null;
    $telnet->DoCommand('display ont wan-info ' . $ch . '/' . $ta . ' ' . $po . ' ' . $ont_id.  PHP_EOL , $respuesta);
    $telnet->DoCommand( ESPACIO . ESPACIO . ESPACIO .ESPACIO. ESPACIO ."q" , $respuesta);

    $esta=false;
    $esta = intval(strpos($respuesta,"Tr069")) > 10 ? true:false;

    if(!$esta){

        $comando2 = "service-port " . $id_acs . " vlan 200 gpon " . $po . " ont " . $ont_id . " gemport 2 multi-service user-vlan 200 tag-transform translate inbound traffic-table index 0 outbound traffic-table index 0 " . PHP_EOL;
        $telnet->DoCommand($comando2 . PHP_EOL . PHP_EOL, $respuesta_olt);
        echo $respuesta_olt;
        $telnet->DoCommand('  interface gpon ' . $ch . "/" . $ta . PHP_EOL.PHP_EOL, $respuesta_olt);
        echo $respuesta_olt;
        sleep(1);
        $telnet->DoCommand('ont ipconfig ' . $po . ' ' . $ont_id . ' dhcp vlan 200 '. PHP_EOL . PHP_EOL, $respuesta_olt1);
        echo $respuesta_olt1;
        sleep(1);
        $telnet->DoCommand('  if-sip add '.$po.' '.$ont_id.' 1 sipagent-profile profile-id 2'.PHP_EOL.PHP_EOL, $respuesta_olt2);
        echo $respuesta_olt2;
        sleep(1);
        $telnet->DoCommand('  sippstnuser add '.$po.' '.$ont_id.' 1 mgid 1 username "'.$serial.'" password "'.($pon."**").'" telno "'. $serial .'"' .PHP_EOL.PHP_EOL, $respuesta_olt3);
        echo $respuesta_olt3;
        sleep(1);
        $telnet->DoCommand("  ont tr069-server-config ".$po." ".$ont_id." profile-name acs" .PHP_EOL . PHP_EOL, $respuesta_olt4);
        echo $respuesta_olt4;
        $telnet->DoCommand('quit'. PHP_EOL, $respuesta_olt);
    }
    $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_acs'), array($id_olt, $ch, $ta, $po, $ont_id, $id_acs));
//}


//if ($respuesta_olt == 0) {
//    $telnet->DoCommand('enable', $void);
//    $telnet->DoCommand(PHP_EOL, $void);
//
//    $telnet->DoCommand('config', $void);
//    $telnet->DoCommand(PHP_EOL, $void);
//
//    $err_num = 0;
//    $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
//    $telnet->DoCommand(PHP_EOL, $void);
//
//    $respuesta_olt = "";
//
//    $telnet->DoCommand($comando, $respuesta_olt);
//    $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//    $responder = -1;
//    $err_num = -1;
//
//    if (strpos($respuesta_olt, 'success: 1') > 0) {
//        $responder = 0;
//        $err_num = 0;
//    }
//
//    if (strpos($respuesta_olt, 'ONT ID has already') !== false) {
//        $responder = "Error: Numero de PON de ONT ya está aprovisionado";
//        $err_num = 1;
//    }
//
//    if (strpos($respuesta_olt, 'SN already exists') > 0) {
//        $responder = "Error: Numero de PON de ONT ya está aprovisionado";
//        $err_num = 1;
//    }
//
//    if (strpos($respuesta_olt, 'service profile does not exist') !== false) {
//        $responder = "Error: El perfil de ONT seleccionado no existe en la cabecera";
//        $err_num = 2;
//    }
//
//    $ont_id = 0;
//
//    if (strpos($respuesta_olt, 'ONTID :') > 0) {
//        $ini = strpos($respuesta_olt, 'ONTID :');
//        //$ont_id = intval(substr($respuesta_olt, intval($ini) + 7, 3));
//        $ont_id = substr($respuesta_olt, intval($ini) + 7, 3);
//        $err_num = 0;
////        echo "1: " .$ont_id;
//
//    }
//
//
//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
////    if (str_replace(' ', '', $ont_id) == '') {
//        $telnet->DoCommand("quit", $void);
//
//        $comando_1 = 'display ont info by-sn ' . str_replace(' ', '', $pon) . PHP_EOL . "q";
//        $telnet->DoCommand($comando_1, $respuesta_olt);
//        $rows = explode("-----------------------------------------------------------------------------", $respuesta_olt);
//        $rows = explode(PHP_EOL, $rows[1]);
//
//        $ont_ids = explode(':', $rows[2]);
//        $ont_id = explode(':', $ont_ids[1]);
//        $ont_id = str_replace(':','',$ont_id[0]);
//        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando_1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
//        $err_num = 0;
////        echo "2: " .$ont_id;
//        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
//        $telnet->DoCommand(PHP_EOL, $void);
//
//        $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '2:" . $ont_id . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
////    }
//
//
//    $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
//    if ($nuevo_nom != '' && $err_num == 1 && (intval($vpn) == 2) && (intval($vpn) == 3))
//        $util->consulta("DELETE FROM clientes WHERE id=" . $idcliente);
//
//
////        }
////            else $err_num=0;
//
//    if ($err_num == 0) {
//        $cuantas_llevo = 0;
//
//        if ($act_internet == 'true') {
//            $id_internet = intval($util->selectMax('control_id_ont', 'id_datos', "olt=" . $id_olt));
//            if (intval($id_internet) < 10100)
//                $id_internet = 10100;
//            else
//                $id_internet++;
//
//            $id_acs = intval($util->selectMax('control_id_ont', 'id_acs', "olt=" . $id_olt));
//            if (intval($id_acs) < 200)
//                $id_acs = 200;
//            else
//                $id_acs++;
//
//
//            $telnet->DoCommand("quit" . PHP_EOL, $void);
//            // creamos servicio en la vlan 100 para datos
//            $comando1 = "service-port " . $id_internet . " vlan 100 gpon " . $gpon . " ont " . $ont_id . " gemport 1 multi-service user-vlan 100 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw . " " . PHP_EOL;
//            $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);
//
//            // creamos servicio en la vlan 200 para el acs
//            $comando2 = "service-port " . $id_acs . " vlan 200 gpon " . $gpon . " ont " . $ont_id . " gemport 2 multi-service user-vlan 200 tag-transform translate inbound traffic-table index 0 outbound traffic-table index 0 " . PHP_EOL;
//            $telnet->DoCommand($comando2 . PHP_EOL . PHP_EOL, $respuesta_olt);
//
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
//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
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
//            $respuesta_olt = null;
//
//            // Creamos la interfaz para que se pueda conectar con el ACS
//            //----------------------------------------------------------------------------------------------------------
//            //----------------------------------------------------------------------------------------------------------
//
//            $telnet->DoCommand('  interface gpon ' . $c . "/" . $t . PHP_EOL.PHP_EOL, $respuesta_olt);
//            sleep(1);
//            $util->log('interface gpon ' . $c . '/' . $t .' ' . $respuesta_olt . ' ' . $id_olt );
//
//            if ($act_vpn != 'true') {
//                $telnet->DoCommand('ont ipconfig ' . $p . ' ' . $ont_id . ' dhcp vlan '.$vlan_acs . PHP_EOL . PHP_EOL, $respuesta_olt1);
//                sleep(1);
//                $util->log($respuesta_olt1);
//            }
//
//            $telnet->DoCommand('  if-sip add '.$p.' '.$ont_id.' 1 sipagent-profile profile-id 2'.PHP_EOL.PHP_EOL, $respuesta_olt2);
//            sleep(1);
//            $util->log($respuesta_olt2);
//
//            $telnet->DoCommand('  sippstnuser add '.$p.' '.$ont_id.' 1 mgid 1 username "'.$serial.'" password "'.($pon."**").'" telno "'. $serial .'"' .PHP_EOL.PHP_EOL, $respuesta_olt3);
//            sleep(1);
//            $util->log($respuesta_olt3);
//
//            $telnet->DoCommand("  ont tr069-server-config ".$p." ".$ont_id." profile-name acs" .PHP_EOL.PHP_EOL, $respuesta_olt4);
//            $util->log($respuesta_olt4);
//
//            setcookie('cabecera_acs', $id_olt, time() + 100, "/");
//            setcookie('pon_acs', $pon, time() + 100, "/");
//            setcookie('ssid', $_POST['ssid'], time() + 100, "/");
//            setcookie('clavewifi', $_POST['clavewifi'], time() + 100, "/");
//
//        }
//
//
//        if ($act_vpn == 'true') {
//            $idvpn = intval($util->selectMax('control_id_ont', 'id_vpn', "olt=" . $id_olt));
//            if (intval($idvpn) < 500)
//                $idvpn = 500;
//            else
//                $idvpn++;
//
//            $telnet->DoCommand('ont ipconfig ' . $p . ' ' . $ont_id . ' dhcp vlan 500 ' . PHP_EOL . PHP_EOL, $respuesta_olt1);
//            sleep(1);
//            $util->log($respuesta_olt1);
//
//
//            if ($act_internet == 'false')
//                $telnet->DoCommand("quit", $void);
//
//            $comando1 = "service-port " . $idvpn . " vlan 500 gpon " . $gpon . " ont " . $ont_id . " gemport 5 multi-service user-vlan 500 tag-transform translate inbound traffic-table index " . $up . " outbound traffic-table index " . $dw;
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand($comando1 . PHP_EOL, $respuesta_olt);
//            $util->log( $comando1 . "" . $respuesta_olt . "" . $id_olt);
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $respuesta_olt);
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand('ont port native-vlan ' . $p . " " . $ont_id . ' eth 1 vlan 500 priority 0' . PHP_EOL, $respuesta_olt);
//            $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//
//            $util->log( 'ont port native-vlan ' . $p . " " . $ont_id . ' eth 1 vlan 500 priority 0' . "" . $respuesta_olt . "" . $id_olt);
//        }
//
//        if ($act_voz == 'true') {
//            $idvoz = intval($util->selectMax('control_id_ont', 'id_voz', "olt=" . $id_olt));
//            if (intval($idvoz) < 5000)
//                $idvoz = 5000;
//            else
//                $idvoz++;
//
//            if ($act_internet == 'false')
//                $telnet->DoCommand("quit" . PHP_EOL, $void);
//
//            $telnet->DoCommand("q" . PHP_EOL . PHP_EOL, $void);
//            $respuesta_olt = null;
//
//            $comando1 = "service-port " . $idvoz . " vlan 300 gpon " . $gpon . " ont " . $ont_id . " gemport 3 multi-service user-vlan 300 tag-transform translate inbound traffic-table index 9 outbound traffic-table index 9" . PHP_EOL . PHP_EOL;
//
//            $telnet->DoCommand($comando1, $respuesta_olt);
//
//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
//        }
//
//        if ($act_tv == 'true') {
//            if ($act_internet == 'false')
//                $telnet->DoCommand("quit" . PHP_EOL, $void);
//
//            $idtv = intval($util->selectMax('control_id_ont', 'id_tv', "olt=" . $id_olt));
//            if (intval($idtv) < 3000)
//                $idtv = 3000;
//            else
//                $idtv++;
//            $comando1 = "service-port " . $idtv . " vlan 400 gpon " . $gpon . " ont " . $ont_id . " gemport 4 multi-service user-vlan 400 tag-transform translate inbound traffic-table index 50 outbound traffic-table index 50 " . PHP_EOL;
//
//            $telnet->DoCommand("q" . PHP_EOL . PHP_EOL, $void);
//            $respuesta_olt = null;
//
//            $telnet->DoCommand($comando1 . PHP_EOL . PHP_EOL, $respuesta_olt);
//
//            $util->consulta("INSERT INTO logs_telnet ( comando, respuesta, cabecera) VALUES ( '" . $comando1 . "','" . $respuesta_olt . "','" . $id_olt . "');");
//
//
//
//        }
//    }
//
//    $aItems = array();
//
//    if ($err_num == 0) {
//        // guardo los datos de la provision
//        $valores = array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon, '1', '0', '0', $serverprofile,
//            $gestionada, $tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
//            $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto, $id_internet, $idvoz, $idtv, $id_olt, $pon, $idvpn, $pppoe_profile, $id_acs);
//        $lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);
//
//        // guardo los numeros de services ports para que la proxima se le sume 1
//        $util->insertInto("control_id_ont", array('olt', 'c', 't', 'p', 'ont_id', 'id_datos', 'id_voz', 'id_tv', 'id_vpn','id_acs'), array($id_olt, $c, $t, $p, $ont_id, $id_internet, $idvoz, $idtv, $idvpn, $id_acs));
//
//
//        /*
//         *
//        WANPPPConnection:
//        ConnectionType:                     TransportType:
//
//        Unconfigured                        PPPoA
//        IP_Routed                           PPPoE
//        DHCP_Spoofed
//        PPPoE_Bridged
//        PPPoE_Relay
//        PPTP_Relay
//        L2TP_Relay
//        *************************************************
//
//        WANIPConnection:
//        ConnectionType:                     AddressingType:
//
//        Unconfigured                        DCHP
//        IP_Routed                           Static
//        IP_Bridged
//
//
//
//
//         */
//
//
//        // guardo los datos referentes a la provision acs
//
////        $asign = $asignada  == 0 ? 'DHCP' : 'PPPoE_Bridged';
//
//        // Unconfigured :  IP_Routed  : IP_Bridged
//        //$tipoip = $tipoip  == 0 ? 'IP_Routed' : 'IP_Routed';
//        $util->delete('provision_acs',"pon",$pon);
//
//        $idcfg = $util->insertInto("provision_acs", array('pon','id_provision', 'ppoe_user', 'ppoe_pass', 'sip_user', 'sip_pass','ConnectionType','AddressingType','ExternalIPAddress','SubnetMask','gestionada','descripcion'),
//                array($pon,$lastid, $ppoe_usuario, $ppoe_passw, $serial, ($pon."**"), $tipoip, $asignada,$ipfija,$mascara,$gestionada,$_POST['descrp']));
////
////        if($idcfg=='' || $idcfg==0 || $idcfg==null)
////            $util->update("provision_acs", array('id_provision', 'ppoe_user', 'ppoe_pass', 'sip_user', 'sip_pass','ConnectionType','AddressingType','ExternalIPAddress','SubnetMask','gestionada'),
////                array($lastid, $ppoe_usuario, $ppoe_passw, $serial, ($pon."**"), $tipoip, $asign,$ipfija,$mascara,$gestionada), "pon='".$pon."'");
//
//        // comprobamos la potencia
//
//        $telnet->Disconnect();
//        $respuesta_olt = $telnet->Connect($server, $user, $pass);
//        $telnet->DoCommand('enable', $respuesta_olt);
//        $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//        $telnet->DoCommand('config', $respuesta_olt);
//        $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
//        $telnet->DoCommand(PHP_EOL, $respuesta_olt);
//        $telnet->DoCommand('cls ' . PHP_EOL, $void);
//        $telnet->DoCommand('display ont optical-info ' . $p . " " . $ont_id . PHP_EOL . ESPACIO, $respuesta_olt);
//
//        $row = explode(PHP_EOL, $respuesta_olt);
//        $row = explode("-----------------------------------------------------------------------------", $respuesta_olt);
//
//        if (isset($row[1])) {
//            $row = explode(PHP_EOL, $row[1]);
//            $a = explode(':', $row[6]);
//            $vendor = $a[1];
//            $b = explode(':', $row[7]);
//            $rx = $b[1];
//            $c = explode(':', $row[10]);
//            $tx = $c[1];
//            $d = explode(':', $row[16]);
//            $temp = $d[1];
//            $e = explode(':', $row[22]);
//            $rx_olt = $e[1];
//            $f = explode(':', $row[19]);
//            $volt = $f[1];
//
//            $aItem = array(
//                'result' => $err_num,
//                'rx' => $rx,
//                'tx' => $tx,
//                'rx_olt' => str_replace('out of range', 'rango: ', $rx_olt),
//                'temp' => $temp,
//                'volt' => $volt,
//                'marca' => $vendor
//            );
//
//        } else {
//            $aItem = array(
//                'result' => $err_num
//            );
//        }
//        array_push($aItems, $aItem);
//        $telnet->DoCommand("quit" . PHP_EOL, $void);
//        $telnet->DoCommand("quit" . PHP_EOL, $void);
//        $telnet->Disconnect();
//
//
//    } else {
//        $aItem = array(
//            'result' => $err_num
//        );
//        array_push($aItems, $aItem);
//        $telnet->DoCommand("quit" . PHP_EOL, $void);
//        $telnet->DoCommand("quit" . PHP_EOL, $void);
//        $telnet->Disconnect();
//    }
//
//    // damos de alta al usuario en la microtik
//
//
//    if($routerIP!='' && $routerUsuario!='' && $routerPassword!='') {
//        require('clases/routeros_api.class.php');
//
//        $API = new RouterosAPI();
//
//        $util->log($routerIP . " " .$routerUsuario ." " .$routerPassword." " . $descripcion . " " .$profile);
//
//        if ($API->connect($routerIP, $routerUsuario, $routerPassword)) {
//            $descripcion = $_POST['descrp'];
//
//            $r = $API->comm("/ppp/secret/add", array(
//                "name" => "$serial",
//                "password" => "$pon",
//                "comment" => "$descripcion",
//                "service" => "pppoe",
//                "profile" => "$pppoe_profile"     //
//            ));
//            $API->disconnect();
//        }
//    }
//    header('Content-type: application/json; charset=utf-8');
//    echo json_encode($aItems);
//}