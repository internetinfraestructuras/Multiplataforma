<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 10:19
 */

require_once('../config/util.php');
require_once('../config/def_tablas.php');
$util = new util();

//$id=$_POST['reseller'];
//$token=$_POST['token'];
//if(!$util->check_token($id,$token)) die("Error de token");

    date_default_timezone_set('Etc/UTC');
	@ini_set('display_errors', 0);
	@ini_set('track_errors', 0);



/** ******************************** **
 *	Borrar Reseller
 * *  url: http://ftth.internetinfraestructuras.es/cmr/borrar-reseller.php
 *
 * espera:
 *      $_POST['action']=='delete'.$_POST['id']         String compuesto de la cadena delete+el numero del id a borrar, sin espacios
 *      $_POST['id']        Id del reseller a borrar
 *      $_POST['check']     Id a borrar en MD5
 ** ******************************** **/

    if(
        isset($_POST['action'])
        &&
        isset($_POST['id']) && $_POST['id'] != ''
        &&
        $_POST['action']=='delete'.$_POST['id']
        &&
        md5($_POST['id'])==$_POST['check']
    )  {

        $id = $_POST['id'];

        $result = $util->delete('revendedores', 'id', $id);

        $util->log('El administrador:'.$_POST['id'].' ha borrado el reseller ID:'.$id.' con el resultado:'.$result);

        if(intval($result)>0){
            echo '_success_';
        } else{
            echo '_failed_';
        }
}



?>