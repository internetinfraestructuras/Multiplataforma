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

$campos=array('servicios.ID','ID_SERVICIO_TIPO','servicios.NOMBRE','PRECIO_PROVEEDOR','IMPUESTO','BENEFICIO','PVP','servicios_tipos.NOMBRE');

$provincias = $util->selectJoin('servicios', $campos,' JOIN servicios_tipos ON servicios_tipos.id = servicios.ID_SERVICIO_TIPO ',
    'servicios.NOMBRE',' ID_EMPRESA='.$_SESSION['REVENDEDOR']);

$aItems = array();

while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'idservicio' => $row[0],
        'id_tipo' => $row[1],
        'comercial' => $row[2],
        'coste' => $row[3],
        'impuesto' => $row[4],
        'beneficio' => $row[5],
        'pvp' => $row[6],
        'tipo' => $row[7]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);
