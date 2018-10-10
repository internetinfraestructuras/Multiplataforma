<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 28/09/2018
 * Time: 17:16
 */

if (!isset($_SESSION)) {
    @session_start();
}


header('Content-type: application/pdf');
include_once('../../../config/util.php');
include_once('../../../clases/airenetwork/clases/Linea.php');
include_once('../../../clases/Contrato.php');
include_once('../../../clases/Servicio.php');
include_once('../../../clases/Empresa.php');

$confAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
$url=$confAire[0][3];
$usuario=$confAire[0][1];
$pass=$confAire[0][2];

$util = new util();
check_session(1);

$cod=$util->cleanstring($_GET['codSolicitud']);
$tipo=$util->cleanstring($_GET['tipo']);

$apiAire=new Linea($url,$usuario,$pass);

$rs=$apiAire->getDocumentosSolicitud($cod,$tipo);
var_dump($rs);

?>