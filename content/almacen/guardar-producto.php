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



if(isset($_POST['action']) && $_POST['action'] == 'productos')
{



    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['producto']) ? $_POST['producto'] : null;
    $is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 'true') ? true : false;

    //check post data
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
        $atributos=$post_data['atributo'];

    }

    $ls=$util->selectWhere3("almacenes",array("ID"),"ID_EMPRESA=".$_SESSION['REVENDEDOR']);

    foreach($ls as $row)
        $almacen=$row;



    $values = array( $almacen[0],$proveedor,$tipo,$modelo,1,$numeroSerie,$precioProv,$beneficio,$pvp,$impuesto);

    // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

    $resultProducto = $util->insertInto('productos', $t_productos, $values);


    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);

    /*
     * UNA VEZ CREADO EL PRODUCTO ASOCIAMOS LOS ATRIBUTOS A DICHO PRODUCTO $RESULT TIENE EL ID DEL PRODUCTO
     */

    // EXTRACT DATA FROM POST

    $j=0;
    for($i=0;$i<count($atributos)/2;$i++)
    {


        $valor= $util->cleanstring($atributos[$j]);
        $j++;
        $id= $util->cleanstring($atributos[$j]);
        $j++;
        $values=array($id,$resultProducto,$valor);


        $result = $util->insertInto('productos_atributos', $t_productos_atributos, $values);

    }


    /*if(intval($result)>0){
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
    }*/

}

// todo: --------------------------------------------
// cuando el producto
// todo: --------------------------------------------

if((isset($_POST['oper']) && $_POST['oper'] == 'edit')&&(isset($_POST['id']) && $_POST['id'] != ''))
{

    $idProducto = $_POST['id'];
    $numeroSerie = $util->cleanstring($_POST['numeroSerie']);
    $proveedor = $util->cleanstring($_POST['proveedor']);
    $tipo= $util->cleanstring($_POST['tipo']);
    $modelo = $util->cleanstring($_POST['modelo']);

    $coste = $util->cleanstring($_POST['coste']);

    $margen = $util->cleanstring($_POST['margen']);

    $pvp = $util->cleanstring($_POST['pvp']);
    $impuesto = $util->cleanstring($_POST['impuesto']);

    $atributos = $_POST['atributo'];

    $atributosNuevos=$_POST['atributo-nuevo'];


    $campos=array('numero_serie',"id_proveedor","id_tipo_producto","id_modelo_producto","precio_prov","margen","pvp","impuestos");
    $values=array($numeroSerie,$proveedor,$tipo,$modelo,$coste,$margen,$pvp,$impuesto);
    $result = $util->update('productos', $campos, $values, "id=".$idProducto,false);

    $util->log('El usuario:'.$_SESSION['USER_ID'].' ha modificado el producto: '.$id.' con el resultado:'.$result);

    for($i=0;$i<count($atributos);$i++)
    {

        $valor= $util->cleanstring($atributos["valor"][$i]);

        $id= $util->cleanstring($atributos["id"][$i]);

        $values=array($valor,$idProducto);
       // echo "Se modifica".$id." con el valor".$valor;
        $campos=array("valor");

        if(!isset($atributosNuevos))
            $result = $util->update('productos_atributos', $campos, $values, "id_producto=".$idProducto." AND id=".$id);
        else

            $result=$util->delete('productos_atributos','id',$id);

    }

    if(isset($atributosNuevos))
    {

        for($i=0;$i<count($atributosNuevos);$i++)
        {

            $valor= $util->cleanstring($atributosNuevos["valor"][$i]);
            $id= $util->cleanstring($atributosNuevos["id"][$i]);

            // echo "Se modifica".$id." con el valor".$valor;

            $values=array($valor,$idProducto,$id);


            $result = $util->insertInto('productos_atributos', $t_productos_atributos, $values);


        }
    }





} else{
    echo "nose EDIT";
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