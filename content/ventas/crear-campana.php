<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 17/07/2018
 * Time: 11:50
 */
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');



// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------


if(isset($_POST['action']) && $_POST['action'] == 'campanas')
{


    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['campana']) ? $_POST['campana'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }

    echo "LLEGAMOS".$post_data;
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
        $inicio = $util->cleanstring($post_data['inicio']);
        $fin = $util->cleanstring($post_data['fin']);
        $duracion = $util->cleanstring($post_data['duracion']);
        $descuento = $util->cleanstring($post_data['descuento']);

    }

    $values = array( $nombre,$_SESSION['REVENDEDOR'],$inicio,$fin,$descuento,$duracion);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $result = $util->insertInto('campanas', $t_campanas, $values);
    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado la campaña:'.$dni.' con el resultado:'.$result);


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
    $id=$util->cleanstring($_POST['id']);
    $nombre = $util->cleanstring($_POST['nombre']);
    $inicio = $util->cleanstring($_POST['inicio']);
    $fin = $util->cleanstring($_POST['fin']);
    $duracion = $util->cleanstring($_POST['duracion']);
    $descuento = $util->cleanstring($_POST['descuento']);

        $values = array($nombre,$inicio,$fin,$duracion,$descuento);
        $campos = array('NOMBRE','FECHA_INICIO','FECHA_FIN','DURACION','DESCUENTO');

    $result = $util->update('campanas', $campos, $values, "id=".$id." AND ID_EMPRESA=".$_SESSION['REVENDEDOR']);
    $util->log('El usuario:'.$_SESSION['USER_ID'].' ha modificado el cliente: '.$dni.' con el resultado:'.$result);
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