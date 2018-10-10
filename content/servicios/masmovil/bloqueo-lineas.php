<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 25/09/2018
 * Time: 16:45


    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ BLOQUEO/DESBLOQUEOS DE LÍNEAS ACTIVAS EN MAS MOVIL                                                               ║
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


if($valor=="A")//REACTIVACION LINEA
{
    $rs=$apiMasMovil->reactivacionLineaMovil($cliente,$numero);
    $apiMasMovil->setLogApi($numero,$rs,$_SESSION['REVENDEDOR'],ID_BLOQUEO_LINEA_TEMPORAL);
    var_dump($rs);
}

else if($valor=="S")//SUSPENSION TEMPORAL
{
    $rs=$apiMasMovil->suspensionLineaMovil($cliente,$numero);
    $apiMasMovil->setLogApi($numero,$rs,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);
    var_dump($rs);
}


