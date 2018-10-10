<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 09/10/2018
 * Time: 11:28
 */

require_once('../config/util.php');
$util = new util();
check_session(3);

$campos=array('clientes_documentos.DOCUMENTO','clientes_documentos_tipos.NOMBRE');

if(isset($_POST['cliente']) && $_POST['cliente']!='') {

    $result = $util->selectJoin("clientes_documentos", $campos,
        " JOIN clientes_documentos_tipos ON clientes_documentos_tipos.ID = clientes_documentos.ID_TIPO_DOCUMENTO" ,
        "clientes_documentos.ID"," clientes_documentos.ID_CLIENTE = ". $_POST['cliente']);

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'tipo' => $row[1],
            'nombre' => $row[0]
        );
        array_push($aItems, $aItem);
    }

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($aItems);
}

