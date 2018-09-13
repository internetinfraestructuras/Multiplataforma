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
require_once('../config/util.php');
$util = new util();

check_session(3);


if(isset($_POST['olt']) && $_POST['olt']!='') {
    $olt=$_POST['olt'];
    $desde=$_POST['desde'];
    $hasta=$_POST['hasta'];

    if($_SESSION['USER_LEVEL']==0) {
        if (intval($olt) == -1) {

        } else if (intval($olt) == 0)
            if ($desde != '' || $hasta != '')
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT (DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'),' a ',DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )", 'count(id)'), "  AND (fecha BETWEEN '" . $desde . "' and '" . $hasta . "')", '', ' week(fecha,0)');
            else
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT (DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'),' a ',DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )", 'count(id)'), '', '', ' week(fecha,0)');

        else
            if ($desde != '' || $hasta != '')
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT ( DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'), ' a ', DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )", 'count(id)'), 'cabecera = ' . $olt . "  and (fecha BETWEEN '" . $desde . "' and '" . $hasta . "')", '', ' week(fecha,0)');
            else
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT ( DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'), ' a ', DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )", 'count(id)'), 'cabecera = ' . $olt, '', ' week(fecha,0)');
    } else {

        if(intval($olt)==-1){

        } else if(intval($olt)==0)
            if($desde!='' || $hasta != '')
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT (DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'),' a ',DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )",'count(id)')," cabecera in (select id from olts where wifero  = ".$_SESSION['REVENDEDOR'].") AND (fecha BETWEEN '".$desde."' and '" .$hasta."')",'', ' week(fecha,0)');
            else
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT (DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'),' a ',DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )",'count(id)'),' cabecera in (select id from olts where wifero  = '.$_SESSION['REVENDEDOR'].')','', ' week(fecha,0)');

        else
            if($desde!='' || $hasta != '')
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT ( DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'), ' a ', DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )",'count(id)'),'cabecera = '.$olt ." and cabecera in (select id from olts where wifero  = ".$_SESSION['REVENDEDOR'].") and (fecha BETWEEN '".$desde."' and '" .$hasta."')",'', ' week(fecha,0)');
            else
                $result = $util->selectWhere('fibra.aprovisionados', array("CONCAT ( DATE_FORMAT(fecha + interval 1 day,'%d/%m/%Y'), ' a ', DATE_FORMAT(fecha  + interval 7 day,'%d/%m/%Y') )",'count(id)'),'cabecera = '.$olt,' cabecera in (select id from olts where wifero  = '.$_SESSION['REVENDEDOR'].')', ' week(fecha,0)');

    }

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'mes' => $row[0],
            'cant' => $row[1]
        );
        array_push($aItems, $aItem);
    }

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}
?>