<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve listado de la tabla estado_olts          ║
    ║ esta tabla se carga desde /cronjobs/estado_ont.php║
    ║ este fichero se carga cada hora por crontab       ║
    ╚════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$campos=array('id','description','run_state','temp','rx','tx','rx_olt','volt','ip','pon','fecha','lastcause','datecause');

$where='';
// si se recibe el parametro filtro se crea un like con esa cadena en el campo descripcion
// el filtro sirve para que el usuario pueda filtrar por un cliente
// el filtro se carga cuando se teclean mas de tres caracteres para no saturar


if($_POST['filtro']!=''){
    $where = ' and upper(description) like "%'.strtoupper($_POST['filtro']).'%"';
}

if($_POST['orden']!=''){
    switch(intval($_POST['orden'])){
        case 1:
            $order =  "ip";
            break;
        case 2:
            $order =  "pon";
            break;
        case 3:
            $order =  "rx";
            break;
        case 0:
            $order =  "description";
            break;
        default:
            $order =  "run_state";
            break;
    }
}



// si se recibe un olt se filta solo de las olt indicada

if(isset($_POST['olt']) && intval($_POST['olt'])>0){
    $olt=$_POST['olt'];


    if($_SESSION['USER_LEVEL']==0)  // si es root se cargan todas
        $result = $util->selectWhere("estado_olts", $campos,"id_olt=".$olt . $where,$order);
    else    // si no es root solo las de ese revendedor
        $result = $util->selectWhere("estado_olts", $campos,"id_olt=".$olt . $where,$order);

} else {    // si no se especifica una olt se cargan todas

    if($_SESSION['USER_LEVEL']==0)
        $result = $util->selectWhere("estado_olts", $campos,"1" . $where,$order);
    else
        $result = $util->selectWhere("estado_olts", $campos,"id_olt in (select id from olts where wifero =(select revendedor from usuarios where id=". $_SESSION['USER_ID']."))". $where,'run_state');

}

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        if(!strpos($row[1],'inicial.indice')>0) {
            $aItem = array(
                'id' => $row[0],
                'cliente' => ucfirst($row[1]),
                'estado' => trim($row[2]),
                'tmp' => $row[3],
                'rx' => $row[4],
                'tx' => $row[5],
                'rxolt' => $row[6],
                'volt' => $row[7],
                'ip' => $row[8],
                'pon' => $row[9],
                'fecha' => $row[10],
                'cause' => $row[11],
                'datecause' => $row[12]
            );
            array_push($aItems, $aItem);
        }
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);