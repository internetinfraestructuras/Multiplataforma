<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 17/07/2018
 * Time: 11:50
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
require_once ('../../clases/Paquete.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');



// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------


if(isset($_POST['action']) && $_POST['action'] == 'paquetes')
{


    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['paquete']) ? $_POST['paquete'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }

    // EXTRACT DATA FROM POST
    foreach ($post_data as $key => $value)
    {
        $key_title = ucfirst($key);

        $explode = @explode('_', $key_title);
        if (!isset($explode[1]))
            $explode = @explode('-', $key_title);

        if (isset($explode[1])) {
            $key_title = implode(' ', $explode);
            $key_title = ucwords(strtolower($key_title));
        }

        // se recogen los datos post y se pasan por la funcion que limpia los caracteres suceptibles de generar inyeccion SQL

        $nombre = $util->cleanstring($post_data['nombre']);
        $internet=$util->cleanstring($post_data['internet']);
        $fijo=$util->cleanstring($post_data['fijo']);
        $movil=$post_data['movil'];
        $tv=$util->cleanstring($post_data['tv']);

        $coste = $util->cleanstring($post_data['precio-coste']);

        $margen = $util->cleanstring($post_data['precio-beneficio']);
        $impuestos= $util->cleanstring($post_data['precio-impuestos']);
        $pvp= $util->cleanstring($post_data['precio-pvp']);


    }

    $values = array( $nombre,$coste,$margen,$impuestos,$pvp,$_SESSION['REVENDEDOR']);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

     $resultPaquete = $util->insertInto('paquetes', $t_paquetes, $values);
     $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el paquete:'.$nombre.' con el resultado:'.$resultPaquete);

     if(!empty($internet))
     {
         $values = array( $resultPaquete,$internet);
         $result = $util->insertInto('paquetes_servicios', $t_paquetes_servicios, $values);
         $util->log('El administrador:'.$_SESSION['USER_ID'].' ha asociado al paquete:'.$resultPaquete.' el servicio:'.$result);
     }
    if(!empty($fijo))
    {
        $values = array( $resultPaquete,$fijo);
        $result = $util->insertInto('paquetes_servicios', $t_paquetes_servicios, $values);
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha asociado al paquete:'.$resultPaquete.' el servicio:'.$result);
    }
    if(!empty($movil))
    {

        for($i=0;$i<count($movil);$i++)
        {
        $values = array( $resultPaquete,$util->cleanstring($movil[$i]));
        $result = $util->insertInto('paquetes_servicios', $t_paquetes_servicios, $values);
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha asociado al paquete:'.$resultPaquete.' el servicio:'.$result);
        }
    }
    if(!empty($tv))
    {
        $values = array( $resultPaquete,$tv);
        $result = $util->insertInto('paquetes_servicios', $t_paquetes_servicios, $values);
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha asociado al paquete:'.$resultPaquete.' el servicio:'.$result);
    }


    if(intval($result)>0){
        if($is_ajax === false) {
            _redirect('#alert_success');
            exit;
        } else {
            die('_success_');
        }
    } else{
        if($is_ajax === false) {
            _redirect('#alert_failed');
            exit;
        } else {
            die('_failed_');
        }
    }

}

// todo: --------------------------------------------
// cuando el cliente es editado
// todo: --------------------------------------------

if(
    (isset($_POST['oper']) && $_POST['oper'] == 'edit')
    &&
    (isset($_POST['id']) && $_POST['id'] != '')

)
{


    $id = $_POST['id'];

    $nombre = $util->cleanstring($_POST['nombre']);
    $coste = $util->cleanstring($_POST['coste']);
    $margen = $util->cleanstring($_POST['margen']);
    $impuesto = $util->cleanstring($_POST['impuesto']);
    $pvp = $util->cleanstring($_POST['pvp']);

    if(isset($_POST['idContrato']))
    {

        Paquete::modificarPaqueteContrato($_POST['idContrato'],$_POST['idLinea'],$id,$coste,$margen,$impuesto,$pvp);
    }
    else
    {


        $values = array($nombre,$coste,$margen,$impuesto,$pvp);
        $campos = array('NOMBRE','PRECIO_COSTE','MARGEN','IMPUESTO','PVP');

        $result = $util->update('paquetes', $campos, $values, "id=".$id. " AND id_empresa=".$_SESSION['REVENDEDOR']);
        $util->log('El usuario:'.$_SESSION['USER_ID'].' ha modificado el paquete: '.$id.' con el resultado:'.$result);
    }


} else{
    echo "nose";
    die();
}



function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}


?>