<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
check_session(3);

$link = mysqli_connect('localhost', 'root', '')
or die ("Error al conectar al servidor");
mysqli_select_db($link, 'etiquetas')
or die ("Error al seleccionar base datos.");
mysqli_query($link,"set names 'utf8'");




    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'serial' => $row[0]
           );
        array_push($aItems, $aItem);

}
header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);

//pathnumber