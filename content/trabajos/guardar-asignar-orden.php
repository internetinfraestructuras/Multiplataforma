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
require_once ('../../clases/Orden.php');

$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');



// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------


if(isset($_POST['action']) && $_POST['action'] == 'ordenes')
{



    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['ordenes']) ? $_POST['ordenes'] : null;
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

        $asignacionOrdenes = ($_POST['ordenes']['orden']);
        $ordenesId=$_POST['ordenes']['ordenId'];

    }

    for($i=0;$i<count($asignacionOrdenes);$i++)
    {
        echo "<hr>La orden es".$ordenesId[$i];
        if($asignacionOrdenes[$i]!=0)
        {

            Orden::asignarOrdenUsuario($ordenesId[$i],$asignacionOrdenes[$i]);

           $result= $util->log('El usuario:'.$_SESSION['USER_ID'].' ha asociado a asignado la orden:'.$ordenesId[$i].'al usuario:'.$asignacionOrdenes[$i]);
        }
    }

/*
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
*/
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
    @$cascadaPrecio=$_POST['cascada-precio'];

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

        if($cascadaPrecio=="on")
        {
            $util = new util();
            $listadoContratos=$util->selectWhere3('contratos,contratos_lineas',
                array("contratos_lineas.id,contratos_lineas.id_contrato"),
                "contratos_lineas.estado!=5 
                AND contratos.id=contratos_lineas.id_contrato 
                AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
                AND contratos_lineas.id_tipo=1 
                AND contratos_lineas.id_asociado=".$id);

            for($i=0;$i<count($listadoContratos);$i++)
            {
                $campos=array("precio_proveedor","beneficio","impuesto","pvp");

                $values=array($coste,$margen,$impuesto,$pvp);

                $result = $util->update('contratos_lineas', $campos, $values, "contratos_lineas.id=".$listadoContratos[$i]['id']);
            }

        }


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