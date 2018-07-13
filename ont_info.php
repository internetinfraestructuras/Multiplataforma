<?php


    include_once("config/util.php");

    $telnet = new PHPTelnet();
    $util= new util();
    error_reporting(2);

    ini_set('max_execution_time', 1000);
    ini_set('memory_limit', 1024*1024);

    header('Content-Type: text/html; charset=utf-8');

    $id_ont = $_POST['id'];
    $respuesta_olt = $util->selectWhere('aprovisionados',  array('c','t','p','id_en_olt','cabecera'), ' id="'.$id_ont.'";');
    $row = mysqli_fetch_array($respuesta_olt);
    $c=intval($row[0]);
    $t=intval($row[1]);
    $p=intval($row[2]);
    $idenolt=intval($row[3]);
    $id_olt=intval($row[4]);

    // leo informacion de la olt seleccionada para obtener la ip, usuario, clave y demas.
    $cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
    $row = mysqli_fetch_array($cabeceras);
    $server = $row['ip'];
    $user = $row['usuario'];
    $pass = $row['clave'];

    $respuesta_olt=0;
    $respuesta_olt = $telnet->Connect($server, $user, $pass);

    if ($respuesta_olt == 0) {
        $telnet->DoCommand('enable', $void);
        $telnet->DoCommand(PHP_EOL, $void);

        $telnet->DoCommand('config', $void);
        $telnet->DoCommand(PHP_EOL, $void);

        $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
        $telnet->DoCommand(PHP_EOL, $void);
        $telnet->DoCommand('cls ' .PHP_EOL , $void );

        $telnet->DoCommand('display ont optical-info ' .$p ." ". $idenolt .PHP_EOL. ESPACIO , $respuesta_olt );

        //$telnet->DoCommand(ESPACIO, $respuesta_olt);

        $responder = -1;
        $err_num=-1;

        $aItems = array();
        if(strpos($respuesta_olt, 'does not exist')!== false) {
            $responder = "Error: Ont No Encontrada";
            $err_num = 1;
            $aItem = array(
                'error' => 1
            );
            array_push($aItems, $aItem);

        } else {
            $row = explode("-----------------------------------------------------------------------------", $respuesta_olt);
//            var_dump($row);
            $row = explode(PHP_EOL, $row[1]);
//            var_dump($row);
            $a = explode(':', $row[6]);
            $vendor = $a[1];
//            $b = explode(':', $row[7]);
//            $rx = $b[1];
//            $c = explode(':', $row[10]);
//            $tx = $c[1];
//            $d = explode(':', $row[16]);
//            $temp = $d[1];
//            $e = explode(':', $row[22]);
//            $rx_olt = $e[1];
//            $f = explode(':', $row[19]);
//            $volt = $f[1];
            if(strpos($row[1],'ONU NNI port')>0){
                $b = explode(':', $row[12]);
                $rx = $b[1];
                $c = explode(':', $row[15]);
                $tx = $c[1];
                $d = explode(':', $row[21]);
                $temp = $d[1];
                $d = explode(':', $row[27]);
                $rx_olt = $d[1];
                $f = explode(':', $row[24]);
                $volt = $f[1];
            } else {
                $b = explode(':', $row[7]);
                $rx = $b[1];
                $c = explode(':', $row[10]);
                $tx = $c[1];
                $d = explode(':', $row[16]);
                $temp = $d[1];
                $e = explode(':', $row[22]);
                $rx_olt = $e[1];
                $f = explode(':', $row[19]);
                $volt = $f[1];
            }


            $aItem = array(
                'error' => 0,
                'rx' => $rx,
                'tx' => $tx,
                'rx_olt' => str_replace('out of range','rango: ',$rx_olt),
                'temp' => $temp,
                'volt' => $volt,
                'marca' => $vendor
            );
            array_push($aItems, $aItem);

        }

        $telnet->Disconnect();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);
    }

    //-----------------------------------------------------------------------------

//

