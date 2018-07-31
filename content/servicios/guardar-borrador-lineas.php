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
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');


if (isset($_POST['action']) && $_POST['action'] == 'borrador') {

    $campos = array('ID_CONTRATO','ID_PQUETE');
    $values = array($_POST['id_borrador'], $_POST['id_paquete']);
    $id = $util->insertInto('contratos_paquete_borrador', $campos, $values);

    $util->delete('contratos_lineas_borrador','ID_CONTRATO',$_POST['id_borrador']);


    foreach ($_POST['lineas'] as $lineas){
        $campos = array('ID_CONTRATO','ID_TIPO','ID_ASOCIADO','PVP');
        $values = array($_POST['id_borrador'], $_POST['id_paquete']);
        $id = $util->insertInto('contratos_lineas_borrador', $campos, $values);

    }


} else {
    echo "nose";
    die();
}


?>