<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */



require_once('/var/www/html/fibra/config/util.php');
//    require_once('../config/util.php');
$util = new util();
error_reporting(0);
$telnet = new PHPTelnet();
ini_set('max_execution_time', 6000);
ini_set('memory_limit', 1024*1024);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
header('Content-Type: text/html; charset=utf-8');

$dt = new DateTime();
$fecha = $dt->format('Y-m-d H:i:s');

$util->consulta('DELETE FROM estado_olts');

$cabeceras = $util->selectWhere('olts', $t_cabeceras);

while ($row = mysqli_fetch_array($cabeceras)) {

    $id_olt =$row['id'];
    $server = $row['ip'];
    $user = $row['usuario'];
    $pass = $row['clave'];
    $ch = $row['chasis'];
    $ta = $row['tarjeta'];
    $po = $row['pon'];


    $result = $telnet->Connect($server, $user, $pass);

    if ($result == 0) {
        $telnet->DoCommand('enable', $result);
        $telnet->DoCommand(PHP_EOL, $result);
        $telnet->DoCommand('config', $result);
        $telnet->DoCommand(PHP_EOL, $result);

        for ($chas = 0; $chas < intval($ch); $chas++) {
            for ($tarjetas = 0; $tarjetas < intval($ta); $tarjetas++) {
                for ($pons = 0; $pons < intval($po); $pons++) {

                    $telnet->DoCommand('cls'.PHP_EOL, $void);
                    $telnet->DoCommand('cls'.PHP_EOL, $void);
                    $result=null;
                    $telnet->DoCommand('display ont info ' . $chas . ' ' . $tarjetas . ' ' . $pons . ' all' .PHP_EOL , $result);

                    if (intval(strpos($result,'There is no ONT available')) == 0) {
                        $telnet->DoCommand('cls'.PHP_EOL, $void);

                        unset($encontradas0);
                        $encontradas0 = explode('-----------------------------------------------------------------------------', $result);
//                              print "<pre>";
//                              print_r($encontradas0);
//                              print "</pre>";

                        if(count($encontradas0)>1) {
                            if (isset($encontradas0[2])) {

                                unset($encontradas1);
                                $encontradas1 = explode(PHP_EOL, $encontradas0[2]);

                                unset($a_ont);
                                $a_ont = array();

                                $primera_linea = true;

                                unset($valores);
                                foreach ($encontradas1 as $valor) {

                                    $valores = array();

                                    $valores[0] = substr($valor, 0, 3); // chasis
                                    $valores[1] = substr($valor, 4, 2); // tarjeta
                                    $valores[2] = substr($valor, 7, 2); // port
                                    $valores[3] = substr($valor, 9, 4);; // id
                                    $valores[4] = substr($valor, 15, 16); // pon
                                    $valores[5] = substr($valor, 33, 7);; // flag
                                    $valores[6] = substr($valor, 45, 7); // run state
                                    array_push($a_ont, $valores);
                                }

//                                $desc1 = null;
//                                $desc1 = explode(PHP_EOL, $encontradas0[4]);

                                unset($a_descripcion);
                                $a_descripcion = array();


                                if (isset($encontradas0[4])) {
                                    $clientes = explode(PHP_EOL, $encontradas0[4]);
//                                        print "<pre>";
//                                        var_dump($clientes);
//                                        print "</pre>";
                                    foreach ($clientes as $valor) {
                                        $descripcion = null;
                                        $descripcion = substr($valor, 19, 50);
                                        array_push($a_descripcion, $descripcion);
                                    }

                                }

                                unset( $estados_olt);
                                //borro la tabla

                                //leo toda la tabla
                                $estados_olt = $util->selectDistinct('estado_olts', 'pon');

                                if(count($a_descripcion)>1)
                                    array_shift($a_descripcion);

                                foreach ($a_ont as $ont_info) {  // recorro las ont encontradas en la olt

                                    $ont_state = str_replace(" ", "",$ont_info[6]);
                                    if (in_array($ont_info[4], $estados_olt) === false) {    // busco el pon de las encontradas dentro de la tabla DB
                                        // si no se encuentra la agrego a la tabla
                                        $de = $a_descripcion[0];
                                        if(str_replace(' ','',$de)==''){
                                            $result=$util->selectWhere('clientes',array('nombre','apellidos','tel1'),' id = (select id_cliente from aprovisionados where num_pon="'.$ont_info[4].'")');
                                            print_r($result);
                                            $row = mysqli_fetch_array($result);
                                            $de = $row[1] . " " . $row[0]. " - " . $row[2];
                                        }
                                        if ($ont_info[4] != null) {
//                                                if($a_descripcion[0]==''){
//                                                    $r = $util->selectWhere('aprovisionados',array('descripcion','num_pon='.$ont_info[4]));
//                                                    $r1 = mysqli_fetch_array($r);
//                                                    $de=$r1[0];
//                                                }
                                            $campos = array('id_olt', 'pon', 'c', 't', 'p', 'control_flag', 'run_state', 'description', 'id_ont');
                                            $valores = array($id_olt, $ont_info[4], $ont_info[0], $ont_info[1], $ont_info[2], str_replace(" ", "",$ont_info[5]), $ont_state, $de, $ont_info[3]);
                                            $util->insertInto('estado_olts', $campos, $valores, false);
                                            array_shift($a_descripcion);
                                        }
                                    } else {    // si se encuentra, actualizo

                                        $de=str_replace(' ','',$de);
                                        $de=str_replace('-','',$de);
                                        if($de!="")
                                            $util->update('estado_olts', array('fecha','run_state','description'), array($fecha, $ont_state,  $de), 'pon="' . $ont_info[4] . '"', false);
                                        else
                                            $util->update('estado_olts', array('fecha','run_state'), array($fecha, $ont_state), 'pon="' . $ont_info[4] . '"', false);

                                        // busco el estado anterior de esta ont
                                        /* $estado_anterior = $util->selectLast('estado_olts', 'run_state', 'pon="' . $ont_info[4] . '"', false);
                                         $descri_anterior = $util->selectLast('estado_olts', 'description', 'pon="' . $ont_info[4] . '"', false);

                                         if ($estado_anterior == 'online') {  // si antes estaba online y ahora offline tengo que avisar
                                             enviar_alarma($ont_info);   // paso control a enviar alarma
                                             $util->update('estado_olts', array('run_state','fecha'), array('offline',$fecha), 'pon="' . $ont_info[4] . '"', false);
                                         }
                                         if ($descri_anterior == '') {  // si antes estaba online y ahora offline tengo que avisar
                                             $util->update('estado_olts', array('description','fecha'), array($a_descripcion[0],$fecha), 'pon="' . $ont_info[4] . '"', false);
                                         }*/
                                    }

//                                        $util->update('estado_olts', array('run_state'), array($ont_state), 'pon="' . $ont_info[4] . '"', false);
                                }
                            }
                        }
                    }

                }
            }
        }

    }

    $telnet->Disconnect();

}

//        $campos=array('id_olt','fecha','pon','c','t','p','control_flag','run_state','description','cpu','mem','temp','alarma','distancia');

function enviar_alarma($info){
    print_r($info);
}
