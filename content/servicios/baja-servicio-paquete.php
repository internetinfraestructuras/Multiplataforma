<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/08/2018
 * Time: 9:25
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra un servicio de una instancia de un paquete en un cliente                                                   ║
    ║                                                                                                                  |
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

    Servicio::darBajaServicioPaquete($idContrato,$idLinea,$idPaquete,$idServicio);


   // Servicio::cancelarBajaServicio($idContrato,$idLinea,$idServicio,$productos);
}