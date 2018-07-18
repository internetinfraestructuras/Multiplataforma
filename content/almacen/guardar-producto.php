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



if(isset($_POST['action']) && $_POST['action'] == 'productos')
{



    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['producto']) ? $_POST['producto'] : null;
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
        $proveedor = $util->cleanstring($post_data['proveedor']);
        $tipo = $util->cleanstring($post_data['tipo']);
        $modelo = $util->cleanstring($post_data['modelo']);
        $numeroSerie = $util->cleanstring($post_data['numero-serie']);
        $precioProv=$util->cleanstring($post_data['precio-proveedor']);
        $beneficio=$util->cleanstring($post_data['beneficio']);
        $pvp=$util->cleanstring($post_data['precio-pvp']);
        $impuesto=$util->cleanstring($post_data['impuesto']);


    }

    $ls=$util->selectWhere3("ALMACENES",array("ID"),"ID_EMPRESA=".$_SESSION['REVENDEDOR']);

    foreach($ls as $row)
        $almacen=$row;


    $values = array( $almacen[0],$proveedor,$tipo,$modelo,0,$numeroSerie,$precioProv,$beneficio,$pvp,$impuesto);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $result = $util->insertInto('PRODUCTOS', $t_productos, $values);


    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);

    /*
     * UNA VEZ CREADO EL PRODUCTO ASOCIAMOS LOS ATRIBUTOS A DICHO PRODUCTO $RESULT TIENE EL ID DEL PRODUCTO
     */

    $post_data = isset($_POST['atributos']) ? $_POST['atributos'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    // check post data
    /*
    if ($post_data === null) {
        if ($is_ajax === false) {
            _redirect('#alert_mandatory');
        } else {
            die('_mandatory_');
        }
    }*/
    $idProducto=$result;
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


        $valor = $util->cleanstring($post_data['atributo']);
        $id= $util->cleanstring($post_data['id']);
        $values=array($id,$idProducto,$valor);

        $result = $util->insertInto('PRODUCTOS_ATRIBUTOS', $t_productos_atributos, $values);

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
    &&
    md5($_POST['id']) ==  $_POST['hash']
)
{


    $id = $_POST['id'];
    $dni = $util->cleanstring($_POST['dni']);
    $nombre = $util->cleanstring($_POST['nombre']);
    $apellidos = $util->cleanstring($_POST['apellidos']);
    $dir = $util->cleanstring($_POST['direccion']);
    $cp = $util->cleanstring($_POST['cp']);
    $email = $util->cleanstring($_POST['email']);
    $tel1 = $util->cleanstring($_POST['tel1']);
    $tel2 = $util->cleanstring($_POST['tel2']);
    $email = $util->cleanstring($_POST['email']);
    $notas = $util->cleanstring($_POST['notas']);
    $region = $util->cleanstring($_POST['region']);
    $provincia = $util->cleanstring($_POST['provincia']);
    $localidad = $util->cleanstring($_POST['localidad']);
    $alta = $util->cleanstring($_POST['alta']);

    if(isset($_POST['region'])){
        $values = array($dni, $nombre, $apellidos, $dir, $cp, $tel1, $tel2, $email, $notas, $region, $provincia, $localidad,$alta);
        $campos = array('dni', 'nombre', 'apellidos', 'direccion', 'cp', 'tel1', 'tel2', 'email', 'notas', 'region', 'provincia', 'localidad','fecha_alta');
    } else {
        $values = array($dni, $nombre, $apellidos, $dir, $cp, $tel1, $tel2, $email, $notas);
        $campos = array('dni', 'nombre', 'apellidos', 'direccion', 'cp', 'tel1', 'tel2', 'email', 'notas');
    }
    $result = $util->update('clientes', $campos, $values, "id=".$id);
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