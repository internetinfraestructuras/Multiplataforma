<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve listado de revendedores                  ║
    ╚════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(1);

$campos=array('revendedores.id','dni','nombre','apellidos','empresa','direccion','municipios.municipio','provincias.provincia', 'cp','tel1','tel2','email','web','logo','notas');
if($_SESSION['USER_LEVEL']==0) {

    $result = $util->selectJoin("revendedores",
        $campos, "JOIN municipios ON municipios.id = revendedores.localidad JOIN provincias ON provincias.id=revendedores.provincia", "nombre");
} else {
    $result = $util->selectJoin("revendedores",
        $campos, "JOIN municipios ON municipios.id = revendedores.localidad JOIN provincias ON provincias.id=revendedores.provincia", "nombre",' id = '.$_SESSION['REVENDEDOR'].')');
}

$aItems = array();

while ($row = mysqli_fetch_array($result)) {
    $aItem = array(
        'id' => $row[0],
        'dni'=> $row[1],
        'nombre'=> $row[2],
        'apellidos'=> $row[3],
        'empresa'=> $row[4],
        'direccion'=> $row[5],
        'municipio'=> $row[6],
        'provincia'=> $row[7],
         'cp'=> $row[8],
        'tel1'=> $row[9],
        'tel2'=> $row[10],
        'email'=> $row[11],
        'web'=> $row[12],
        'logo'=> $row[13],
        'notas'=> $row[14]
    );
    array_push($aItems, $aItem);

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);

