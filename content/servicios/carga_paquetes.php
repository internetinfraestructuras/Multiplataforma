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
require_once('../../config/util.php');
$util = new util();
check_session(3);

$id=$_POST['idpaquete'];
$campos=array('servicios.ID','ID_SERVICIO_TIPO','NOMBRE_COMERCIAL','PRECIO_PROVEEDOR','IMPUESTO','BENEFICIO','PVP');

$provincias = $util->selectJoin('servicios', $campos, ' JOIN paquetes_servicios ON paquetes_servicios.ID_SERVICIO = servicios.ID ',
    'NOMBRE_COMERCIAL','paquetes_servicios.ID_PAQUETE='.$id.' AND ID_EMPRESA='.$_SESSION['REVENDEDOR']);

$aItems = array();

while ($row = mysqli_fetch_array($provincias)) {
    $aItem = array(
        'idservicio' => $row[0],
        'tipo' => $row[1],
        'comercial' => $row[2],
        'coste' => $row[3],
        'impuesto' => $row[4],
        'beneficio' => $row[5],
        'pvp' => $row[6]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);