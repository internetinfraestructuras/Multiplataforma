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
check_session(1);


date_default_timezone_set('Etc/UTC');

	require('config.inc.php');


/** ******************************** **
 *	@usuarios FORM
 ** ******************************** **/

	// Check Action First!
	if(isset($_POST['action']) && $_POST['action'] == 'usuarios') {


        $email_body = null;
        $array = $required = array();

        // catch post data
        $post_data = isset($_POST['usuarios']) ? $_POST['usuarios'] : null;
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

            $nombre = $util->cleanstring($post_data['nombre']);
            $apellidos = $util->cleanstring($post_data['apellidos']);
            $dni = $util->cleanstring($post_data['dni']);
            $email = $util->cleanstring($post_data['email']);
            $tel1 = $util->cleanstring($post_data['tel1']);
            $notas = $util->cleanstring($post_data['notas']);
            $nivel = $util->cleanstring($post_data['nivel']);
            $revendedor = $util->cleanstring($post_data['revendedor']);
            $usuario = $util->cleanstring($post_data['usuario']);
            $pass = md5($util->cleanstring($post_data['pass1']));
        }
        $t_usuarios=array('dni','nombre','apellidos','email','telefono','nivel','usuario','clave','notas','revendedor');

        $values = array( $dni, $nombre, $apellidos, $email, $tel1, $nivel,$usuario, $pass, $notas, $revendedor   );

        $result = $util->insertInto('usuarios', $t_usuarios, $values);
        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha creado el cliente:'.$dni.' con el resultado:'.$result);

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


if(
    (isset($_POST['oper']) && $_POST['oper'] == 'edit')
    &&
    (isset($_POST['id']) && $_POST['id'] != '')
)
{

    $id = $_POST['id'];
    $dni = $util->cleanstring($_POST['dni']);
    $nombre = $util->cleanstring($_POST['nombre']);
    $apellidos = $util->cleanstring($_POST['apellidos']);
    $tel1 = $util->cleanstring($_POST['tel1']);
    $email = $util->cleanstring($_POST['email']);
    $notas = $util->cleanstring($_POST['notas']);
    $nivel = $util->cleanstring($_POST['nivel']);
    $activo = $util->cleanstring($_POST['activo']);
    $user = $util->cleanstring($_POST['usuario']);
    $pass = md5($_POST['pass1']);
    date_default_timezone_set('Europe/Madrid');
    $date = date('Y/m/d H:i:s');

    if(intval($nivel)<=0 && !$_SESSION['USER_LEVEL'] == 0)
        $nivel=1;

    if($_POST['pass1']!=''){
        $campos=array('dni','nombre','apellidos','email','telefono','nivel','notas','ultima_modificacion','activo','usuario','clave');
        $values = array( $dni, $nombre, $apellidos, $email, $tel1, $nivel, $notas, $date, 1,$user,$pass);
    }else {
        $campos=array('dni','nombre','apellidos','email','telefono','nivel','notas','ultima_modificacion','activo','usuario');
        $values = array( $dni, $nombre, $apellidos, $email, $tel1, $nivel, $notas, $date, 1,$user);
    }


    $result = $util->update('usuarios', $campos, $values, "id='$id'");
    $util->log('El administrador:'.$_SESSION['USER_ID'].' ha modificado el usuario:'.$id.' con el resultado:'.$result);


}



function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}



?>