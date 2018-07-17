<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
     ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve los modelos de productos del tipo select.  ║
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
$modelos = $util->selectWhere('productos_modelos', $campos,'id_tipo='.$id,'nombre');

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