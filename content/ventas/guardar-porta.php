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
include_once('../../config/util.php');
include_once('../../config/def_tablas.php');
include_once('../../clases/AltaTecnica.php');
$util = new util();
//$util->verErrores(1);


check_session(3);
date_default_timezone_set('Etc/UTC');

$tipo = $_POST['tipo'];
$id_cliente = $_POST['id_cliente'];
$donante = $_POST['donante'];
$firma = $_POST['firma'];
$num_porta = $_POST['num_porta'];
$tipo_cli = $_POST['tipo_cli'];
$tipo_doc = $_POST['tipo_doc'];
$tarifa = $_POST['tarifa'];

$codSolicitud=0;

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


    $icc = $_POST['icc'];
    $dc = $_POST['dc'];
    $iccOrigen = $_POST['iccorigen'];
    $dcOrigen = $_POST['dcorigen'];
    $modalidad = $_POST['modalidad'];
    $docNombre = $_COOKIE['documentos[0]'];
    $documento = "../documentos/".$_COOKIE['documentos[0]'];

    $imagedata = file_get_contents($documento);
    $base64 = base64_encode($imagedata);


}


if (isset($_POST['action']) && $_POST['action'] == 'porta') {

    $campos = array('ID_CLIENTE','ID_EMPRESA', 'FECHA_SOLICITUD',      'TIPO','NOMBRE_TITULAR',    'TIPO_TITULAR',
        'CIF_TITULAR','DIR_TITULAR','REGION_TITULAR','PROV_TITULAR','POBLACION_TITULAR','CP_TITULAR','TIPO_DOC',
        'DONANTE','HORARIO','NUMERO_PORTAR','FIRMA','TIPO_ACCESO','TARIFA');

    $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'), $tipo, $tit_n." ". $tit_a,$tipo_cli,
        $tit_dni,       $tit_dir,    $tit_region,     $tit_prov,     $tit_loc,           $tit_cp,   $tipo_doc,
        $donante, $hora_porta, $num_porta,    $firma, $tipo_acceso, $tarifa);

    if($tipo==3) {
        $campos = array('ID_CLIENTE','ID_EMPRESA',          'FECHA_SOLICITUD',      'TIPO',
            'DONANTE','ICC','DC','TARIFA','MODALIDAD_ORIGEN','NUMERO_PORTAR','FIRMA','TIPO_ACCESO','TIPO_DOC','TIPO_TITULAR');

        $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'),$tipo,
            $donante, $icc, $dc, $tarifa, $modalidad, $num_porta, $firma,1,$tipo_doc,$tipo_cli);
    }

    $id = $util->insertInto('portabilidades', $campos, $values);
                    //0        1         2           3       4               5           6               7           8           9
                    //   10          11     12      13           14   15     16       17      18          19               20
    //              //   21      22     23                  24                 25                 26                27
    $t_clientes=array('ID', 'NOMBRE','APELLIDOS','DNI','DOCUMENTO_URL','DOCUMENTO','TIPO_DOCUMENTO','DIRECCION','LOCALIDAD','PROVINCIA',
                        'COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','FECHA_MODIFICA','NOTAS',
                        'BAJA','BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD');

    $cliente = $util->selectWhere3('clientes',$t_clientes, 'ID='.$id_cliente);

    $proveedor_movil = $util->selectLast('servicios','ID_PROVEEDOR','ID='.$tarifa);

    if(intval($proveedor_movil)==PROV_AIRE){
                //  $idEmpresa,$idServicio,$tipoCliente,$consentimiento,$nombre,$apellido1,$apellido2,
                //  $fechaNacimiento,$email,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento,
                //  $tipoDocumento,$nif,$icc,$dc,$telefono,$modalidadActual,$iccOrigen,$dcOrigen

        // se dividen los apellidos en dos
        $ape = explode(" ", $cliente[2]);

        $codSolicitud = AltaTecnica::addNuevaPortabilidadAireNetworks(
            '', $tarifa, $tipo_cli, $cliente[23], $cliente[1], $ape[0], $ape[1],
                    $cliente[25], $cliente[17], $cliente[10], $cliente[9], $cliente[8], $cliente[14], $cliente[7], '0', $docNombre, $base64,
                    $cliente[6],  $cliente[3], $icc, $dc, $num_porta, $modalidad, $iccOrigen, $dcOrigen);


    } else {

    }

    echo $codSolicitud;

} else {
    echo "nose";
    die();
}

//function imprimir($codSolicitud){
//    header('../servicios/airenetworks/obtener-documento.php?codSolicitud='.$codSolicitud.'&tipo=PORTABILIDAD');
//}


?>