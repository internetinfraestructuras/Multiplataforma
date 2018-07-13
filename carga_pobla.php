<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve las poblaciones de la provincia indicada ║
    ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$id=$_POST['id'];
$campos=array('id','municipio');
$provincias = $util->selectWhere('municipios', $campos,'provincia_id='.$id,'municipio');

$aItems = array();


while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'id' => $row[0],
        'municipio' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);