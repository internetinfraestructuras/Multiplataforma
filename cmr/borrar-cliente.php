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


date_default_timezone_set('Etc/UTC');
	@ini_set('display_errors', 0);
	@ini_set('track_errors', 0);


/** ******************************** **
 *	BORRAR UN CLIENTE
 *  url: http://ftth.internetinfraestructuras.es/cmr/borrar-cliente.php
 *
 *  Espera
 *   $_POST['reseller'] = id del reseller
 *   $_POST['id']= Id del cliente a borrar
 *   $_POST['action']='delete' + id  (pasar el string delete seguido del id del cliente a borrar, sin espacios
 *   $_POST['hash']=md5(id)  id del cliente a borrar en MD5
 *
 *  Devuelve:
 *      String:
 *      _success_ o _failed_
 ** ******************************** **/


    if(
        isset($_POST['action'])
        &&
        isset($_POST['id']) && $_POST['id'] != ''
        &&
        isset($_POST['reseller']) && $_POST['reseller'] != ''
        &&
        $_POST['action']=='delete'.$_POST['id']
        &&
        $_POST['hash']==md5($_POST['id'])
    )
    {

        $id = $_POST['id'];

        $result = $util->deleteWhere(DDBB.'clientes', 'id', "id=" . $id . " and user_create in (select id from usuarios where revendedor = ".$_POST['reseller'].")");

        $util->log('El administrador:'.$_POST['reseller'].' ha borrado el cliente ID:'.$id.' con el resultado:'.$result);

        if(intval($result)>0){
           echo '_success_';

        } else{
            echo '_failed_';
        }
}
?>