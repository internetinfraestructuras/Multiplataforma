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
require_once ('../../clases/Servicio.php');

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
        $instalacion=$_POST['ordenes']['instalacion'];
        $cobro=$_POST['ordenes']['cobro'];
        $factura=$_POST['ordenes']['factura'];

    }
    $facturable=2;
    for($i=0;$i<count($asignacionOrdenes);$i++)
    {
        echo "<hr>La orden es ".$ordenesId[$i];
        if($asignacionOrdenes[$i]!=0)
        {
            if($cobro!=0)
            {
                if($instalacion[$i]!=0)
                {
                    if($factura[$i]==1)
                        $facturable=1;
                    else
                        $facturable=0;


                    $detallesServicio=Servicio::getDetallesServicio($instalacion[$i]);
                    Orden::setFacturableOrden($ordenesId[$i],$facturable,$detallesServicio[0][3],$detallesServicio[0][1],"0",$detallesServicio[0][0],1,$cobro[0][$i]);
                }


            }
        Orden::asignarOrdenUsuario($ordenesId[$i],$asignacionOrdenes[$i]);

         $result= $util->log('El usuario:'.$_SESSION['USER_ID'].' ha asociado a asignado la orden:'.$ordenesId[$i].'al usuario:'.$asignacionOrdenes[$i]);
        }
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



function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}


?>