<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 29/05/2018
 * Time: 9:03
 */


$aItem = array(
    'p' => 'InternetGatewayDevice.ManagementServer.PeriodicInformInterval',
    'v' => '43200',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.ManagementServer.ConnectionRequestUsername',
    'v' => 'Nexwrfacs',
    't' => "xsd:string"
);
array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.ManagementServer.ConnectionRequestPassword',
    'v' => '1NexwrfAcs*',
    't' => "xsd:string"
);
array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.X_HW_LANBIND.SSID2Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.X_HW_LANBIND.SSID3Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.X_HW_LANBIND.SSID4Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);


$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.Lan2Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.Lan3Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.Lan4Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.SSID1Enable',
    'v' => '1',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.SSID2Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.SSID3Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_LANBIND.SSID4Enable',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.Enable',
    'v' => 'true',
    't' => 'xsd:boolean'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.Reset',
    'v' => 'false',
    't' => 'xsd:boolean'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.ConnectionType',
    'v' => 'IP_Routed',
    't' => "xsd:string"
);
array_push($aItems, $aItem);



//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.X_HW_VLAN',
//        'v' => '100',
//        't' => "xsd:string",
//    );
//    array_push($aItems, $aItem);
//
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username',
////        'v' => '',
//        'v' => 'temporal',
//        't' => "xsd:string",
//        'd' => 'ppoeuser'
//    );
//    array_push($aItems, $aItem);
//
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password',
////        'v' => '',
//        'v' => 'temporal',
//        't' => "xsd:string",
//        'd' => 'ppoepass'
//    );
//    array_push($aItems, $aItem);
//
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.NATEnabled',
//        'v' => 'true',
//        't' => "xsd:string",
//    );
//    array_push($aItems, $aItem);
//
//
//
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_SERVICELIST',
//        'v' => 'INTERNET',
//        't' => "xsd:string"
//    );
//    array_push($aItems, $aItem);
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_VLAN',
//        'v' => '100',
//        't' => 'xsd:unsignedInt'
//    );
//    array_push($aItems, $aItem);
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_PRI',
//        'v' => '0',
//        't' => 'xsd:unsignedInt'
//    );
//    array_push($aItems, $aItem);
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_IPv4Enable',
//        'v' => 'true',
//        't' => 'xsd:boolean'
//    );
//    array_push($aItems, $aItem);
//    $aItem = array(
//        'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANPPPConnection.1.X_HW_IPv6Enable',
//        'v' => 'false',
//        't' => 'xsd:boolean'
//    );
//    array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.X_HW_LANBIND.SSID1Enable',
    'v' => '1',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);

$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.AddressingType',
    'v' => 'DHCP',
    't' => "xsd:string"
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.X_HW_SERVICELIST',
    'v' => 'INTERNET',
    't' => "xsd:string"
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.2.X_HW_VLAN',
    'v' => '100',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.2.X_HW_PRI',
    'v' => '0',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.2.NATEnabled',
    'v' => 'true',
    't' => 'xsd:boolean'
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.2.ConnectionType',
    'v' => 'IP_Routed',
    't' => "xsd:string"
);
array_push($aItems, $aItem);
$aItem = array(
    'p' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.2.X_HW_VLAN',
    'v' => '100',
    't' => 'xsd:unsignedInt'
);
array_push($aItems, $aItem);
