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


ini_set('max_execution_time', 300);
include_once("../config/util.php");

$util = new util();

ini_set('display_errors',0);
error_reporting('E_ALL');

//check_session(3);

$cabecera=$_REQUEST['olt'];
$num_pon=str_replace(' ','',$_REQUEST['pon']);
$mac=str_replace(' ','',$_REQUEST['mac']);
$mac = str_replace("-",":",$mac);
$mac1 = substr($mac,0,2) . ":" .substr($mac,2,3) . substr($mac,5,2) . ":" .
    substr($mac,7,3).substr($mac,10,2). ":" .substr($mac,12,2);

$act_voz = $_POST['act_voz'];


// Consultar Id del dispositivo mediante una consulta indicando la MAC
//---------------------------------------------------------------------------------------------------------------------------
$url=trim('http://10.211.2.2:7557/devices/?query=%7B"InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.MACAddress%22%3A"'.$mac1.'"%7D');
$util->log($url);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_BUFFERSIZE, 512);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$json=json_decode($result,true);

$esta = true;
$id_device=$json[0]['_id'];
$pon=$json[0]['_deviceId']['_SerialNumber'];

curl_close($ch);

$util->log($id_device);
$util->log($pon);

$id_olt = $_COOKIE['cabecera'];

if($esta==false) {
//    echo "return";
//    return;
}

$w = trim($pon);

$result = $util->selectWhere(FIBRADB.'config_acs', array('ip_radius', 'user_radius', 'pass_radius', 'ssid', 'usuario_web', 'pass_web', 'profile'), ' id_cabecera=' . $cabecera);

//        print_r($result);
while ($row = mysqli_fetch_array($result)) {

    $cfg_ssid = $row['ssid'];
    $cfg_userweb = ($row['usuario_web']);
    $cfg_passweb = ($row['pass_web']);

    $routerIP=$row['ip_radius'];
    $routerUsuario=$row['user_radius'];
    $routerPassword=$row['pass_radius'];
    $profile=$row['profile'];
    //echo $cfg_ssid."<br>";

}

$camposacs=array('pon', 'id_acs', 'id_cabecera');
$valoresacs=array($pon, $id_device, $cabecera);
$util->insertInto(FIBRADB.'acs_ids', $camposacs, $valoresacs);

// si viene especificado el nombre de la wifi y la clave de forma manual...
if(isset($_REQUEST['ssid']) && isset($_REQUEST['clavewifi']) && $_REQUEST['ssid']!='' && $_REQUEST['clavewifi']!='' ) {

    $cfg_ssid = $_REQUEST['ssid'];
    $pass_wifi = $_REQUEST['clavewifi'];

} else {
    // si no viene  lo cojo de la configuracion guardada en la tabla config_acs

    $result2 = $util->selectWhere('etiquetas.series', array('CLIENTE', 'SSID'), " PON='" . $pon . "'");
    while ($row1 = mysqli_fetch_array($result2)) {
        if ($cfg_ssid != '' && $cfg_ssid != null)
            $cfg_ssid = trim($cfg_ssid) . "_" . trim($row1['SSID']);
        else
            $cfg_ssid = trim($row1['CLIENTE']) . "_" . trim($row1['SSID']);
    }

    $pass_wifi = substr($pon,-8);
}


$result3 = $util->selectWhere(FIBRADB.'provision_acs',
    array('ppoe_user','ppoe_pass','ConnectionType','AddressingType','ExternalIPAddress','RemoteIPAddress','SubnetMask','gestionada')," pon='" . $pon . "'");


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
    $cfg_ssid = 'Mi Fibra Wifi '. substr($pon,-4);


$util->insertInto(FIBRADB."provision_acs", array('ssid', 'wifipass', 'actualizado'), array($cfg_ssid, $pass_wifi, $ahora));

include("lista_parametros_acs.php");
$url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?connection_request";
$util->log($url);
$ch = curl_init($url);


foreach ($aItems as $clave) {

    $payload = json_encode(array('name' => 'setParameterValues', 'parameterValues' => [[$clave['p'], $clave['v'], $clave['t']]]));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
//            $util->log($payload);
}

if(intval($id_olt)== 15)
    sleep(6);


$payload = json_encode(array('name' => 'refreshObject', 'objectName' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);


$aItems = array();

$aItem = array(
    'ssid' => $cfg_ssid,
    'wifipass' => $pass_wifi
);
array_push($aItems, $aItem);

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);

?>