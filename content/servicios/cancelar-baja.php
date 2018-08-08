<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 07/08/2018
 * Time: 13:10
 */


/*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra de la cabecera un service port y una ont                                                                   ║
    ║ Recibe como parametro post a: tiene que ser un string 'borrar_en_olt                                             ║
    ║ el id del registro a borrar y que se encuentra en la tabla aprovisionados, de ahi cogemos los datos de la        ║
    ║ cabecera y todo lo demas como pon, c, t, p, etc                                                                           ║
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
    $id=$_POST['id'];
    $idContrato=$_POST['idContrato'];
    $idLinea=$_POST['idLinea'];
    $idServicio=$_POST['idServicio'];
    $productos=$_POST['productos'];


    Servicio::cancelarBajaServicio($idContrato,$idLinea,$idServicio,$productos);
}