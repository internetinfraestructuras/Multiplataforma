<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/08/2018
 * Time: 9:04
 */
/*
     ╔═══════════════════════════════════════════════════════════════════════╗
     ║ Devuelve un listado de numeros libres en la api             ║
     ║ correspondiente dependiendo del parametro pasado            ║
     ║ como nombre del proveedor, segun la tarifa de movil         ║
     ║ tenemos que cargar los numeros desde un proveedor u otro    ║
     ╚═══════════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(3);

require_once('../../clases/airenetwork/config.php');
require_once('../../clases/airenetwork/clases/Linea.php');
$linea=new Linea($url,$user,$pass);
$aItems = $linea->getNumerosLibres();

header('Content-type: application/json; charset=utf-8');
echo $aItems;