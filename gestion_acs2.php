<?php

//*******************************************************************************************************
//  Interfaz que permite a los instaladores aprovisionar uno o varios servicios a una ont
//  tras seleccionar un cliente o crear uno nuevo se activan los servicios que quiere asignar
//  Internet, voz, Tv, se busca la ont conectada o se teclea su numero pon, se selecciona la velocidad
//  y se pulsa el boton aprovisionar
//*******************************************************************************************************


if (!isset($_SESSION)) {
    @session_start();
}

require_once('config/util.php');

ini_set('display_errors', 1);
error_reporting('E_ALL');
$util = new util();
//check_session(3);

$pon=$_REQUEST['p'];
$result = $util->selectWhere('acs_ids', array('id_acs'), " pon='" . $pon."'");

while ($row = mysqli_fetch_array($result)) {
    $id_device=$row[0];
}

ini_set('max_execution_time', 6000);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?timeout=3000&connection_request";
$ch = curl_init($url);

include("php/lista_parametros_refresh.php");

foreach ($aItems as $clave) {
    array_push($cfg, $clave['p']);
    $payload = json_encode(array('name' => 'refreshObject', 'objectName' => $clave['p']));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    print_r(curl_exec($ch));
}

$a = array();

$url = "http://10.211.2.2:7557/devices/?query=%7B%22_id%22%3A%22".$id_device."%22%7D";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$json=json_decode($result,true);
include("php/lista_parametros_consultar.php");
$c=0;
foreach ($a as $clave) {
    echo $c. " ". $clave."<br>";
    $c++;
}

