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

require_once('../config/util.php');
$util = new util();

//check_session_cmr($_POST['hash']);

$campos=array('nombre_perfil','perfil_olt');

if(isset($_POST['cliente']) && $_POST['cliente']!='') {

    $result = $util->selectWhere("perfil_internet", $campos, "id_olt=".$_POST['cliente'] ,"CONVERT(SUBSTRING_INDEX(perfil_olt,'-',-1),UNSIGNED INTEGER)");

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'front' => $row[0],
            'back' => $row[1]
        );
        array_push($aItems, $aItem);
    }


    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}


    /*  OBETENER LAS VELOCIDADES DISPONIBLES
     *
     * ruta:    ftth.internetinfraestructuras.es/cmr/velocidades.php
     * espera:  hash: de momento nada
     *          cliente: pasale un 5 de momento
     * devuelve:
     *          front = Nombre que se muestra en lo select, incluye velocidad y MB
     *          back = valor que hay que retornar a cuando seleccione una velocidad
     */