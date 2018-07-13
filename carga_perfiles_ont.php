<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve los modelos ont  de la cabecera indicada ║
    ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);
$campos=array('perfil');

if(isset($_POST['cabecera']) && $_POST['cabecera']!='') {

    $result = $util->selectWhere("modelos_ont", $campos, "cabecera=".$_POST['cabecera'] ,"perfil");

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'nombre_perfil' => $row[0]
        );
        array_push($aItems, $aItem);
    }

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
