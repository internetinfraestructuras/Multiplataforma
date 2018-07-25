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

if($id==28){
    $campos=array('id','comunidad');
    $provincias = $util->selectWhere('comunidades', $campos,'','comunidad');

} else {
    $campos=array('id','estadonombre');
    $provincias = $util->selectWhere('estado', $campos,'ubicacionpaisid='.$id,'estadonombre');

}

$aItems = array();


while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'id' => $row[0],
        'region' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);