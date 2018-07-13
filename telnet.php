<?php
/*
PHPTelnet 1.1.1
by Antone Roundy
adapted from code found on the PHP website
public domain
*/

include_once("config/util.php");


$telnet = new PHPTelnet();
$util= new util();

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$c =  $_POST['c'];
$t =  $_POST['t'];
$p =  $_POST['p'];
$caja =  $_POST['caja'];
$puerto =  $_POST['puerto'];
$lat =  $_POST['lat'];
$lon =  $_POST['lon'];
$gpon=$c."/".$t."/".$p;


setcookie('c', $c, time() + (86400 * 30), "/");
setcookie('t', $t, time() + (86400 * 30), "/");
setcookie('p', $p, time() + (86400 * 30), "/");
setcookie('caja', $caja, time() + (86400 * 30), "/");
setcookie('puerto', $puerto, time() + (86400 * 30), "/");
setcookie('cabecera', $id_olt, time() + (86400 * 30), "/");


// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1


$command = $_POST['command'];
$serial = $_POST['serial'];

if($command=='alta'){
    $result = $util->selectWhere('aprovisionados',  array('id'), 'serial='.$serial);
    $row = mysqli_fetch_array($result);
    $r=intval($row[0]);

    if($r>0){
        echo "El número de serie de la ONT ya está registrado";
        return;
    }

}

$lineprofile = $_POST['lineprofile'];
$serverprofile = $_POST['serverprofile'];
$descripcion = str_replace(" ", "_",$_POST['descrp'])."_".$c."_".$t."_".$p."_".$caja."_".$puerto;
$descripcion=strtoupper($descripcion);

$lineprofile='11';

$idcliente = $_POST['idcliente'];
$servicio = $_POST['servicio'];
$up = $_POST['up'];
$dw = $_POST['dw'];

//datos del tipo de acceso
$gestionada = isset( $_POST['gestionada'])? $_POST['gestionada'] : "";
$tipoip = isset( $_POST['tipoip'])? $_POST['tipoip'] : "";
$asignada = isset( $_POST['asignada'])? $_POST['asignada'] : "";
$ppoe_usuario = isset( $_POST['usuario_ppoe'])? $_POST['usuario_ppoe'] : "";
$ppoe_passw = isset( $_POST['clave_ppoe'])? $_POST['clave_ppoe'] : "";

$gemport = substr($servicio,0,1);

$ont_id = intval($util->selectWhere('aprovisionados',  'id_en_olt', "cabecera=". $id_olt));
var_dump($ont_id);
return;
$id_internet = intval($util->selectMax('aprovisionados',  'id_internet', "cabecera=". $id_olt))+1;
$id_voip = $util->selectMax('aprovisionados',  'id_voip', "cabecera=". $id_olt);
$id_iptv = $util->selectMax('aprovisionados',  'id_iptv', "cabecera=". $id_olt);



$valores=array($idcliente, $_SESSION['USER_ID'], date('Y-m-d H:i:s'), $lat, $lon,'1','0','0',$serverprofile,
    $gestionada,$tipoip, $asignada, $descripcion, $up, $dw, $ppoe_usuario,
    $ppoe_passw, $ont_id, $c, $t, $p, $serial, $caja, $puerto,  $id_internet, $id_voip, $id_iptv, $id_olt);



$lastid = $util->insertInto("aprovisionados", $t_aprovisionados, $valores);


//service-port 10 vlan 100 gpon 0/0/15 ont 10 gemport 1 multi-service user-vlan 100 tag-transform
// translate inbound traffic-table index 600 outbound traffic-table index 600

if($command=='alta'){
    $comando="ont add ".$p." ".$ont_id." sn-auth ".$serial." omci ont-lineprofile-id ".$lineprofile." ont-srvprofile-name ".$serverprofile." desc ".$descripcion;
    $comando1="service-port ".$id_internet." vlan ".$servicio." gpon ".$gpon." ont ".$ont_id." gemport ".$gemport.
        " multi-service user-vlan ".$servicio." tag-transform translate inbound traffic-table index ".$up." outbound traffic-table index ".$dw;
}

$result = $telnet->Connect($server, $user, $pass);


if ($result == 0) {
    $telnet->DoCommand('enable', $result);
    $telnet->DoCommand( PHP_EOL, $result);
    $telnet->DoCommand('config', $result);
    $telnet->DoCommand( PHP_EOL, $result);
    //echo 'interface gpon '.$c."/".$t;

    $telnet->DoCommand('interface gpon '.$c."/".$t, $result);
    $telnet->DoCommand( PHP_EOL, $result);
//echo $comando;

    $telnet->DoCommand($comando, $result);
    $telnet->DoCommand( PHP_EOL, $result);
    $respuesta1=strpos($result,'success: 1');

    if($comando1!="") {
        $telnet->DoCommand("quit", $result);
        $telnet->DoCommand( PHP_EOL, $result);
        $telnet->DoCommand($comando1, $result);
        $telnet->DoCommand(PHP_EOL, $result);
        $respuesta2=strpos($result,'[1');
    }

    $telnet->Disconnect();

    if($respuesta1!==false && $respuesta2!==false)
        echo "Operación completada correctamente";
    else {
        $util->delete("aprovisionados","id" ,$lastid);
        echo "ERROR. Por favor revisa los datos y reitentalo";
    }
}

//header('Content-type: application/json; charset=utf-8');
//echo json_encode($result);
//-----------------------------------------------------------------------------
?>




