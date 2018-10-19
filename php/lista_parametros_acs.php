
<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 19/06/2018
 * Time: 10:38
 */

//include_once("../config/util.php");
//$util = new util();


$aItems = array();

// Configuracion de la lan, ip de gestion, mascara, dhcp inicio y fin y dns
//---------------------------------------------------------------------------------------------------------------------------------------------------

$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.IPInterface.1.IPInterfaceIPAddress', 'v' => '192.168.1.1', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MinAddress', 'v' => '192.168.1.100', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MaxAddress', 'v' => '192.168.1.150', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.IPInterface.1.IPInterfaceSubnetMask', 'v' => '255.255.255.0', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers', 'v' => '8.8.8.8,8.8.4.4', 't' => "xsd:string");
array_push($aItems, $aItem);


// Configurar wan a modo PPPOE vlan 100
//---------------------------------------------------------------------------------------------------------------------------------------------------
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Name', 'v' => 'WAN_PPOE', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_VLAN', 'v' => '100', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_SERVICELIST', 'v' => 'INTERNET', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_LANBIND.Lan1Enable', 'v' => 1, 't' => "xsd:unsignedInt");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_LANBIND.Lan2Enable', 'v' => 1, 't' => "xsd:unsignedInt");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_LANBIND.Lan3Enable', 'v' => 1, 't' => "xsd:unsignedInt");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_LANBIND.Lan4Enable', 'v' => 1, 't' => "xsd:unsignedInt");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_LANBIND.SSID1Enable', 'v' => 1, 't' => "xsd:unsignedInt");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.DNSServers', 'v' => '8.8.8.8,8.8.4.4', 't' => "xsd:string");

array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.ConnectionType', 'v' => 'IP_Routed', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.AddressingType', 'v' => 'DHCP', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username', 'v' => $ppoe_user, 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password', 'v' => $ppoe_pass, 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.NATEnabled',  'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$interfaz = 1;


// Configurar wan a modo DHCP vlan 100
//---------------------------------------------------------------------------------------------------------------------------------------------------
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_VLAN', 'v' => '100', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_SERVICELIST', 'v' => 'INTERNET', 't' => "xsd:string");
array_push($aItems, $aItem);

if (intval($_COOKIE['gestionada']) == 0) {
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_LANBIND.Lan1Enable', 'v' => 1, 't' => "xsd:unsignedInt");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_LANBIND.Lan2Enable', 'v' => 1, 't' => "xsd:unsignedInt");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_LANBIND.Lan3Enable', 'v' => 1, 't' => "xsd:unsignedInt");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_LANBIND.Lan4Enable', 'v' => 1, 't' => "xsd:unsignedInt");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.X_HW_LANBIND.SSID1Enable', 'v' => 1, 't' => "xsd:unsignedInt");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.Enable',  'v' => true, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.DNSServers', 'v' => '8.8.8.8,8.8.4.4', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.Name', 'v' => 'WAN_DHCP', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.ConnectionType', 'v' => 'IP_Routed', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.NATEnabled',  'v' => true, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Enable',  'v' => false, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
} else {
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.Enable',  'v' => false, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
}


if (intval($_COOKIE['tipoip']) == 0 && intval($_COOKIE['gestionada']) == 1) {
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.AddressingType', 'v' => 'DHCP', 't' => "xsd:string");
    array_push($aItems, $aItem);
} else if (intval($_COOKIE['tipoip']) == 1 && intval($_COOKIE['gestionada']) == 1 && $_COOKIE['ipfija'] != '') {
    /* cuando es ip statica*/
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.AddressingType', 'v' => 'Static', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.ExternalIPAddress', 'v' => $ExternalIPAddress, 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.SubnetMask', 'v' => $SubnetMask, 't' => "xsd:string");
    array_push($aItems, $aItem);
}

$interfaz = 2;


// Configurar wan a modo DHCP vlan 300 VOZIP
//---------------------------------------------------------------------------------------------------------------------------------------------------
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.X_HW_VLAN', 'v' => '300', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.X_HW_SERVICELIST', 'v' => 'INTERNET', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.Name', 'v' => 'WAN_DHCP_VOZIP', 't' => "xsd:string");
array_push($aItems, $aItem);

$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.ConnectionType', 'v' => 'IP_Routed', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.NATEnabled', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.DNSServers', 'v' => '8.8.8.8,8.8.4.4', 't' => "xsd:string");
array_push($aItems, $aItem);
//$aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.RTP.X_HW_PortName', 'v' => '4_INTERNET_R_VID_300', 't' => "xsd:string");        array_push($aItems, $aItem);
$interfaz = 4;
// fin vlan 300


// CONFIGURAMOS LA WIFI SEGUN LA INTERFAZ QUE HAYAMOS ACTIVADO

//si es una vpn (ont en modo bridge, desactivamos la wifi
if ($_COOKIE['vpn'] == 'true') {
    $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.Enable', 'v' => false, 't' => 'xsd:boolean');
    array_push($aItems, $aItem);
} else {
    $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.Enable', 'v' => true, 't' => 'xsd:boolean');
    array_push($aItems, $aItem);
}

$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.RegulatoryDomain', 'v' => 'ES', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID', 'v' => $cfg_ssid, 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey', 'v' => $pass_wifi, 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.KeyPassphrase', 'v' => $pass_wifi, 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.KeyPassphrase', 'v' => $pass_wifi, 't' => "xsd:string");
array_push($aItems, $aItem);
//FIN CONFIGURACION WIFI

//        $util->log('InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID '.$cfg_ssid);


// habilitamos upnp para aplicaciones de voz ip, juegos, etc
$aItem = array('p' => 'InternetGatewayDevice.X_HW_MainUPnP.Enable', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.X_HW_SlvUPnP.Enable', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.ManagementServer.PeriodicInformEnable', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.X_HW_Security.AclServices.HTTPWanEnable', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.ManagementServer.PeriodicInformInterval', 'v' => '525600', 't' => "xsd:string");
array_push($aItems, $aItem);


// Configurar wan a modo DHCP vlan 400 IPTV
//---------------------------------------------------------------------------------------------------------------------------------------------------
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.X_HW_VLAN', 'v' => '400', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.X_HW_SERVICELIST', 'v' => 'INTERNET', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.Name', 'v' => 'WAN_DHCP_IPTV', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.Enable', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.ConnectionType', 'v' => 'IP_Routed', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.NATEnabled', 'v' => true, 't' => "xsd:boolean");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.DNSServers', 'v' => '8.8.8.8,8.8.4.4', 't' => "xsd:string");
array_push($aItems, $aItem);

// cambiamos el tipo de wan
//---------------------------------------------------------------------------------------------------------------------------------------------------
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.X_HW_SERVICELIST', 'v' => 'VOIP', 't' => "xsd:string");
array_push($aItems, $aItem);
$aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.X_HW_SERVICELIST', 'v' => 'IPTV', 't' => "xsd:string");
array_push($aItems, $aItem);

// desactivo el usuario de voz ip para que no este intentando loguearse en el servidor hasta que no se active la voz ip

if ($_COOKIE['activarvoz'] == 'true') {
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.Enable', 'v' => true, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.Enable', 'v' => 'enabled', 't' => "xsd:string");
    array_push($aItems, $aItem);
} else {
    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.Line.1.Enable', 'v' => false, 't' => "xsd:boolean");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.Enable', 'v' => 'disabled', 't' => "xsd:string");
    array_push($aItems, $aItem);
}

// estas dos lineas cambian las vlan por las que se establecen la comunicacion de voz ip
// Signaling Port: y Media Port

// la cabecera de coria(18) a peticion de ellos aprovisionará la voz por la 100
if(intval($_COOKIE['cabecera_acs'])==18){

    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.SIP.VLANIDMark', 'v' => '100', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.RTP.VLANIDMark', 'v' => '100', 't' => "xsd:string");
    array_push($aItems, $aItem);

} else {
    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.SIP.VLANIDMark', 'v' => '300', 't' => "xsd:string");
    array_push($aItems, $aItem);
    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.RTP.VLANIDMark', 'v' => '300', 't' => "xsd:string");
    array_push($aItems, $aItem);
}

// ajustes necesarios para el correcto funcionamiento de los equipos de teleasistencia sobre sip si no es un bridge
if (intval($_COOKIE['vpn']) != 'true') {

    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.DTMFMethod', 'v' => 'InBand', 't' => "xsd:string");
    array_push($aItems, $aItem);

    $aItem = array('p' => 'InternetGatewayDevice.Services.VoiceService.1.VoiceProfile.1.RTP.TelephoneEventPayloadType', 'v' => '127', 't' => "xsd:string");
    array_push($aItems, $aItem);

}

$interfaz = 5;
// fin vlan 400*/*/
