<?php
// todo: *******************************************************************************
// funcion que busca las ont conectadas a una cabecera y que aun no estan aprovisionadas
// tambien encuentra las ont que aunque hayan estado aprovisionadas se hayan cambiado de puerto
// en este caso apareceran en la busqueda pero a la hora de aprovisionarla dará error porque ya esta aprovisionada
// este tipo de operaciones se realiza enviando un comando por telnet a la cabecera y capturando su respuesta
// en modo texto, se procesa en busca de coincidencias
// todo: *******************************************************************************

include_once("config/util.php");

//define("ESPACIO","\040");

//******************************************
// la clase principal de toda la plataforma es PHPTelne, capaz de establecer
// comunicaciones con las cabeceras por ssh ydevolver una cadena de texto
//******************************************


$telnet = new PHPTelnet();
$util = new util();

// leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
$id_olt = $_POST['olt'];
$cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
$row = mysqli_fetch_array($cabeceras);
$server = $row['ip'];
$user = $row['usuario'];
$pass = $row['clave'];

//echo $server. " ". $user. " ". $pass;

$aItems = array();

$result = $telnet->Connect($server, $user, $pass);

if ($result == 0) {
    $telnet->DoCommand('enable', $result);
    $telnet->DoCommand(PHP_EOL, $result);

    $telnet->DoCommand('config', $result);
    $telnet->DoCommand(PHP_EOL, $result);
    //echo 'interface gpon '.$c."/".$t;

    $telnet->DoCommand('display ont autofind all ' . PHP_EOL .ESPACIO .ESPACIO .PHP_EOL .ESPACIO .ESPACIO .PHP_EOL , $result);


//        echo "1: ". $result;

    $encontradas = explode('----------------------------------------------------------------------------', $result);

    foreach ($encontradas as $valor) {

        if (strpos($valor, 'F/S/P') > 0) {
            $aqui_hay_algo = explode(':', $valor);
            //print_r($aqui_hay_algo);
            $fsp=str_replace("Ont SN","",$aqui_hay_algo[2]);
            $fsp=explode("/",substr($aqui_hay_algo[2],0,8));
            $fsp=str_replace("Ont SN","",$fsp);

            $serietmp=str_replace("Ont SN"," ",$aqui_hay_algo[3]);
            $serietmp=str_replace("Password"," ",$serietmp);
            $serietmp=trim($serietmp);
            $w=trim($serietmp);
            $serie= $util->selectWhere2('etiquetas.series',array('pathnumber'),'etiquetas.series.PON like "'.$w.'"');
            $series="";
            $series=$serie[0];
            if($series==null)
                $series="";

            $aItem = array(
                'f' => $fsp,
                's' => substr($serietmp, 0, 16),
                'serie'=>$series
            );
            array_push($aItems, $aItem);

        }
    }
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($aItems);

$telnet->Disconnect();



//-----------------------------------------------------------------------------
?>




