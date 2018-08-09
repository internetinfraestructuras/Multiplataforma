<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/08/2018
 * Time: 9:04
 */
/*
     ╔═════════════════════════════════════════════════════════════╗
     ║ Devuelve un listado de numeros pendientes de portar║
     ║ tanto de fijos como de moviles para un cliente     ║
     ╚═════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(3);

$id=$_POST['id'];
$reseller = $_SESSION['REVENDEDOR'];


$campos=array('ID','TIPO','NUMERO_PORTAR');
$modelos = $util->selectWhere('portabilidades', $campos,'ID_CLIENTE = '.$id.' AND ID_EMPRESA = ' .$reseller .
    ' AND ESTADO NOT IN (4,6) ','FECHA_SOLICITUD');

$aItems = array();


while ($row = mysqli_fetch_array($modelos)) {
    $aItem = array(
        'id' => $row[0],
        'tipo' => $row[1],
        'num' => $row[2]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);