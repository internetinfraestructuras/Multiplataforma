<?php

require_once('/var/www/html/fibra/config/util.php');
    $util = new util();

    $telnet = new PHPTelnet();
    ini_set('max_execution_time', 60000);
    ini_set('memory_limit', 1024*1024);
    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
    header('Content-Type: text/html; charset=utf-8');

    $cabeceras = $util->selectWhere('olts', $t_cabeceras);

    while ($row = mysqli_fetch_array($cabeceras)) {

        $id_olt = $row['id'];
        $server = $row['ip'];
        $user = $row['usuario'];
        $pass = $row['clave'];

        $result = 0;
        $result = $telnet->Connect($server, $user, $pass);

        if ($result == 0) {
            $telnet->DoCommand('enable', $void);
            $telnet->DoCommand(PHP_EOL, $void);

            $telnet->DoCommand('config', $void);
            $telnet->DoCommand(PHP_EOL, $void);


        }

        $lista_onts = $util->selectWhere('estado_olts',  array('c','t','p','id_ont','id'),'id_olt='.$id_olt);

        $c=1;
        while ($row2 = mysqli_fetch_array($lista_onts)) {

            $c = intval($row2[0]);
            $t = intval($row2[1]);
            $p = intval($row2[2]);
            $idenont = intval($row2[3]);
            $id_row = intval($row2[4]);

            $respuesta_olt='';
            $telnet->DoCommand('cls ' . PHP_EOL, $void);
            $telnet->DoCommand('display ont wan-info ' . $c ."/" .$t ." ". $p . " " . $idenont . PHP_EOL . " q", $respuesta_olt);
            $rows = explode("---------------------------------------------------------------------", $respuesta_olt);

            if(isset($rows[2])){
                $rows = explode(PHP_EOL, $rows[2]);
                $i = explode(':', $rows[7]);
                $ip = $i[1];
                $m = explode(':', $rows[14]);
                $mac = $m[1];

                $ssid ="";
                $telnet->DoCommand('interface gpon ' . $c . "/" . $t, $void);
                $telnet->DoCommand(PHP_EOL, $void);

                $telnet->DoCommand('cls ' . PHP_EOL, $void);

                $telnet->DoCommand('display ont optical-info ' . $p . " " . $idenont . PHP_EOL . ESPACIO, $respuesta_olt);

                $aItems = array();
                if (strpos($respuesta_olt, 'does not exist') == false) {

                    $rows = explode("-----------------------------------------------------------------------------", $respuesta_olt);
                    if(isset($rows[1])) {
                        $rows = explode(PHP_EOL, $rows[1]);

                        if(strpos($rows[1],'ONU NNI port')>0){
                            $b = explode(':', $rows[12]);
                            $rx = $b[1];
                            $c = explode(':', $rows[15]);
                            $tx = $c[1];
                            $d = explode(':', $rows[21]);
                            $temp = $d[1];
                            $d = explode(':', $rows[27]);
                            $rx_olt = $d[1];
                            $f = explode(':', $rows[24]);
                            $volt = $f[1];
                        } else {
                            $b = explode(':', $rows[7]);
                            $rx = $b[1];
                            $c = explode(':', $rows[10]);
                            $tx = $c[1];
                            $d = explode(':', $rows[16]);
                            $temp = $d[1];
                            $e = explode(':', $rows[22]);
                            $rx_olt = $e[1];
                            $f = explode(':', $rows[19]);
                            $volt = $f[1];
                        }
                    }

                    $dt = new DateTime();
                    $fecha = $dt->format('Y-m-d H:i:s');
                    $util->update('estado_olts', array('temp', 'rx', 'tx', 'rx_olt', 'volt', 'fecha','ip','ssid','mac'), array($temp, $rx, $tx, $rx_olt, $volt,$fecha,$ip,$ssid,$mac), 'id=' . $id_row);
                }

                $telnet->DoCommand('quit '  , $void);
            }


        }
        $telnet->Disconnect();
    }
