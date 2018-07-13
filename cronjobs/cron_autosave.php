<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


    require_once('/var/www/html/fibra/config/util.php');
    $util = new util();

    $telnet = new PHPTelnet();
    ini_set('max_execution_time', 300);

    $cabeceras = $util->selectWhere('olts', $t_cabeceras);

    while ($row = mysqli_fetch_array($cabeceras)) {
        print_r($row);
        $id_olt =$row['id'];
        $server = $row['ip'];
        $user = $row['usuario'];
        $pass = $row['clave'];

        $result = $telnet->Connect($server, $user, $pass);
        $result = 0;

        if ($result == 0) {
            $telnet->DoCommand('enable', $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $telnet->DoCommand('config', $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $telnet->DoCommand('save', $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $telnet->DoCommand(PHP_EOL, $result);
            $telnet->DoCommand(PHP_EOL, $result);
        }

        echo $util->consulta("insert into informe_cron (actuacion) values ('Se ha autosalvado la cabecera: ". $user."')");
        $telnet->Disconnect();
    }
