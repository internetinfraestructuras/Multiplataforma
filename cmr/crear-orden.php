<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */

require_once('util.php');
require_once('def_tablas.php');
$util = new util();

$id=$_POST['reseller'];
$token=$_POST['token'];
if(!$util->check_token($id,$token)) die("Error de token");

//check_session_cmr(3);
date_default_timezone_set('Etc/UTC');



    // todo: --------------------------------------------
	// CREAR UNA ORDEN DE TRABAJO
    // url: ftth.internetinfraestructuras.es/cmr/crear-orden.php
    // espera:
                /*
                 *  $_POST['reseller'] = id reseller
                    $_POST['token']   =  obtenido en login
                 *  $_POST['idcliente'];        id de la tabla clientes
                 *  $_POST['prioridad'];        entero 1 al que quieras, lo ordenamos por peso: 1 mayor prioridad
                 *  $_POST['fecha_limite'];     fecha limite para la instalacion (yyyy-mm-dd)
                 *  $_POST['internet'];         0 / 1   1= activar
                 *  $_POST['tv'];               0 / 1   1= activar
                 *  $_POST['voz'];              0 / 1   1= activar
                 *  $_POST['up'];               string (10,20,30,40,50...)
                 *  $_POST['down'];             string (10,20,30,40,50...)
                 *  $_POST['cabecera'];         Id de la cabecera donde se ha de realizar el trabajo
                *
                * Devuelve:
                *       json:
                 *
                        'result => '',
                        'id' => ,
                        'token' =>

                        result puede ser:
                        OK          guardado correctamente
                        error1      velocidad up desconocida
                        error2      velocidad down desconocida
                        error3      id cliente no encontrado
                        error4      error desconocido

                        id      en caso de guardarse devuelve el id de la orden
                        token   devuelve el mismo token recibido para su comprobacion


               PRUEBA POSTMAN
                [
                    {
                        "result": "OK",
                        "id": 6,
                        "token": "fb3060e5479501d9b54094c38f5c864ac78c63420478593d52228958d63af158"
                    }
                ]

*/
    $aItems = array();
    $id = $util->cleanstring($_POST['idcliente']);
    $idcli= $util->selectWhere2('clientes',array('id'), " id='".$id."'");

    if(intval($idcli[0])==0){
        $aItem = array(
            'result' => 'error3',
            'id' => 0,
            'token' => $token
        );
        array_push($aItems, $aItem);
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);
        return;
    }



        $a = $util->cleanstring($_POST['reseller']);
        $b = $util->cleanstring($_POST['idcliente']);
        $c = $util->cleanstring($_POST['prioridad']);
        $d = $util->cleanstring($_POST['fecha_limite']);
        $e = $util->cleanstring($_POST['internet']);
        $f = $util->cleanstring($_POST['tv']);
        $g = $util->cleanstring($_POST['voz']);
        $h = $util->cleanstring($_POST['up']);
        $i = $util->cleanstring($_POST['down']);
        $j = $util->cleanstring($_POST['cabecera']);



        $campos=array('id_revendedor','id_cliente','prioridad','fecha_limite','internet','tv','voz','up','down','cabecera');
        $values = array( $a,$b,$c,$d,$e,$f,$g,$h,$i,$j);

        // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

        $result = $util->insertInto('ordenes', $campos, $values);
        $util->log('La Api:'.$_POST['reseller'].' ha creado la orden de trabajo con el id:'.$result);

        if(intval($result)>0){
            $aItem = array(
                'result' => "OK",
                'id' => $result,
                'token' => $token
            );
            array_push($aItems, $aItem);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($aItems);
            return;
        } else{
            $aItem = array(
                'result' => 'error4',
                'id' => 0,
                'token' => $token
            );
            array_push($aItems, $aItem);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($aItems);
            return;
        }

?>