<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/08/2018
 * Time: 9:25
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║                                                                                                                  ║
    ║               REALIZA UN CORTE/RESTABLECE CORTE POR IMPAGO EN LOS SERVICIOS DE FORMA INDEPENDIENTE               ║                                                                                                     |
    ║                                                                                                                  ║
    ║                                                                                                                  ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once ('../../clases/Servicio.php');
$util = new util();
check_session(1);

if(isset($_POST['id']) && $_POST['id']!='')
{
    $idServicio=$_POST['id'];
    $idContrato=$_POST['idContrato'];
    $idLinea=$_POST['idLineaContrato'];
    $idPaquete=$_POST['idPaquete'];
    $restablecer=$_POST['restablecer'];
    $idLineaDetalle=$_POST['idLineaDetalle'];

    $tipo=Servicio::getTipoServicio($_SESSION['REVENDEDOR'],$idServicio);
    $tipo=$tipo[0][0];




    if($restablecer==1)
         Servicio::setImpagoServicioPaquete($idContrato,$idLinea,$tipo,"",$idServicio,$idLineaDetalle);
    else
        Servicio::setRestablecerCorteImpago($idContrato,$idLinea,$tipo,"",$idServicio,$idLineaDetalle);



}