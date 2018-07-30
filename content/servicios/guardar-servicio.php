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



if(isset($_POST['action']) && $_POST['action'] == 'servicios')
{



    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['servicio']) ? $_POST['servicio'] : null;
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
        $tipo = $util->cleanstring($post_data['tipo']);
        $precioProv=$util->cleanstring($post_data['precio-proveedor']);
        $beneficio=$util->cleanstring($post_data['beneficio']);
        $pvp=$util->cleanstring($post_data['precio-pvp']);
        $impuesto=$util->cleanstring($post_data['impuesto']);
        $atributos=$post_data['atributo'];
        $proveedor=$post_data['proveedor'];

    }

    $values = array( $tipo,$_SESSION['REVENDEDOR'],$nombre,$precioProv,$impuesto,$beneficio,$pvp,$proveedor);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $resultServicio = $util->insertInto('SERVICIOS', $t_servicios, $values);


    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);

    /*
     * UNA VEZ CREADO EL PRODUCTO ASOCIAMOS LOS ATRIBUTOS A DICHO PRODUCTO $RESULT TIENE EL ID DEL PRODUCTO
     */

    // EXTRACT DATA FROM POST
    var_dump($atributos);
    $j=0;
    for($i=0;$i<count($atributos)/2;$i++)
    {

        $valor= $util->cleanstring($atributos[$j]);
        $j++;
        $id= $util->cleanstring($atributos[$j]);
        $j++;
        $values=array($id,$resultServicio,$valor);
        echo $valor."<br/>";

        $result = $util->insertInto('SERVICIOS_ATRIBUTOS', $t_servicios_atributos, $values);

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

    $nombre = $util->cleanstring($_POST['nombre']);
    $tipo = $util->cleanstring($_POST['tipo']);
    $precioProv=$util->cleanstring($_POST['precio-proveedor']);
    $beneficio=$util->cleanstring($_POST['beneficio']);
    $pvp=$util->cleanstring($_POST['precio-pvp']);
    $impuesto=$util->cleanstring($_POST['impuesto']);
    $atributos=$_POST['atributo'];

    $values=array($nombre,$tipo,$precioProv,$beneficio,$pvp,$impuesto);
    $campos=array("nombre","id_servicio_tipo","");
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