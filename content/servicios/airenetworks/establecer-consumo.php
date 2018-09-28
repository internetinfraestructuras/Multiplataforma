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
require_once('../../../clases/airenetwork/clases/Linea.php');
require_once('../../../clases/Empresa.php');

$confAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
$url=$confAire[0][3];
$usuario=$confAire[0][1];
$pass=$confAire[0][2];
$util = new util();
check_session(1);

$numero=$util->cleanstring($_POST['numero']);
$valor=$util->cleanstring($_POST['valor']);

$apiAire=new Linea($url,$usuario,$pass);

$rs=$apiAire->setConsumoMaximo($numero,$valor);

return $rs;



