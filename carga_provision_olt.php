<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 9:38
 */


/*
    ╔════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Lanza comando a la cabecera para listar todas las ont que estan aprovisionadas          ║
    ║ procesa la salida de texto y crea un array con la informacion obtenida                  ║
    ╚════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/


if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(3);

$telnet = new PHPTelnet();
ini_set('max_execution_time', 200);

$a_ont = array();
$a_descripcion = array();

if(isset($_POST['a']) && $_POST['a']=='provision_olt'){

    if(isset($_POST['olt']) && $_POST['olt']!=''){

        $id_olt = $_POST['olt'];
        $cabeceras = $util->selectWhere('olts', $t_cabeceras, ' id=' . $id_olt);
        $row = mysqli_fetch_array($cabeceras);
        $server = $row['ip'];
        $user = $row['usuario'];
        $pass = $row['clave'];



        $result = $telnet->Connect($server, $user, $pass);
        $result=0;
            if ($result == 0) {
                $telnet->DoCommand('enable', $result);
                $telnet->DoCommand(PHP_EOL, $result);
                $telnet->DoCommand('config', $result);
                $telnet->DoCommand(PHP_EOL, $result);

                $telnet->DoCommand('interface gpon 0/1', $result);
                $telnet->DoCommand('display ont info 0 all', $result);


                $encontradas0 = explode('-----------------------------------------------------------------------------', $result);

                $encontradas1 = explode(PHP_EOL, $encontradas0[2]);


                foreach ($encontradas1 as $valor) {

                    $encontradas2 = explode(" ", $valor);
                    if(count($encontradas2)>5) {
                        $c = substr($encontradas2[2], 0, 1);
                        $valores[0] = $c; // chasis
                        $tp = explode("/", $encontradas2[3]);
                        $valores[1] = $tp[0]; // tarjeta
                        $valores[2] = $tp[1]; // port
                        $valores[3] = $encontradas2[7]; // id
                        $valores[4] = $encontradas2[9]; // pon
                        $valores[5] = $encontradas2[11]; // flag
                        $valores[6] = $encontradas2[17]; // run state
                        $valores[7] = $encontradas2[20]; // config state
                        $valores[8] = $encontradas2[23]; // match state
                        $valores[9] = $encontradas2[24]; // protect
                        array_push($a_ont, $valores);
                    }
                }

                $desc1 = explode(PHP_EOL, $encontradas0[4]);


                foreach ($desc1 as $valor) {
                    $desc2 = explode("   ", $valor);
                    if(count($desc2)>2) {
                        array_push($a_descripcion, $desc2[3]);
                    }
                }
            }

            $telnet->Disconnect();

            $estados_olt = $util->selectDistinct('estado_olts','pon');

            $id_ont_cont = 0;

            foreach ($a_ont as $ont_info){  // recorro las ont encontradas en la olt
                $ont_state=$ont_info[6];
                if (in_array($ont_info[4], $estados_olt)===false) {    // busco el pon de las encontradas dentro de la tabla DB
                    // si no se encuentra la agrego a la tabla
                    $campos = array('id_olt','pon', 'c', 't', 'p', 'control_flag', 'run_state', 'description');
                    $valores = array($id_olt,$ont_info[4],$ont_info[0],$ont_info[1],$ont_info[2],$ont_info[5],$ont_info[6],$a_descripcion[$id_ont_cont]);
                    $util->insertInto('estado_olts', $campos, $valores);
                    $id_ont_cont++;
                } else {    // si se encuentra, actualizo
                    if($ont_info[6]=='offline'){    // si el estado actual es offline ...

                        // busco el estado anterior de esta ont
                        $estado_anterior = $util->selectLast('estado_olts','run_state','pon="'.$ont_info[4].'"');
                        if($estado_anterior=='online') {  // si antes estaba online y ahora offline tengo que avisar
                            enviar_alarma($ont_info);   // paso control a enviar alarma
                            $util->update('estado_olts',array('run_state'),array('offline'),'pon="'.$ont_info[4].'"');
                        }
                    }
                }
                $util->update('estado_olts',array('run_state'),array($ont_state),'pon="'.$ont_info[4].'"');
            }


    } else echo "no pon";

} else echo "no control";

//                    $campos=array('id_olt','fecha','pon','c','t','p','control_flag','run_state','description','cpu','mem','temp','alarma','distancia');

function enviar_alarma($info){
    print_r($info);
}