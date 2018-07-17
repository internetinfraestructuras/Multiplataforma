<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Devuelve un listado de la tabla aprovisionados    ║
    ╚════════════════════════════════════════════════════════════╝
*/

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

// campos que se van a devolver
$campos=array(
    'aprovisionados.fecha,aprovisionados.lat, aprovisionados.lon, aprovisionados.velocidad_up, aprovisionados.velocidad_dw,
            aprovisionados.ppoe_usuario, aprovisionados.ppoe_password, aprovisionados.id_en_olt, aprovisionados.c,aprovisionados.t,
            aprovisionados.p, aprovisionados.caja, aprovisionados.puerto, MAX(aprovisionados.id_internet), aprovisionados.serial, 
            aprovisionados.descripcion, aprovisionados.num_pon, clientes.nombre, clientes.apellidos, clientes.direccion, clientes.dni,
            clientes.tel1 , clientes.tel2, clientes.email, clientes.fecha_alta,aprovisionados.id, MAX(aprovisionados.id_voip),
             MAX(aprovisionados.id_iptv), MAX(aprovisionados.id_vpn),aprovisionados.cabecera,aprovisionados.internet,aprovisionados.iptv,aprovisionados.voip ');

// orden default
$orden = " aprovisionados.fecha ";

// si se especifica un orden, se ordena por el

if (isset($_POST['orden']) && $_POST['orden']=='pon'){
    $orden = "aprovisionados.num_pon " . $_POST['modo'];
}
if (isset($_POST['orden']) && $_POST['orden']=='ser'){
    $orden = "aprovisionados.serial " . $_POST['modo'];
}
if (isset($_POST['orden']) && $_POST['orden']=='cli'){
    $orden = "clientes.apellidos ". $_POST['modo'].", clientes.nombre " . $_POST['modo'];
}
if (isset($_POST['orden']) && $_POST['orden']=='dat'){
    $orden = "aprovisionados.fecha " . $_POST['modo'];
}




// si se recibe id_cliente o pon o id provision ...
if(
    (isset($_POST['id_cliente']) && $_POST['id_cliente']!='')
    || ((isset($_POST['pon']) && $_POST['pon']!=''))
    ||(isset($_POST['id_provision']))
){

    // si se especifica un id de cliente solo se carga ese cliente

    if (isset($_POST['id_cliente'])){
        if ($_SESSION['USER_LEVEL'] == 0)   // si es root cargo todos
            $result = $util->selectJoin("aprovisionados", $campos,
                " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", $orden,'','aprovisionados.num_pon');
        else    // si no es root, cargo solo los suyos
            $result = $util->selectJoin("aprovisionados", $campos,
                " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", $orden,
                " clientes.user_create in (SELECT id FROM usuarios WHERE revendedor = (select revendedor from usuarios where id = " . $_SESSION['USER_ID'] . "))",'aprovisionados.num_pon');

        // si se especifica un id, cargo solo los datos del cliente de una provision

    } else if (isset($_POST['id_provision'])){
        $result = $util->selectJoin("aprovisionados", $campos,
            " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", $orden,
            " aprovisionados.id = ".$_POST['id_provision']);

    } else {    // si no se recibe un id de cliente o id de provision los cargo todos

        if ($_SESSION['USER_LEVEL'] == 0)   // si es root todos, todos
            $result = $util->selectJoin("aprovisionados", $campos,
                " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ",
                $orden,' num_pon = "'.$_POST['pon'].'"','aprovisionados.num_pon');
        else    // si no es root todos los de ese revendedor
            $result = $util->selectJoin("aprovisionados", $campos,
                " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", $orden,
                " clientes.user_create in (SELECT id FROM usuarios WHERE revendedor = (select revendedor from usuarios where id = " . $_SESSION['USER_ID'] . ")) and num_pon = '".$_POST['pon']."'",'aprovisionados.num_pon');

    }


    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {
        $aItem = array(
            'fecha' => date_format(date_create($row[0]),"d/m/Y H:i:s"),
            'lat' => $row[1],
            'lon' => $row[2],
            'velocidad_up' => $row[3],
            'velocidad_dw' => $row[4],
            'ppoe_usuario' => $row[5],
            'ppoe_password' => $row[6],
            'id_en_olt' => $row[7],
            'c' => $row[8],
            't' => $row[9],
            'p' => $row[10],
            'caja' => $row[11],
            'puerto' => $row[12],
            'id_internet' => $row[13],
            'serial' => $row[14],
            'descripcion' => $row[15],
            'num_pon' => $row[16],
            'cli_nombre' => $row[17],
            'cli_apellidos' => $row[18],
            'cli_direccion' => $row[19],
            'cli_dni' => $row[20],
            'cli_tel1' => $row[21],
            'cli_tel2' => $row[22],
            'cli_email' => $row[23],
            'cli_fecha_alta' => $row[24],
            'prov_id' => $row[25],
            'id_vozip' => $row[26],
            'id_iptv' => $row[27],
            'id_vpn' => $row[28],
            'id_cabecera' => $row[29],
            'int_sino' => $row[30],
            'tv_sino' => $row[31],
            'tel_sino' => $row[32]
        );
        array_push($aItems, $aItem);

    }
} else { // si NO se recibe id_cliente o pon o id provision ... los cargo todos

    if ($_SESSION['USER_LEVEL'] == 0) {  // si es root cargo todos
        $where=" where 1 ";

        if (isset($_POST['filtro']) && $_POST['filtro']!=''){
            $filtro=$_POST['filtro'];
            $where = $where . " AND clientes.dni LIKE '%".$filtro."%' OR clientes.nombre LIKE '%".$filtro.
                "%' OR clientes.apellidos LIKE '%".$filtro."%' OR  aprovisionados.num_pon LIKE '%".$filtro."%' ";
        }

        if (isset($_POST['cabecera']) && $_POST['cabecera']!=''){
            $where = $where . " and aprovisionados.cabecera=".$_POST['cabecera'];
        }

        $result = $util->selectJoin("aprovisionados", $campos,
            " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario  " . $where, $orden, '', ' aprovisionados.num_pon');
    } else {
            // si no es root solo los de este revendedor

        $where="";

        if (isset($_POST['filtro']) && $_POST['filtro']!=''){
            $filtro=$_POST['filtro'];
            $where = $where . " AND clientes.dni LIKE '%".$filtro."%' OR clientes.nombre LIKE '%".$filtro.
                "%' OR clientes.apellidos LIKE '%".$filtro."%' OR  aprovisionados.num_pon LIKE '%".$filtro."%' ";
        }

        if (isset($_POST['cabecera']) && $_POST['cabecera']!=''){
            $where = $where . " and aprovisionados.cabecera=".$_POST['cabecera'];
        }

        $result = $util->selectJoin("aprovisionados", $campos,
        " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", $orden,
        " clientes.user_create in (SELECT id FROM usuarios WHERE revendedor = (select revendedor from usuarios where id = " . $_SESSION['USER_ID'] . ")) " . $where, ' aprovisionados.num_pon');
    }

    $aItems = array();

    while ($row = mysqli_fetch_array($result)) {

        $aItem = array(
            'fecha' => date_format(date_create($row[0]),"d/m/Y H:i:s"),
            'velocidad_up' => $row[3],
            'velocidad_dw' => $row[4],
            'ppoe_usuario' => $row[5],
            'c' => $row[8],
            't' => $row[9],
            'p' => $row[10],
            'caja' => $row[11],
            'puerto' => $row[12],
            'id_internet' => $row[13],
            'serial' => $row[14],
            'num_pon' => $row[16],
            'cli_nombre' => $row[17],
            'cli_tel1' => $row[21],
            'cli_apellidos' => $row[18],
            'prov_id' => $row[25],
            'id_vozip' => $row[26],
            'id_iptv' => $row[27],
            'id_vpn' => $row[28],
            'id_cabecera' => $row[29],
            'int_sino' => $row[30],
            'tv_sino' => $row[31],
            'tel_sino' => $row[32]
        );
        array_push($aItems, $aItem);

    }
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);