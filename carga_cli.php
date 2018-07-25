<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Devuelve un array json con los datos solicitados de los clientes                 ║
    ╚════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$where = " clientes.id > 0";

/*
    ╔════════════════════════════════════════════════════════╗
    ║ Si se recibe la variable filtro, creo un where ║
    ╚════════════════════════════════════════════════════════╝
*/
if(isset($_POST['filtro'])) {
    if ($_POST['filtro'] == 'false')
        $where = " clientes.id NOT IN (SELECT id_cliente FROM aprovisionados)";
    else
        $where = " clientes.id > 0";
}
//              0           1       2           3           4       5           6           7           8
//    9       10          11    12    13      14      15          16            17              18
//   19                    20                   21              22
$campos=array('clientes.ID','DNI','NOMBRE','APELLIDOS','DIRECCION','LOCALIDAD','PROVINCIA','COMUNIDAD','IBAN',
    'SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','BANCO', 'DOCUMENTO_URL','DOCUMENTO',
    'TIPO_DOCUMENTO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO');


/*
    ╔═══════════════════════════════════╗
    ║ Si el usuario activo es root ║
    ╚═══════════════════════════════════╝
*/

if($_SESSION['USER_LEVEL']==0) {

    // si se indica el id del cliente, solo cargo ese cliente
    if (isset($_POST['idcliente']) && $_POST['idcliente'] != '') {
        $result = $util->selectWhere("clientes", $campos, 'clientes.id = ' . $_POST['idcliente'],'apellidos, nombre');
        $provision = $util->selectWhere("aprovisionados",array('id_en_olt','c','t','p','serial','num_pon','caja','puerto'),"id_cliente=".$_POST['idcliente']);
        $rowprovision = mysqli_fetch_array($provision);
    } else { // si no se cargan todos
//        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre",$where);
        $result = $util->selectWhere("clientes", $campos, $where,'apellidos, nombre');

    }
}else{ // no es root

    if (isset($_POST['idcliente']) && $_POST['idcliente'] != '') {
        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre", 'clientes.id = ' . $_POST['idcliente']);
        $provision = $util->selectWhere("aprovisionados",array('id_en_olt','c','t','p','serial','num_pon','caja','puerto'),"id_cliente=".$_POST['idcliente']);
        $rowprovision = mysqli_fetch_array($provision);

    } else {
        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre", $where . ' and user_create in (select id from usuarios where revendedor = '.$_SESSION['REVENDEDOR'].')');
    }

}

    $aItems = array();
    if (isset($_POST['idcliente']) && $_POST['idcliente'] != ''){
        while ($row = mysqli_fetch_array($result)) {
            $aItem = array(
                'id' => $row[0],
                'dni' => $row[1],
                'nombre' => $row[2],
                'apellidos' => $row[3],
                'direccion' => $row[4],
                'municipio' => $row[5],
                'provincia' => $row[6],
                'cp' => $row[11],
                'tel1' => $row[12],
                'tel2' => $row[13],
                'email' => $row[14],
                'notas' => $row[11],
                'alta' => $row[15],
                'region' => $row[7],
                'iban' => $row[8],
                'swift' => $row[9],
                'banco' => $row[16],
                'urldoc' => $row[17],
                'documento' => $row[18],
                'tipodoc' => $row[19],
                'idconsentimiento' => $row[20],
                'tipocli' => $row[21],
                'fnacimiento' => $row[22]
            );

            array_push($aItems, $aItem);
        }
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $aItem = array(
                'id' => $row[0],
                'dni' => $row[1],
                'nombre' => $row[2],
                'apellidos' => $row[3],
                'direccion' => $row[4],
                'municipio' => $row[5],
                'provincia' => $row[6],
                'cp' => $row[11],
                'tel1' => $row[12],
                'tel2' => $row[13],
                'email' => $row[14],
                'notas' => $row[11],
                'alta' => $row[15],
                'region' => $row[7]
            );
            array_push($aItems, $aItem);
        }
    }

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);