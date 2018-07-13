<?php


    include_once("config/util.php");

    $telnet = new PHPTelnet();
    $util= new util();
    error_reporting(1);

    ini_set('max_execution_time', 60);
    ini_set('memory_limit', 1024*1024);

    mb_internal_encoding("UTF-8");

//    mb_http_output( "UTF-8" );


    // leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
    $id_olt = $_POST['olt'];
    $pon = $_POST['pon'];

    $cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
    $row = mysqli_fetch_array($cabeceras);
    $server = $row['ip'];
    $user = $row['usuario'];
    $pass = $row['clave'];
    $conexion=0;

    $comando='display ont info by-sn ' . $pon .PHP_EOL . ESPACIO. "q";

    $conexion = $telnet->Connect($server, $user, $pass);
    if ($conexion == 0) {
        $telnet->DoCommand('enable', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $telnet->DoCommand('config', $respuesta_olt);
        $telnet->DoCommand(PHP_EOL, $respuesta_olt);

        $telnet->DoCommand($comando , $respuesta_olt);

        $rows = explode("-----------------------------------------------------------------------------",substr($respuesta_olt,0,200));


        $rows2 = explode(PHP_EOL, $rows[1]);
        //print_r($rows2);
        $ids = explode(':',$rows2[2]);

        $ctps = explode(':', $rows2[1]);
        $ctp=explode('/', $ctps[1]);
        $c = $ctp[0];
        $t = $ctp[1];
        $p = $ctp[2];
        $idenont = intval($ids[1]);

        $cmd = 'display ont wan-info ' . $c ."/" .$t ." ". $p . " " . $idenont;

        $c=0;
        while ($ip!='' && $mac!='' || $c<4){
            $c++;
            $respuesta_olt="";

            $telnet->DoCommand($cmd . PHP_EOL .ESPACIO ."q", $respuesta_olt);

            //echo $respuesta_olt;
            $rows = explode("---------------------------------------------------------------------", $respuesta_olt);
            if(isset($rows[2])) {
                $rows2 = explode(PHP_EOL, $rows[2]);
                $i = explode(':', $rows2[7]);
                $ip = $i[1];
                $m = explode(':', $rows2[14]);
                $mac = $m[1];
            }
            if($ip!='' && $mac!='')
                break;
        }

        $aItems=array();

        $aItem = array(
            'ip' => $ip,
            'mac' => $mac,
        );
        array_push($aItems, $aItem);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);
    }


//-----------------------------------------------------------------------------

//

