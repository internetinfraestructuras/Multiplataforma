<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve un listado de servicios completo         ║
    ║       ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(3);

$campos=array('ID','NOMBRE','FECHA_INICIO','FECHA_FIN','DURACION','DESCUENTO');

$provincias = $util->selectWhere('campanas', $campos,' (CURRENT_DATE BETWEEN FECHA_INICIO AND FECHA_FIN) AND ID_EMPRESA='.$_SESSION['REVENDEDOR']);

$aItems = array();

while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'idcampana' => $row[0],
        'nombre' => $row[1],
        'fini' => $row[2],
        'ffin' => $row[3],
        'duracion' => $row[4],
        'descuento' => $row[5]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);

