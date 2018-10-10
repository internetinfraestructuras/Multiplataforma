<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ ACTIVA/DESACTIVA SERVICIO DE ROAMING EN LÍNEAS DE MÁSMOVIL!!!                                                       ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../../config/util.php');
require_once('../../../clases/masmovil/MasMovilAPI.php');
$util = new util();
check_session(1);

$cliente=$util->cleanstring($_POST['refCliente']);
$numero=$util->cleanstring($_POST['numero']);
$valor=$util->cleanstring($_POST['valor']);

$apiMasMovil=new MasMovilAPI();

if($valor=="A")
    $idTransaccion=2;
else if($valor=="D")
    $idTransaccion=3;

$rs=$apiMasMovil->setRoaming($cliente,$numero,$valor,"");
var_dump($rs);
$apiMasMovil->setLogApi($numero,$rs,$_SESSION['REVENDEDOR'],$idTransaccion);








