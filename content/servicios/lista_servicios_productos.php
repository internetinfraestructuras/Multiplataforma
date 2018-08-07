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

// recorro el array de servicios contratados, obtengo los id de los servicios,
// construyo la cadena para pasar como parte de la consulta select IN

$servicios=$_POST['servicios'];
$in='';
foreach ($servicios as $servicio){
    $in = $in  . $servicio[0]. ",";
}

// borro la ultima coma
$in = substr($in,0,-1);


$campos=array('ID','NOMBRE');
$provincias = $util->selectWhere('servicios_tipos_atributos', $campos,'servicios_tipos_atributos.ID_SERVICIO='.$id." AND servicios_tipos_atributos.id_tipo=1",'NOMBRE');

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