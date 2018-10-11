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
require_once('../../../clases/Contrato.php');
require_once('../../../clases/Servicio.php');
require_once('../../../clases/Empresa.php');



$confAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
$url=$confAire[0][3];
$usuario=$confAire[0][1];
$pass=$confAire[0][2];

$util = new util();
check_session(1);

$valor=$util->cleanstring($_POST['valor']);
$numero=$util->cleanstring($_POST['numero']);
$idContrato=$util->cleanstring($_POST['idContrato']);
$idLineaDetalle=$util->cleanstring($_POST['idLineaDetalle']);
$tipo=$util->cleanstring($_POST['tipo']);

if($idLineaDetalle=="null")
{

}
else
{
    $numeroMax=0;

    $numeroAtributos=Servicio::getAtributosTecnicosServicio($tipo);
    $numeroMax=$idLineaDetalle+($numeroAtributos-1);

    $lineasDetalles=Contrato::getLineasDetallesServicio($idLineaDetalle,$numeroMax);


    $apiAire=new Linea($url,$usuario,$pass);

    if($valor=='S')//S:solicitar corte
        $rs=$apiAire->setCorteImpago($numero);
    if($valor=='C')//C:Cancelar solicitud de corte
        $rs=$apiAire->setCancelarCorteImpago($numero);
    if($valor=='R')//C:Cancelar solicitud de corte
        $rs=$apiAire->setRestablecerCorteImpago($numero);


    //Si la operación es correcta pasamos las lineas a impago
    if($rs==OPERACION_OK_MASMOVIL && $valor=='S' )
    {
       for($i=0;$i<count($lineasDetalles);$i++)
       {
            $idLinea=$lineasDetalles[$i][0];
            Contrato::setLineaDetallesImpago($idLineaDetalle);
       }
    }
    if($rs==OPERACION_OK_MASMOVIL && $valor=='C' || $valor=='R')
    {
        for($i=0;$i<count($lineasDetalles);$i++)
        {
            $idLinea=$lineasDetalles[$i][0];
            Contrato::setLineaDetalleAlta($idLineaDetalle);

        }
    }

    return $rs;
}
?>



