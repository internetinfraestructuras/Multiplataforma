<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
     ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve los tipos de producto del proveedor        ║
     ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(3);

$id=$_POST['id'];
$campos=array('id','nombre');
$modelos = $util->selectWhere('servicios_tipo', $campos,'','nombre');

$aItems = array();


while ($row = mysqli_fetch_array($modelos)) {
    $aItem = array(
        'id' => $row[0],
        'nombre' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);