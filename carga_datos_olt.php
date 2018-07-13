<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════╗
    ║ Devuelve los datos de la cabecera indicada     ║
    ╚════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);


if(isset($_POST['cabecera']) && $_POST['cabecera']!='') {

    $campos=array('chasis','tarjeta', 'pon', 'id_inicial','serportini');

    $result = $util->selectWhere("olts", $campos, "id=".$_POST['cabecera']);
    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'c' => $row[0],
            't' => $row[1],
            'p' => $row[2],
            'id' => $row[3],
            'sp' => $row[4]
        );
        array_push($aItems, $aItem);
    }


    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
