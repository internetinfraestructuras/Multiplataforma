<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════════════════════════════════════════╗
    LISTAR ORDENES DE TRABAJO
    LISTAR DATOS DE UNA ORDEN SOLICITADA

    Espera:
           $_POST['idorden'] si queremos obtener los datos de una orden en concreto
           $_POST['reseller'] Id del reseller
           $_POST['token']   =  obtenido en login


    Devuelve:

        'id' =>         id_tabla,
        'id_revendedor' =>
        'id_cliente' =>
        'id_usuario' =>  id usuario instalador
        'fecha' =>       fecha solicitud
        'prioridad' =>
        'fecha_limite' =>
        'fecha_montado' => fecha y hora de montaje
        'internet' =>
        'tv' =>
        'voz' =>
        'up' =>
        'down' =>
        'id_provision' =>
        'test' =>               array con los siguientes campos:

                                    'fecha_hora_test',
                                    'señal',
                                    'resultado_test'-> 'OK':'ERROR'

        Proximamente devolvera mas cosas, hay que ir viendo que es interesante


    Prueba PostMan:

   {
        "id": "1",
        "id_revendedor": "0",
        "id_cliente": "0",
        "id_usuario": "0",
        "fecha": "2018-06-12 13:37:24",
        "prioridad": "0",
        "fecha_limite": "0000-00-00",
        "fecha_montado": "0000-00-00 00:00:00",
        "internet": "0",
        "tv": "0",
        "voz": "0",
        "up": "",
        "down": "",
        "id_provision": "0",
        "test": null
    },

    ╚════════════════════════════════════════════════════════════════════════════════════════════════╝
*/

require_once('util.php');
$util = new util();

$id=$_POST['reseller'];
$token=$_POST['token'];
if(!$util->check_token($id,$token)) die("Error de token");

$campos=array('id','id_revendedor','id_cliente','id_usuario','fecha','prioridad','fecha_limite',
    'fecha_montado','internet','tv','voz','up','down','id_provision');


    if (isset($_POST['idorden']) && $_POST['idorden'] != '') {
        $result = $util->selectWhere("ordenes", $campos, 'id = ' . $_POST['idorden']);
    } else{
        $result = $util->selectWhere("ordenes", $campos);
    }


    $aItems = array();
    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'id' => $row[0],
            'id_revendedor' => $row[1],
            'id_cliente' => $row[2],
            'id_usuario' => $row[3],
            'fecha' => $row[4],
            'prioridad' => $row[5],
            'fecha_limite' => $row[6],
            'fecha_montado' => $row[7],
            'internet' => $row[8],
            'tv' => $row[9],
            'voz' => $row[10],
            'up' => $row[11],
            'down' => $row[12],
            'id_provision' => $row[13],
            'test' => $row[14]
        );
        array_push($aItems, $aItem);
    }

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);