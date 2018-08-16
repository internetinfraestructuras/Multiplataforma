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
//require_once('../../config/def_tablas.php');
require_once('../../clases/Contrato.php');

$util = new util();
$contrato = new Contrato();

check_session(3);
date_default_timezone_set('Etc/UTC');


if (isset($_POST['action']) && $_POST['action'] == 'contrato') {

    $borrador=$_POST['id_borrador'];
    $id_campana=$_POST['id_campana'];
    $dto=$_POST['dto'];
    $dias=$_POST['dias'];
    $hasta=$_POST['hasta'];
    $pack=$_POST['pack'];
    $extras=$_POST['extras'];
    $firma=$_POST['firma'];
    $lineas=$_POST['lineas'];
    $cliente=$_POST['cliente'];
    $permanencia=$_POST['permanencia'];

    $idContrato = $contrato->setNuevoContrato($cliente, $permanencia,3);
    $idDocumento = $contrato->setNuevoDocumento($idContrato, 1,'', $firma);

    foreach ($lineas as $linea){
        $tipo=2;
        if($linea[5]=='p') $tipo=2;

        $fecha = date('Y-m-d');
        $nuevafecha = strtotime ( '+'.intval($linea[9]).' month' , strtotime ( $fecha ) ) ;
        $permanLinea = date ( 'Y-m-d' , $nuevafecha );

        $r=$util->selectWhere('servicios',array("PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO"),' id = '.$linea[0]);
        $row = mysqli_fetch_array($r);

        $enpack=0;
        if($linea[5]=='p')
            $enpack=1;

        $idLinea = $contrato->setNuevaLineaContrato($tipo, $linea[0], $idContrato, $row[0], $row[1], $row[2],
            $linea[2], $permanLinea, 3, null, $enpack, $linea[6], $linea[7]);

        // damos el producto de alta
        $contrato->setNuevoProductoContrato($idLinea, $linea[8], 3);

        $r2=$util->selectWhere('servicios_atributos',array("ID_TIPO_ATRIBUTO","VALOR","VALOR_TECNICO"),' ID_SERVICIO = '.$linea[0]);

        while($row2 = mysqli_fetch_array($r2)) {

            // agregamos la linea de detalle al contrato
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], $row2[0], $row2[1], 3, null);


        }

    }

    $util->insertInto('contratos_campanas', array('ID_CONTRATO','ID_CAMPANA','DTO','DTO_DIAS','DTO_HASTA'),
        array($idContrato,   $id_campana, $dto, $dias,     $hasta));


} else {
    echo "nose";
    die();
}

?>