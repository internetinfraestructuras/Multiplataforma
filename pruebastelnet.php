<?php


//include_once("config/util.php");
//
//$telnet = new PHPTelnet();
//$util = new util();
//
//// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
//$id_olt = 4;
//$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
//$row = mysqli_fetch_array($cabeceras);
//$server = $row['ip'];
//$user = $row['usuario'];
//$pass = $row['clave'];
//
//echo $server;
//$aItems = array();
//
//$result = $telnet->Connect($server, $user, $pass);
//
//if ($result == 0) {
//    $telnet->DoCommand('enable', $result);
//    $telnet->DoCommand(PHP_EOL, $result);
//
//    $telnet->DoCommand('config', $result);
//    $telnet->DoCommand(PHP_EOL, $result);
//
//    $telnet->DoCommand("display traffic table ip from-index 0 to-index 1023", $result);
//    $telnet->DoCommand(PHP_EOL, $result);
//
//    echo $result;
    //$encontradas = explode('----------------------------------------------------------------------------', $result);
//print_r($encontradas);

//    foreach ($encontradas as $valor) {

//        if (strpos($valor, 'F/S/P') > 0) {
//            $aqui_hay_algo = explode(':', $valor);
//            //print_r($aqui_hay_algo);
//            $fsp=str_replace("Ont SN","",$aqui_hay_algo[2]);
//            $fsp=explode("/",$aqui_hay_algo[2]);
//            $fsp=str_replace("Ont SN","",$fsp);
//
//            $serietmp=str_replace("Ont SN"," ",$aqui_hay_algo[3]);
//            $serietmp=str_replace("Password"," ",$serietmp);
//            $serietmp=str_replace(" ","",$serietmp);
//            $aItem = array(
//                'f' => $fsp,
//                's' => $serietmp,
//            );
//            array_push($aItems, $aItem);
//        }
//    }
//}

//header('Content-type: application/json; charset=utf-8');
//echo json_encode($aItems);

//$telnet->Disconnect();


//use PEAR2\Net\RouterOS;
//require_once 'PEAR2/Autoload.php';
//
//header('Content-Type: text/plain');
//
//try {
//    $util = new RouterOS\Util($client = new RouterOS\Client('10.211.2.1', 'ruben', 'ruben*'));
//    echo "conectado";
//    foreach ($util->setMenu('/log')->getAll() as $entry) {
//        echo "---";
//        echo $entry('time') . ' ' . $entry('topics') . ' ' . $entry('message') . "\n";
//    }
//} catch (Exception $e) {
//    echo 'Unable to connect to RouterOS.';
//}

require('clases/routeros_api.class.php');
$API = new RouterosAPI();

if ($API->connect( '10.211.2.1', 'ruben',  'ruben*')) {


//    if($form_direccionip!="")
//    {
//        $API->comm("/ppp/secret/add", array(
//            "name"     => "$ppoe_user",
//            "password" => "$ppoe_pass",
//            "remote-address" => "$ExternalIPAddress",
//            "comment"  => "$cliente",
//            "service"  => "ppoe-vlan-100",
//            "profile"  => "$form_profile"
//        ));
//    }
//    else
//    {
        echo $API->comm("/ppp/secret/add", array(
            "name"     => "Prueba1",
            "password" => "Prueba1",
            "comment"  => "Comentario",
            "service"  => "any",
            "profile"  => "ppoe-vlan-100"     //
        ));
//
//    }

    $API->disconnect();
}

?>




