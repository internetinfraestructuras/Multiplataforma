<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve las provincias de la comunidad indicada  ║
    ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$id=$_POST['id'];
$campos=array('id','provincia');
$provincias = $util->selectWhere('provincias', $campos,'comunidad_id='.$id,'provincia');

$aItems = array();


while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'id' => $row[0],
        'provincia' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);