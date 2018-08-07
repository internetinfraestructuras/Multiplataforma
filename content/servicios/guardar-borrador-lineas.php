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


    $util->delete('contratos_lineas_borrador','ID_CONTRATO',$_POST['id_borrador']);

    //ID ID_PAQUETE	ID_TIPO	ID_ASOCIADO	ID_CONTRATO	PRECIO_PROVEEDOR	BENEFICIO	IMPUESTO	PVP
    //[id_servicio, id_familia, pvp_extra, cantidad, nom_servicio, 'e'];

    $campos = array('ID_PAQUETE','ID_CONTRATO','ID_TIPO','ID_ASOCIADO','PVP','PAQUETEOSERVICIO','NOMBRE');
//    var_dump($_POST['lineas']);

    foreach ($_POST['lineas'] as $lineas){

        for ($n=0;$n<intval($lineas[3]);$n++) {
            $values = array($_POST['id_paquete'], $_POST['id_borrador'], $lineas[1], $lineas[0], $lineas[2], $lineas[5], $lineas[4]);
            $util->insertInto('contratos_lineas_borrador', $campos, $values);
        }
    }

} else {
    echo "nose";
    die();
}
?>