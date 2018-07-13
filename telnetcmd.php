<?php


include_once("config/util.php");

$telnet = new PHPTelnet();
$util= new util();
check_session(0);

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
if(isset( $_POST['perfil']))
    $perfil = $_POST['perfil'];

$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

$command = $_POST['command'];

if($command=='deleteontprofile'){


    $util->delete("modelos_ont","perfil",$perfil . "' and cabecera = '".$id_olt);
    $result = $telnet->Connect($server, $user, $pass);

    if ($result == 0) {
        $telnet->DoCommand('enable', $result);
        $telnet->DoCommand(PHP_EOL, $result);

        $telnet->DoCommand('config', $result);
        $telnet->DoCommand(PHP_EOL, $result);
        //echo 'interface gpon '.$c."/".$t;

        $telnet->DoCommand('undo table ip index '.$perfil, $result);
        echo 'undo table ip index '.$perfil;
        $telnet->DoCommand(PHP_EOL, $result);

        $telnet->Disconnect();

    }

}

if($command=='deleteolt'){

    $util->delete("modelos_ont","cabecera",$id_olt);
    $util->delete("perfil_internet","id_olt",$id_olt);
    $util->delete("olts","id",$id_olt);
}

if($command=='deletespeedprofile'){

    $util->delete("perfil_internet","perfil_olt",$perfil . "' and id_olt = '".$id_olt);

    $result = $telnet->Connect($server, $user, $pass);

    if ($result == 0) {
        $telnet->DoCommand('enable', $result);
        $telnet->DoCommand(PHP_EOL, $result);

        $telnet->DoCommand('config', $result);
        $telnet->DoCommand(PHP_EOL, $result);

        $telnet->DoCommand('undo traffic table ip index '.$perfil, $result);
        $telnet->DoCommand(PHP_EOL, $result);
        if(strpos($result, 'traffic table is used')>0) {
            $responder = "Error: Esta tabla está en uso y no se puede borrar";
            echo $responder;
        } else echo 1;

        $telnet->Disconnect();

    }

}

if($command=='display_service_profiles'){

    $result = $telnet->Connect($server, $user, $pass);
    $telnet->DoCommand('enable', $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->DoCommand('config', $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->DoCommand("display traffic table ip from-index 0 to-index 1023", $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $encontradas = explode('----------------------------------------------------------------------------', $result);

    print_r($encontradas);

//    foreach ($encontradas as $valor) {
//
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
//
    $telnet->Disconnect();

}

if(isset($_POST['comando']) && $_POST['comando']=='apelo'){

    $result = $telnet->Connect($server, $user, $pass);
    $telnet->DoCommand('enable', $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->DoCommand('config', $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->DoCommand($_POST['command'], $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->Disconnect();

}




//-----------------------------------------------------------------------------


