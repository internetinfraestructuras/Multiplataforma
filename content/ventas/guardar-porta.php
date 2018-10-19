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
if(isset($_POST['firma']))
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


//    if(isset($_COOKIE['documentos[0]'])) {
//        $docNombre = $_COOKIE['documentos[0]'];
//        $documento = "../documentos/" . $_COOKIE['documentos[0]'];
//    }


}


if (isset($_POST['action']) && $_POST['action'] == 'porta') {


                     //0        1         2           3       4               5           6               7           8           9
        // 10          11     12      13           14   15     16       17      18          19               20
        // 21      22     23                  24                 25                 26                27
        // 28                                       29                                      30
    $t_clientes=array('clientes.ID', 'NOMBRE','APELLIDOS','DNI','DOCUMENTO_URL','DOCUMENTO','TIPO_DOCUMENTO','DIRECCION','clientes.LOCALIDAD','clientes.PROVINCIA',
        'clientes.COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','FECHA_MODIFICA','NOTAS',
        'BAJA','BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD',
        'comunidades.comunidad AS NOMCOMUNIDAD','provincias.provincia AS NOMPROVINCIA','municipios.municipio AS NOMLOCALIDAD');


    $cliente = $util->selectJoin('clientes',$t_clientes, ' JOIN comunidades ON comunidades.id = clientes.COMUNIDAD JOIN provincias ON provincias.id = clientes.PROVINCIA JOIN municipios ON municipios.id = clientes.LOCALIDAD ','','clientes.ID='.$id_cliente);
    $cliente= mysqli_fetch_array($cliente);

    $proveedor_movil = $util->selectLast('servicios','ID_PROVEEDOR','ID='.$tarifa);

    // se dividen los apellidos en dos
    $ape = explode(" ", $cliente[2]);
    $ape1 = $ape[0];


    if(isset($ape[1]))
        $ape2 = $ape[1];
    else
        $ape2='';

    $altaCorrecta=false;

    if(intval($proveedor_movil)==PROV_AIRE){

        // Nombre de la imagen
        $path = "../../".$_POST['documento'];

        // Extensión de la imagen
        $type = pathinfo($path, PATHINFO_EXTENSION);

        // Cargando la imagen
        $data = file_get_contents($path);

        // Decodificando la imagen en base64
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


        $imagedata = file_get_contents($path);
        $docNombre = substr($path,34,30);


                //  $idEmpresa,$idServicio,$tipoCliente,$consentimiento,$nombre,$apellido1,$apellido2,
                //  $fechaNacimiento,$email,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento,
                //  $tipoDocumento,$nif,$icc,$dc,$telefono,$modalidadActual,$iccOrigen,$dcOrigen


        switch (intval($tipo_doc)){
            case 1:
                $tipo_doc='0';
                break;
            case 2:
                $tipo_doc='1';
                break;
            case 3:
                $tipo_doc='4';
                break;
            case 4:
                $tipo_doc='2';
                break;
        }

        switch (intval($tipo_cli)){
            case 1:
                $tipo_cli='0';
                break;
            case 2:
                $tipo_cli='1';
                break;
            case 3:
                $tipo_cli='5';
                break;
            case 4:
                $tipo_cli='2';
                break;
        }

        switch (intval($cliente[23])){
            case 1:
                $lopd = '0';
                break;
            case 2:
                $lopd = '3';
                break;
            case 3:
                $lopd = '2';
                break;
            case 4:
                $lopd = '1';
                break;
            case 5:
                $lopd = '2';
                break;
            case 6:
                $lopd = '3';
                break;
            case 7:
                $lopd = '3';
                break;
            case 8:
                $lopd = '2';
                break;
        }

        $codSolicitud = AltaTecnica::addNuevaPortabilidadAireNetworks(
            $_SESSION['REVENDEDOR'], $tarifa, $tipo_cli, $lopd, $cliente[1], $ape1, $ape2,
                    $util->fecha_eur($cliente[25]), $cliente[17], $cliente[28], $util->eliminar_tildes($cliente[29]), $cliente[30], $cliente[14], $cliente[7], ' ', $docNombre, $base64,
                   $tipo_doc,  $cliente[3], $icc, $dc, $num_porta, $modalidad, $iccOrigen, $dcOrigen);

        if(intval($codSolicitud)>999){
            $altaCorrecta=true;
        } else {
            echo $codSolicitud;
        }

    } else {

        /*
         * $nombre,$nombreEmpresa,$tipoCliente,$tipoDocumento,$dni,$nombreContacto,$apellido1,$apellido2,$telContacto,
         * $movilContacto,$faxContacto,$emailContacto,$telefonoContacto,$calle,$numeroCalle,$piso,$localidad,$codigoProvincia,
         * $codigoPais,$codigoPostal, $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta,$iccTarjeta,
         * $idServicio,$iccNuevo,$donante,$telefono,$tipoAbono,$fechaPortabilidad

        0       1       2           3       4                  5        6               7           8           9
        'ID', 'NOMBRE','APELLIDOS','DNI','DOCUMENTO_URL','DOCUMENTO','TIPO_DOCUMENTO','DIRECCION','LOCALIDAD','PROVINCIA',
        10          11        12    13          14      15  16      17          18          19              20
        'COMUNIDAD','IBAN','SWIFT','ID_EMPRESA','CP','FIJO','MOVIL','EMAIL','FECHA_ALTA','FECHA_MODIFICA','NOTAS',
         21     22      23                      24              25                  26              27
        'BAJA','BANCO','ID_CONSENTIMIENTO','ID_TIPO_CLIENTE','FECHA_NACIMIENTO','DIRECCION_BANCO','NACIONALIDAD');


         */
        switch (intval($tipo_doc)){
            case 1:
                $tipo_doc=TIPO_DOC_MASMOVIL_1;
                break;
            case 2:
                $tipo_doc=TIPO_DOC_MASMOVIL_2;
                break;
            case 3:
                $tipo_doc=TIPO_DOC_MASMOVIL_3;
                break;
            case 4:
                $tipo_doc=TIPO_DOC_MASMOVIL_4;
                break;
        }

//        $donante = consultar();
        $donante="401";

        if($modalidad=='prepago')
            $modalidad="1";
        else
            $modalidad="0";


        $codSolicitud = AltaTecnica::addNuevaPortabilidadMasMovil(
            $cliente[1]." ".$ape1 ." ". $ape2, $cliente[1],   $tipo_cli,   $tipo_doc,  $cliente[3], $cliente[1],      $ape1,      $ape2,      $cliente[15],
            $cliente[16],   $cliente[15], $cliente[17], $cliente[15],     $cliente[7],  "", "", $cliente[9],    substr($cliente[14],0,2),
            $cliente[27],   $cliente[14],   $cliente[1]." ".$ape1 ." ". $ape2,   $cliente[22],  substr($cliente[11],4,4),
            substr($cliente[11],8,4),substr($cliente[11],12,2),substr($cliente[11],14,10),
            $iccOrigen,   $tarifa,    $icc,     $donante, $num_porta, $modalidad,  $util->sumasdiasemana(date('Ymd'),2,"Ymd"));

        $aItems = array();
        $aItem = array(
            'transactionId' => $codSolicitud->transactionId,
            'activationCode' => $codSolicitud->activationCode,  //   ok = OK-001
            'activateDescription' => $codSolicitud->activateDescription
        );

        array_push($aItems, $aItem);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($aItems);

        if($codSolicitud->activationCode=='OK-001')
            $altaCorrecta=true;

    }

    if ($altaCorrecta==true){
        if($tipo==3) {
            $campos = array('ID_CLIENTE','ID_EMPRESA',          'FECHA_SOLICITUD',      'TIPO',
                'DONANTE','ICC','DC','TARIFA','MODALIDAD_ORIGEN','NUMERO_PORTAR','FIRMA','TIPO_ACCESO','TIPO_DOC','TIPO_TITULAR');

            $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'),$tipo,
                $donante, $icc, $dc, $tarifa, $modalidad, $num_porta, $firma,1,$tipo_doc,$tipo_cli);
        } else {
            $campos = array('ID_CLIENTE','ID_EMPRESA', 'FECHA_SOLICITUD',      'TIPO','NOMBRE_TITULAR',    'TIPO_TITULAR',
                'CIF_TITULAR','DIR_TITULAR','REGION_TITULAR','PROV_TITULAR','POBLACION_TITULAR','CP_TITULAR','TIPO_DOC',
                'DONANTE','HORARIO','NUMERO_PORTAR','FIRMA','TIPO_ACCESO','TARIFA','ID_OPERADOR','COD_SOLICITUD');

            $values = array($id_cliente, $_SESSION['REVENDEDOR'],$util->hoy('fecha'), $tipo, $tit_n." ". $tit_a,$tipo_cli,
                $tit_dni,       $tit_dir,    $tit_region,     $tit_prov,     $tit_loc,           $tit_cp,   $tipo_doc,
                $donante, $hora_porta, $num_porta,    $firma, $tipo_acceso, $tarifa, $proveedor_movil, $codSolicitud);
        }

        $id = $util->insertInto('portabilidades', $campos, $values);

    }

} else {
    echo "nose";
    die();
}

//function imprimir($codSolicitud){
//    header('../servicios/airenetworks/obtener-documento.php?codSolicitud='.$codSolicitud.'&tipo=PORTABILIDAD');
//}


?>