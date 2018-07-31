<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve un listado de servicios incluidos en el  ║
    ║  paquete pasado como variable post"idpaquete"     ║
    ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(3);

$id=$_POST['idpaquete'];
$campos=array('servicios.ID','ID_SERVICIO_TIPO','servicios.NOMBRE','PRECIO_PROVEEDOR','IMPUESTO','BENEFICIO','PVP','servicios_tipos.NOMBRE');

$paquetes = $util->selectJoin('servicios', $campos, ' JOIN paquetes_servicios ON paquetes_servicios.ID_SERVICIO = servicios.ID '.
    ' JOIN servicios_tipos ON servicios_tipos.id = servicios.ID_SERVICIO_TIPO ',
    'servicios.NOMBRE','paquetes_servicios.ID_PAQUETE='.$id.' AND ID_EMPRESA='.$_SESSION['REVENDEDOR']);

$aItems = array();

while ($row = mysqli_fetch_array($paquetes)) {
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