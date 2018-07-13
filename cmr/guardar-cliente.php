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
	// AGREGAR CLIENTES A LA BD
    // url: ftth.internetinfraestructuras.es/cmr/guardar-cliente.php
    // espera:
    //          $_POST['action'] =  'addcli'            (para agregar uno nuevo)
    //          $_POST['oper'] =  'edit'                (para editar)
    //          $_POST['id'] =  'XX'                    (id cliente a editar)
                /*
                 *  $_POST['reseller'] = id reseller
                 *  $_POST['nombre'];
                    $_POST['apellidos'];
                    $_POST['dni'];
                    $_POST['dir'];
                    $_POST['cp'];
                    $_POST['region'];               entero id tabla regiones
                    $_POST['provincia'];            entero id tabla provincias
                    $_POST['localidad'];            entero id tabla localidades
                    $_POST['email'];
                    $_POST['tel1'];
                    $_POST['tel2'];
                    $_POST['notas'];
                    $_POST['alta'] = fecha de alta
                    $_POST['token']   =  obtenido en login

                *
                * Devuelve:
                *       json:
                 *
                        'result => '',
                        'id' => ,
                        'token' =>

                        result puede ser:
                        OK          guardado correctamente
                        error1     dni duplicado
                        error2     email duplicado
                        error3     faltan campos obligatorios
                        error4      error desconocido

                        id      en caso de guardarse devuelve el id del cliente

                        token   devuelve el mismo token recibido para su comprobacion


*/
    $aItems = array();

    if(
    (
        !isset($_POST['dni']) || !isset($_POST['email']) || !isset($_POST['nombre'])
    ) ||
    (
        $_POST['dni']=='' || $_POST['email']=="" || $_POST['nombre']=="")
    ){
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

    if(isset($_POST['action']) && $_POST['action'] == 'addcli') {

        $nombre = $util->cleanstring($_POST['nombre']);
        $apellidos = $util->cleanstring($_POST['apellidos']);
        $dni = $util->cleanstring($_POST['dni']);
        $dir = $util->cleanstring($_POST['dir']);
        $cp = $util->cleanstring($_POST['cp']);
        $region = $util->cleanstring($_POST['region']);
        $provincia = $util->cleanstring($_POST['provincia']);
        $localidad = $util->cleanstring($_POST['localidad']);
        $email = $util->cleanstring($_POST['email']);
        $tel1 = $util->cleanstring($_POST['tel1']);
        $tel2 = $util->cleanstring($_POST['tel2']);
        $notas = $util->cleanstring($_POST['notas']);
        $alta = $_POST['alta'];
        $reseller = $util->cleanstring($_POST['reseller']);



        $dniddbb= $util->selectWhere2('clientes',array('id'), " dni='".$dni."'");
        if(intval($dniddbb[0])>0){
            $aItem = array(
                'result' => 'error1',
                'id' => 0,
                'token' => $token
            );
            array_push($aItems, $aItem);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($aItems);
            return;
        }

        $mailddbb= $util->selectWhere2('clientes',array('id'), " email='".$email."'");
        if(intval($mailddbb[0])>0){
            $aItem = array(
                'result' => 'error2',
                'id' => 0,
                'token' => $token
            );
            array_push($aItems, $aItem);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($aItems);
            return;
        }



        $values = array( $dni, $nombre, $apellidos, $dir, $localidad, $provincia, $region, $cp, $tel1, $tel2, $email, $notas, $alta, $alta, $reseller);

        // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

        $result = $util->insertInto('clientes', $t_clientes, $values);
        $util->log('La Api:'.$_POST['reseller'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);

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
    }
?>