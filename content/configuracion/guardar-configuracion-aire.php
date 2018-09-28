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
require_once('../../clases/Empresa.php');
require_once('../../clases/Servicio.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');



// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------



if(isset($_POST['action']) && $_POST['action'] == 'configuracion')
{


    $array = $required = array();

    // catch post data
    $post_data = isset($_POST['configuracion']) ? $_POST['configuracion'] : null;
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

        $usuario=$post_data['usuario'];
        $pass=$post_data['pass'];
        $url=$post_data['url'];





    }
    $tarifasInternas=$_POST['tarifas']['internas'];
    $tarifasAire=$_POST['tarifas']['aire'];

    for($i=0;$i<count($tarifasInternas);$i++)
    {

        echo $tarifasAire[$i]."<br>";
        if($tarifasAire[$i]!="0")
        {

            Servicio::setServicioExterno($_SESSION['REVENDEDOR'],$tarifasInternas[$i],$tarifasAire[$i]);

        }
    }

   Empresa::setConfiguracionAireNetworks($_SESSION['REVENDEDOR'],$usuario,$pass,$url);

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
    }*/
}





?>