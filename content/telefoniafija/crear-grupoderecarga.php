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

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "here";
require_once('../../config/util.php');
require_once('../../clases/telefonia/classTelefonia.php');
require_once('../../config/def_tablas.php');
$util = new util();
$tel = new Telefonia();

check_session(3);
date_default_timezone_set('Etc/UTC');



// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------


if(isset($_POST['action']) && $_POST['action'] == 'gruporecarga')
{

    //echo "por if";
    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['gruporecarga']) ? $_POST['gruporecarga'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }

    //echo "LLEGAMOS".$post_data;
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
        $importe = $util->cleanstring($post_data['importe']);
        $acumulable = $util->cleanstring($post_data['acumulable']);
        $color = $util->cleanstring($post_data['background-color']);



    }
    //echo "nombre:$nombre ,importe:$importe,acumulable $acumulable, color $color";
    try {
    $result = $tel->addGrupoRecarga($_SESSION['CIF'], $nombre, $importe, $acumulable,$color);
    if ($result == 1)
        //echo "ok insertado";
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el grupo de recarga:'.$nombre.' con el resultado:'.$result);
    }
    catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
    }

    /*$values = array( $nombre,$_SESSION['REVENDEDOR'],2);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $result = $util->insertInto('proveedores', $t_proveedores, $values);
    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);
*/

    echo "creado ok";
    if(intval($result)>0){
        if($is_ajax === false) {
            //alertaConReload("Grupo Creado","Grupo de recarga creado con éxito","success","Aceptar","_redirect");
            _redirect('?alert_success=1');
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

if(isset($_POST['action']) && $_POST['action'] == 'edit-gruporecarga')
{
    //echo "por if";
    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['gruporecarga']) ? $_POST['gruporecarga'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }

    //echo "LLEGAMOS".$post_data;
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
        $importe = $util->cleanstring($post_data['importe']);
        $acumulable = $util->cleanstring($post_data['acumulable']);
        $color = $util->cleanstring($post_data['background-color']);



    }
    echo "nombre:$nombre ,importe:$importe,acumulable $acumulable, color $color";

    try {
        $result = $tel->updateGrupoRecarga($_SESSION['CIF'], $nombre, $importe, $acumulable,$color);
        if ($result == 1)
            //echo "ok update";
            $util->log('El administrador:'.$_SESSION['USER_ID'].' ha updateado el grupo de recarga:'.$nombre.' con el resultado:'.$result);
    }
    catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
    }



    //echo "creado ok";
    if(intval($result)>0){
        if($is_ajax === false) {
            //alertaConReload("Grupo Creado","Grupo de recarga creado con éxito","success","Aceptar","_redirect");
            header("Location:http://127.0.0.1/content/telefoniafija/gruposderecarga.php?update_success=1");
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



function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}



?>