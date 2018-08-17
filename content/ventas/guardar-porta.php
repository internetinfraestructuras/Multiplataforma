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
//$util->verErrores(1);


check_session(3);
date_default_timezone_set('Etc/UTC');

$tipo = $_POST['tipo'];
$id_cliente = $_POST['id_cliente'];
$donante = $_POST['donante'];
$firma = $_POST['firma'];
$num_porta = $_POST['num_porta'];


if($tipo==2) {

    $tit_n = $_POST['tit_n'];
    $tit_a = $_POST['tit_a'];
    $tit_tcli = $_POST['tit_tcli'];
    $tit_tdoc = $_POST['tit_tdoc'];
    $tit_dni = $_POST['tit_dni'];
    $tit_region = $_POST['tit_region'];
    $tit_prov = $_POST['tit_prov'];
    $tit_loc = $_POST['tit_loc'];
    $tit_dir = $_POST['tit_dir'];
    $tit_cp = $_POST['tit_cp'];
    $tipo_acceso = $_POST['tipo_acceso'];
    $hora_porta = $_POST['hora_porta'];

} else {

    $tarifa = $_POST['tarifa'];
    $icc = $_POST['icc'];
    $dc = $_POST['dc'];
    $modalidad = $_POST['modalidad'];
}


if (isset($_POST['action']) && $_POST['action'] == 'porta') {

    $campos = array('ID_CLIENTE','ID_EMPRESA',          'FECHA_SOLICITUD',      'TIPO','NOMBRE_TITULAR',    'TIPO_TITULAR',
        'CIF_TITULAR','DIR_TITULAR','REGION_TITULAR','PROV_TITULAR','POBLACION_TITULAR','CP_TITULAR','TIPO_DOC',
        'DONANTE','HORARIO','NUMERO_PORTAR','FIRMA','TIPO_ACCESO');

    $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'), $tipo, $tit_n." ". $tit_a,$tit_tcli,
        $tit_dni,       $tit_dir,    $tit_region,     $tit_prov,     $tit_loc,           $tit_cp,   $tit_tdoc,
        $donante, $hora_porta, $num_porta,    $firma, $tipo_acceso);

    if($tipo==3) {
        $campos = array('ID_CLIENTE','ID_EMPRESA',          'FECHA_SOLICITUD',      'TIPO', 'TIPO_DOC',
            'DONANTE','ICC','DC','TARIFA','MODALIDAD_ORIGEN','NUMERO_PORTAR','FIRMA');

        $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'),$tipo, $tit_n." ". $tit_a,$tit_tcli,
            $donante, $hora_porta, $num_porta, $firma);
    }

    $id = $util->insertInto('portabilidades', $campos, $values);
    echo $id;

} else {
    echo "nose";
    die();
}


?>