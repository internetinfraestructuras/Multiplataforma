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


    $util->delete('contratos_campanas_borrador','ID_CONTRATO',$_POST['id_borrador']);

    $campos = array('ID_CONTRATO','ID_CAMPANA','DTO','DTO_DIAS','DTO_HASTA');

    $values = array( $_POST['id_borrador'], $_POST['id_campana'],$_POST['dto'],$_POST['dias'],$_POST['hasta']);

    $util->insertInto('contratos_campanas_borrador', $campos, $values);

} else {
    echo "nose";
    die();
}
?>