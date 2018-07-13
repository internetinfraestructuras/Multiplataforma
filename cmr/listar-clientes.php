<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════════════════════════════════════════╗
            Devuelve un array json con los datos solicitados de los clientes
    Espera:
           $_POST['idcliente'] si queremos obtener los datos de un cliente en concreto
           $_POST['dni'] si queremos obtener los datos de un cliente en concreto buscando por dni
           $_POST['reseller'] Id del reseller
           $_POST['hash'] Id del reseller en md5
           $_POST['token']   =  obtenido en login


    Devuelve:

    Si especificamos un id:

        'id' =>
        'dni' =>
        'nombre' =>
        'apellidos' =>
        'direccion' =>
        'localidad_s' =>  String nombre de la localidad
        'provincia_s' =>  String nombre de la provincia
        'cp' =>
        'tel1' =>
        'tel2' =>
        'email' =>
        'notas' =>
        'alta' =>
        'modifica' =>
        'region_i' =>   Integer: Id tabla regiones
        'provincia_i' => Integer: Id tabla provincias
        'localidad_i' =>  Integer: Id tabla localidades

    Si no especificamos un id, devolvemos todos:

        'id' =>
        'dni' =>
        'nombre' =>
        'apellidos' =>
        'direccion' =>
        'municipio' =>
        'provincia' =>
        'cp' =>
        'tel1' =>
        'tel2' =>
        'email' =>
        'notas' =>
        'alta' =>
        'modifica' =>


    Prueba PostMan:

    {
        "id": "230",
        "dni": "15435560F",
        "nombre": "Adrián",
        "apellidos": "Sánchez Ortiz",
        "direccion": "C/Cruz, Nº28",
        "municipio": "Prado del Rey",
        "provincia": "Cádiz",
        "cp": "11660",
        "tel1": "651551272",
        "tel2": "",
        "email": "guapiji@gmail.com",
        "notas": "",
        "alta": "2018-05-15",
        "modifica": "2018-05-17 12:07:26",
        "region_i": "1",
        "provincia_i": "11",
        "localidad_i": "1790"
    },

    ╚════════════════════════════════════════════════════════════════════════════════════════════════╝
*/

require_once('util.php');
$util = new util();

$id=$_POST['reseller'];
$token=$_POST['token'];
if(!$util->check_token($id,$token)) die("Error de token");

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

$campos=array('clientes.id','dni','nombre','apellidos','direccion','municipios.municipio','provincias.provincia', 'cp','tel1','tel2','email','notas','fecha_alta','fecha_modificacion','clientes.region','clientes.provincia','clientes.localidad','comunidades.comunidad','provincias.provincia','municipios.municipio');

/*
    ╔═══════════════════════════════════╗
    ║ Si el usuario activo es root ║
    ╚═══════════════════════════════════╝
*/

    if (isset($_POST['idcliente']) && $_POST['idcliente'] != '') {
        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN comunidades ON comunidades.id = clientes.region LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre", 'clientes.id = ' . $_POST['idcliente']);

    } else if (isset($_POST['reseller']) && (md5($_POST['reseller'])==$_POST['hash'])){
        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN comunidades ON comunidades.id = clientes.region LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre", $where . ' and user_create = '.$_POST['reseller']);
    } else if (isset($_POST['dni']) && ($_POST['dni']!='')){
        $result = $util->selectJoin("clientes", $campos, "LEFT JOIN comunidades ON comunidades.id = clientes.region LEFT JOIN municipios ON municipios.id = clientes.localidad LEFT JOIN provincias ON provincias.id=clientes.provincia", "nombre", ' dni = '.$_POST['dni']);
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
                'localidad_s' => $row[5],
                'provincia_s' => $row[6],
                'cp' => $row[7],
                'tel1' => $row[8],
                'tel2' => $row[9],
                'email' => $row[10],
                'notas' => $row[11],
                'alta' => $row[12],
                'modifica' => $row[13],
                'region_i' => $row[14],
                'provincia_i' => $row[15],
                'localidad_i' => $row[16]
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
                'cp' => $row[7],
                'tel1' => $row[8],
                'tel2' => $row[9],
                'email' => $row[10],
                'notas' => $row[11],
                'alta' => $row[12],
                'modifica' => $row[13],
                'region_i' => $row[14],
                'provincia_i' => $row[15],
                'localidad_i' => $row[16]
            );
            array_push($aItems, $aItem);
        }
    }

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);