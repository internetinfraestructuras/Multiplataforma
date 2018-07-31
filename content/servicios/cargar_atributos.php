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
require_once('../../config/util.php');
$util = new util();
check_session(3);

$id=$_POST['id'];
$campos=array('ID','NOMBRE');
$provincias = $util->selectWhere('servicios_tipos_atributos', $campos,'servicios_tipos_atributos.ID_SERVICIO='.$id,'NOMBRE');

$aItems = array();


while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'ID' => $row[0],
        'NOMBRE' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);