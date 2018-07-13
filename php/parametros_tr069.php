<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 22/05/2018
 * Time: 9:36
 */

/*
 * usuario pppoe = numero de serie
 * clave pppoe = pon
 *
 * wifi empresa + 4 digitos ultimos serie
 * wifi key 8 ultimos del pon
 *
 * usuario vozip num serie
 * clave vozip pon + **
 */

//if (!isset($_SESSION)) {@session_start();}
//
//ini_set('display_errors',1);
//error_reporting('E_ALL');

ini_set('max_execution_time', 50);
include_once("../config/util.php");

$util = new util();

check_session(3);

//$cabecera=$_COOKIE['cabecera_acs'];
//$num_pon='485754431146EB9C';

$cabecera=$_POST['olt'];
$num_pon=str_replace(' ','',$_POST['pon']);


// leo el listado de las ont que el ACS esta encontrando, recojo el id del device y con el puedo atacar a cada una de ellas
//---------------------------------------------------------------------------------------------------------------------------

$ch = curl_init('http://10.211.2.2:7557/devices/');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$json=json_decode($result,true);
curl_close($ch);
//echo count($json);
//print_r($json);
$esta=false;


for ($c=0; $c<count($json);$c++ ){
//    echo $json[$c]['_deviceId']['_SerialNumber']." - ". $num_pon."<br>";

    if($json[$c]['_deviceId']['_SerialNumber'] == $num_pon){
        $esta = true;
        $id_device=$json[$c]['_id'];
        $pon=$json[$c]['_deviceId']['_SerialNumber'];
//        echo $pon."<br>";
//        echo $id_device."<br>";
        break;
        break;
    }
}

if($esta==false) {
    echo "return";
//    return;
}

//foreach ($obj as $objeto) {
//    $id_device = $objeto->_id;
//    $pon = $objeto->_deviceId->_SerialNumber;


//    if($pon==$num_pon) {
        $w = trim($pon);

        $result = $util->selectWhere('config_acs', array('ip_radius', 'user_radius', 'pass_radius', 'ssid', 'usuario_web', 'pass_web', 'profile'), ' id_cabecera=' . $cabecera);

        while ($row = mysqli_fetch_array($result)) {

            $cfg_ssid = $row['ssid'];
            $cfg_userweb = ($row['usuario_web']);
            $cfg_passweb = ($row['pass_web']);

            $routerIP=$row['ip_radius'];
            $routerUsuario=$row['user_radius'];
            $routerPassword=$row['pass_radius'];
            $profile=$row['profile'];
            echo $cfg_ssid."<br>";

        }

        $camposacs=array('pon', 'id_acs', 'id_cabecera');
        $valoresacs=array($pon, $id_device, $cabecera);
        $util->insertInto('acs_ids', $camposacs, $valoresacs);

        if(isset($_POST['ssid']) && isset($_POST['clavewifi']) && $_POST['ssid']!='' && $_POST['clavewifi']!='' ) {
            $cfg_ssid = $_POST['ssid'];
            $pass_wifi = $_POST['clavewifi'];
        } else {
            $result2 = $util->selectWhere('etiquetas.series', array('CLIENTE', 'SSID'), " PON='" . $pon . "'");
            while ($row1 = mysqli_fetch_array($result2)) {
                if ($cfg_ssid == '' || $cfg_ssid == null)
                    $cfg_ssid = trim($cfg_ssid) . "_" . trim($row1['SSID']);
                else
                    $cfg_ssid = trim($row1['CLIENTE']) . "_" . trim($row1['SSID']);
            }
            $pass_wifi = substr($pon,-8);
        }


        $result3 = $util->selectWhere('provision_acs',
                array('ppoe_user','ppoe_pass','ConnectionType','AddressingType','ExternalIPAddress','RemoteIPAddress','SubnetMask','gestionada')," pon='" . $pon . "'");

        echo "Result 3:";
        print_r($result3);
        while ($row2 = mysqli_fetch_array($result3)) {
            $ppoe_user = ($row2['ppoe_user']);
            $ppoe_pass = ($row2['ppoe_pass']);
            $ConnectionType = ($row2['ConnectionType']);
            $AddressingType = ($row2['AddressingType']);
            $ExternalIPAddress = ($row2['ExternalIPAddress']);
            $RemoteIPAddress = ($row2['RemoteIPAddress']);
            $SubnetMask = ($row2['SubnetMask']);
            $gestionada = ($row2['gestionada']);
            $ahora = date("Y-m-d H:i:s");
        }


        if ($cfg_userweb == '')
            $cfg_userweb = 'telecomadmin';

        if ($cfg_passweb == '')
            $cfg_passweb = '1Nexwrf*';


        $interfaz = 2;

        if($cfg_ssid=='' || $cfg_ssid== null)
            $cfg_ssid = 'Mi_Fibra_Wifi_'. substr($pon,-4);


//        $util->log($ppoe_user." ".$ppoe_pass." ".$ConnectionType." ".$AddressingType." ".$ExternalIPAddress." ".
//                            $RemoteIPAddress." ".$SubnetMask." ".$gestionada." ".$ahora." ".$cfg_userweb." ".$cfg_passweb." ".$pass_wifi." ".$cfg_ssid);

        $util->insertInto("provision_acs", array('ssid', 'wifipass', 'actualizado'), array($cfg_ssid, $pass_wifi, $ahora));

        include("lista_parametros_acs.php");
        $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?connection_request";

        $ch = curl_init($url);

        foreach ($aItems as $clave) {
            $payload = json_encode(array('name' => 'setParameterValues', 'parameterValues' => [[$clave['p'], $clave['v'], $clave['t']]]));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
//            echo $result;
//            echo PHP_EOL;
//            echo "<br><br>";
//            echo PHP_EOL;
//            echo PHP_EOL;
        }
        curl_close($ch);
?>