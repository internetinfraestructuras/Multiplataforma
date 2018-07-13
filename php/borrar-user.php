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
check_session(2);

date_default_timezone_set('Etc/UTC');
	@ini_set('display_errors', 0);
	@ini_set('track_errors', 0);

	require('config.inc.php');


/** ******************************** **
 *	@revende FORM
 ** ******************************** **/


    if(
        isset($_POST['oper'])
        &&
        isset($_POST['id']) && $_POST['id'] != ''
        &&
        $_POST['oper']=='del'.$_POST['id']
    )
    {

        $id = $_POST['id'];

        $result = $util->delete('usuarios', 'id', $id);

        $util->log('El administrador:'.$_SESSION['USER_ID'].' ha borrado el usuario ID:'.$id.' con el resultado:'.$result);

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




?>