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
    $restablecer=$_POST['restablecer'];
    $tipo=$_POST['tipo'];



    var_dump($_POST);
    if($restablecer==1)
         Servicio::setImpagoServicio($idContrato,$idLinea,$tipo,"",$idServicio);
    else
        Servicio::setRestablecerServicioCorteImpago($idContrato,$idLinea,$tipo,"",$idServicio);



}