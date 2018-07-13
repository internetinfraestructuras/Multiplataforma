<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


require_once('/var/www/html/fibra/config/util.php');
//require_once('../config/util.php');
$util = new util();
error_reporting(1);
$telnet = new PHPTelnet();
ini_set('max_execution_time', 600);
ini_set('memory_limit', 1024 * 1024);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
header('Content-Type: text/html; charset=utf-8');

$dt = new DateTime();
$fecha = $dt->format('Y-m-d H:i:s');

$util->consulta('truncate table estado_olts');

$campos = array(
    'aprovisionados.id_en_olt as idolt, aprovisionados.c as c ,aprovisionados.t as t,aprovisionados.num_pon as numpon,
            aprovisionados.p as p, clientes.nombre as nombre, clientes.apellidos as apellidos,
            aprovisionados.cabecera as cabecera');


$resultsql = $util->selectJoin("aprovisionados", $campos,
    " left join clientes on aprovisionados.id_cliente=clientes.id left join usuarios on usuarios.id = aprovisionados.id_usuario ", 'cabecera,c,t,p');


$num_cabecera = 0;
$parar=1;
//while ($rows = mysqli_fetch_array($resultsql) and $parar<6) {
while ($rows = mysqli_fetch_array($resultsql)) {

    $chas = $rows['c'];
    $tarjetas = $rows['t'];
    $pons = $rows['p'];
    $idenolt = $rows['idolt'];
    $pon = $rows['numpon'];

    if (intval($rows['cabecera']) != $num_cabecera) {
        $telnet->Disconnect();

        $cabeceras = $util->selectWhere('olts', $t_cabeceras, 'id=' . $rows['cabecera']);

        $row = mysqli_fetch_array($cabeceras);

        $id_olt = $row['id'];
        $server = $row['ip'];
        $user = $row['usuario'];
        $pass = $row['clave'];
        $ch = $row['chasis'];
        $ta = $row['tarjeta'];
        $po = $row['pon'];

        $result1 = $telnet->Connect($server, $user, $pass);

        $telnet->DoCommand('enable', $result1);
        $telnet->DoCommand(PHP_EOL, $result1);
//        $telnet->DoCommand('config', $result1);
//        $telnet->DoCommand(PHP_EOL, $result1);


        $num_cabecera = $rows['cabecera'];
    }

    $telnet->DoCommand('cls' . PHP_EOL, $void);
    $telnet->DoCommand('cls' . PHP_EOL, $void);
    $result = null;
    $telnet->DoCommand('display ont info ' . $chas . ' ' . $tarjetas . ' ' . $pons . '  ' . $idenolt . PHP_EOL . "q", $result);
//    sleep(1);
//    $telnet->DoCommand(PHP_EOL . "q", $result);
//    sleep(1);
//    $telnet->DoCommand("q", $result);


    if (intval(strpos($result, 'ONT does not exist')) == 0) {
//            $telnet->DoCommand('cls' . PHP_EOL, $void);

        unset($encontradas0);
        $encontradas0 = explode('-----------------------------------------------------------------------------', $result);



        if (count($encontradas0) > 0) {
            if (isset($encontradas0[1])) {

                unset($encontradas1);
                $encontradas1 = explode(PHP_EOL, $encontradas0[1]);
                print "<pre>";
                var_dump($encontradas1);
                print "</pre>";

                $estado = explode(":", $encontradas1[4]);
                $estado = trim($estado[1]);
                $de = $rows['apellidos'] . " " . $rows['nombre'];

                $estado1 = explode(":", $encontradas1[20]);
                $causa = trim($estado1[1]);

//                echo $encontradas1[21];

                $estado2 = explode(":", $encontradas1[21]);
                $fechacausa = $estado2[1].":".$estado2[2].":".$estado2[3];

                $campos = array('id_olt', 'pon', 'c', 't', 'p', 'description', 'id_ont', 'run_state','lastcause','datecause');
                $valores = array($num_cabecera, $pon, $chas, $tarjetas, $pons, $de, $idenolt, $estado,$causa,$fechacausa);
                $util->consulta("DELETE FROM estado_olts WHERE pon ='".$pon."'");
                $util->insertInto('estado_olts', $campos, $valores, false);
                $util->update('estados_olt',$campos,$valores,"pon='".$pon."'");
            }

        }

    }
}

$telnet->Disconnect();
function enviar_alarma($info)
{
    print_r($info);
}


