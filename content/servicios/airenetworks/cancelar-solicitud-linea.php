<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 28/09/2018
 * Time: 16:58
 */

if (!isset($_SESSION)) {
    @session_start();
}

include_once('../../../config/util.php');
require_once('../../../clases/airenetwork/clases/Linea.php');
require_once('../../../clases/Empresa.php');


$confAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);

$url=$confAire[0][3];
$usuario=$confAire[0][1];
$pass=$confAire[0][2];

$util = new util();
check_session(1);

$cod=$util->cleanstring($_POST['codSolicitud']);

$apiAire=new Linea($url,$usuario,$pass);

$rs=$apiAire->setCancelarSolicitudLinea($cod);




switch($rs)
{
    case "0001":
        $rs="0001:La cancelación de la solicitud de línea se ha efectuado correctamente";
        break;
    case "0007":
        $rs="0007:Error al obtener la solicitud de línea";
    break;
    case "0008":
        $rs="0008:Código incorrecto";
        break;
    case "0009":
        $rs="0009:Error al obtener estado solicitud";
        break;
    case "0010":
        $rs="0010:El estado no es correcto para solicitar la cancelación.";
        break;
    case "0011":
        $rs="0011:Error al cancelar la solicitud";
        break;
    case "0012":
        $rs="0012:El periodo de cancelación de portabilidad ha expirado";
        break;
    case "0013":
        $rs="0013:Error al cancelar la solicitud";
        break;
    case "0014":
        $rs="0014:Error al cancelar la solicitud";
        break;
    case "0015":
        $rs="0015:Error al cancelar la solicitud";
    break;
}
echo $rs;


?>