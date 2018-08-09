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

$action= $_POST['porta'];
$id_cliente= $_POST['id_cliente'];
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
$donante = $_POST['donante'];
$tipo_acceso = $_POST['tipo_acceso'];
$num_porta  = $_POST['num_porta'];
$hora_porta = $_POST['hora_porta'];
$firma = $_POST['firma'];
$tipo = $_POST['tipo'];




if (isset($_POST['action']) && $_POST['action'] == 'porta') {


    $campos = array('ID_CLIENTE','ID_EMPRESA',          'FECHA_SOLICITUD',      'TIPO','NOMBRE_TITULAR',    'TIPO_TITULAR',
                    'CIF_TITULAR','DIR_TITULAR','REGION_TITULAR','PROV_TITULAR','POBLACION_TITULAR','CP_TITULAR','TIPO_DOC',
                    'DONANTE','HORARIO','NUMEROS_PORTAR','FIRMA','TIPO_ACCESO');

    $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'),$tipo, $tit_n." ". $tit_a,$tit_tcli,
                    $tit_dni,       $tit_dir,    $tit_region,     $tit_prov,     $tit_loc,           $tit_cp,   $tit_tdoc,
                    $donante, $hora_porta, $num_porta,    $firma, $tipo_acceso);

    $id = $util->insertInto('portabilidades', $campos, $values);
    echo $id;
} else {
    echo "nose";
    die();
}


?>