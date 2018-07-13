<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 10/05/2018
 * Time: 13:32
 */

require_once ("acscurl.php");

$acs = new acscurl();
$acs->setCookiePath("/cookies/");
$acs->init();
echo $acs->get("http://192.168.3.251:7557/presets/");

//
//$url = "http://192.168.3.251:7557/presets/nueva";
//$postData = array("user" => "acsnexwrf", "password" => "1Nexwrf*");
///*Convierte el array en el formato adecuado para cURL*/
//$elements = array();
////foreach ($postData as $name=>$value) {
//    $elements[] = "weight=".urlencode('1');
//    $elements[] = "{precondition}=".urlencode('\"InternetGatewayDevice.DeviceInfo.SerialNumber\":\"485555555555555\"');
//
////}
//$handler = curl_init();
//curl_setopt($handler, CURLOPT_URL, $url);
//curl_setopt($handler, CURLOPT_POST,true);
//curl_setopt($handler, CURLOPT_POSTFIELDS, $elements);
//$response = curl_exec ($handler);
//curl_close($handler);


$acs->close();