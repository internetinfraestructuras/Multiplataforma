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

$reseller = $_SESSION['REVENDEDOR'];


$listado= $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes',
    array("productos.id",
        "productos.numero_serie",
        "productos_tipos.nombre as Tipo",
        "productos_modelos.nombre as Modelo"),
    "productos.id_tipo_producto=productos_tipos.id
            AND productos.id_modelo_producto=productos_modelos.id 
            AND almacenes.id=productos.id_almacen 
            AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND productos.estado=1");


$aItems = array();

while ($row = mysqli_fetch_array($modelos)) {
    $aItem = array(
        'id' => $row[0],
        'serial' => $row[1],
        'tipo' => $row[2],
        'modelo' => $row[3]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);