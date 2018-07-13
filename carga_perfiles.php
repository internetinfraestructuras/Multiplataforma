<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔═════════════════════════════════════════════════════════════════════════╗
    ║ Devuelve los perfiles de velocidad de  la cabecera indicada  ║
    ╚═════════════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$campos=array('nombre_perfil','perfil_olt');

if(isset($_POST['cabecera']) && $_POST['cabecera']!='') {

    $result = $util->selectWhere("perfil_internet", $campos, "id_olt=".$_POST['cabecera'] ,"CONVERT(SUBSTRING_INDEX(perfil_olt,'-',-1),UNSIGNED INTEGER)");

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'nombre_perfil' => $row[0],
            'perfil_olt' => $row[1]
        );
        array_push($aItems, $aItem);
    }


    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
