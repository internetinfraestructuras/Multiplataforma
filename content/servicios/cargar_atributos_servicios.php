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

/*
 * SELECT servicios_atributos.ID_TIPO_ATRIBUTO,servicios_atributos.VALOR
 * from servicios,servicios_atributos
 * where servicios.id=servicios_atributos.ID_SERVICIO AND servicios.id=29
 */
$id=$_POST['id'];
$campos=array('servicios_atributos.ID_TIPO_ATRIBUTO','valor','servicios.id_proveedor');
$atributos = $util->selectWhere('servicios,servicios_atributos', $campos,'servicios.id=servicios_atributos.id_servicio AND servicios.id='.$id,'servicios_atributos.ID_TIPO_ATRIBUTO');

$aItems = array();


while ($row = mysqli_fetch_array($atributos)) {
    $aItem = array(
        'id_tipo_atributo' => $row[0],
        'valor' => $row[1]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);