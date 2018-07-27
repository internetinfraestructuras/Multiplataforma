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

var_dump($_POST);
if(isset($_POST['action']) && $_POST['action'] == 'modelos')
{


    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['modelo']) ? $_POST['modelo'] : null;
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


        $tipo = $util->cleanstring($post_data['tipo']);
        $nombre = $util->cleanstring($post_data['nombre']);

        }


    $values = array( $tipo,$nombre,$_SESSION['REVENDEDOR']);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $result = $util->insertInto('PRODUCTOS_MODELOS', $t_productos_modelos, $values);
    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el modelo:'.$dni.' con el resultado:'.$result);

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
    $tipo = $util->cleanstring($_POST['tipo']);

    $values=array($nombre,$tipo);
    $campos=array('nombre','id_tipo');

    $result = $util->update('productos_modelos', $campos, $values, "id=".$id);

    $util->log('El usuario:'.$_SESSION['USER_ID'].' ha modificado el modelo de producto: '.$id.' con el resultado:'.$result);
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