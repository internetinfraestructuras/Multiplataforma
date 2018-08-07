<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../config/util.php');
require_once('../config/def_tablas.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');



    // todo: --------------------------------------------
	// cuando el cliente es creado por primera vez
    // todo: --------------------------------------------


	if(isset($_POST['action']) && $_POST['action'] == 'clientes') {


        $email_body = null;
        $array = $required = array();

        // catch post data
        $post_data = isset($_POST['clientes']) ? $_POST['clientes'] : null;
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
        foreach ($post_data as $key => $value) {
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
            $apellidos = $util->cleanstring($post_data['apellidos']);
            $dni = $util->cleanstring($post_data['dni']);
            $tdoc = $util->cleanstring($post_data['tipodoc']);
            $dir = $util->cleanstring($post_data['dir']);
            $localidad = $util->cleanstring($post_data['localidad']);
            $provincia = $util->cleanstring($post_data['provincia']);
            $region = $util->cleanstring($post_data['region']);
            $iban = $util->cleanstring($post_data['iban']);
            $swift = $util->cleanstring($post_data['swift']);
            $cp = $util->cleanstring($post_data['cp']);
            $tel1 = $util->cleanstring($post_data['tel1']);
            $tel2 = $util->cleanstring($post_data['tel2']);
            $email = $util->cleanstring($post_data['email']);
            $notas = $util->cleanstring($post_data['notas']);
            $banco = $util->cleanstring($post_data['banco']);
            $lopd = $util->cleanstring($post_data['lopd']);
            $tcli = $util->cleanstring($post_data['tipocli']);
            $fnac = $util->cleanstring($post_data['nacimiento']);
            $dirbanco = $util->cleanstring($post_data['dirbanco']);
            $nacion = $util->cleanstring($post_data['nacion']);

        }
/*
 * 

 */
        $campos=array('NOMBRE','APELLIDOS','DNI','TIPO_DOCUMENTO','DIRECCION','LOCALIDAD','PROVINCIA',
            'COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','NOTAS','BAJA',
            'BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD');

        $alta=date("Y-m-d");

        $values = array( $nombre, $apellidos, $dni, $tdoc, $dir, $localidad, $provincia, $region, $iban, $swift,
            $_SESSION['REVENDEDOR'], $cp, $tel1, $tel2, $email, $alta, $notas,0,  $banco,  $lopd,$tcli,$fnac,$dirbanco, $nacion);

        // llama a la funcion insertInto de la clase util que recibe la tabla (string) y dos arrays (campos y valores)

        $result = $util->insertInto('clientes', $campos, $values);
        $util->log('Se ha creado un cliente'.$_SESSION['REVENDEDOR'].' con dni :'.$dni.' con el id:'.$result);

        if(intval($result)>0){
            if($is_ajax === false) {
                _redirect('#alert_success');
                exit;
            } else {
                echo $result;
            }
        } else{
            if($is_ajax === false) {
                _redirect('#alert_failed');
                exit;
            } else {
                die('_failed_');
            }
        }

    } else {

        // todo: --------------------------------------------
        // cuando el cliente es editado
        // todo: --------------------------------------------

        if (
            (isset($_POST['oper']) && $_POST['oper'] == 'edit')
            &&
            (isset($_POST['id']) && $_POST['id'] != '')
            &&
            md5($_POST['id']) == $_POST['hash']
        ) {


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

            if (isset($_POST['region'])) {
                $values = array($dni, $nombre, $apellidos, $dir, $cp, $tel1, $tel2, $email, $notas, $region, $provincia, $localidad, $alta);
                $campos = array('dni', 'nombre', 'apellidos', 'direccion', 'cp', 'tel1', 'tel2', 'email', 'notas', 'region', 'provincia', 'localidad', 'fecha_alta');
            } else {
                $values = array($dni, $nombre, $apellidos, $dir, $cp, $tel1, $tel2, $email, $notas);
                $campos = array('dni', 'nombre', 'apellidos', 'direccion', 'cp', 'tel1', 'tel2', 'email', 'notas');
            }
            $result = $util->update('clientes', $campos, $values, "id=" . $id);
            $util->log('El usuario:' . $_SESSION['USER_ID'] . ' ha modificado el cliente: ' . $dni . ' con el resultado:' . $result);
        } else {
            echo "nose";
            die();
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