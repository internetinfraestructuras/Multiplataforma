<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
//require_once('../../config/def_tablas.php');
require_once('../../clases/Contrato.php');

$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');


if (isset($_POST['action']) && $_POST['action'] == 'contrato') {
    $borrador=$_POST['id_borrador'];
    $borrador=$_POST['id_campana'];
    $dto=$_POST['dto'];
    $dias=$_POST['dias'];
    $hasta=$_POST['hasta'];
    $pack=$_POST['pack'];
    $extras=$_POST['extras'];
    $firma=$_POST['firma'];
    $lineas=$_POST['lineas'];
    $cliente=$_POST['cliente'];


} else {
    echo "nose";
    die();
}
?>