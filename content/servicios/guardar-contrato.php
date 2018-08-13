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


    $util->delete('contratos_borrador','ID_CLIENTE',$_POST['id_cliente']);
    $campos = array('ID_CLIENTE','ID_EMPRESA');
    $values = array($_POST['id_cliente'], $_SESSION['REVENDEDOR']);
    $id = $util->insertInto('contratos_borrador', $campos, $values);
    echo $id;
} else {
    echo "nose";
    die();
}


?>