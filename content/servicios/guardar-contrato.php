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
require_once('../../clases/Orden.php');

$util = new util();
$contrato = new Contrato();
$orden= new Orden();

check_session(3);
date_default_timezone_set('Etc/UTC');


if (isset($_POST['action']) && $_POST['action'] == 'contrato') {

    $borrador   = $_POST['id_borrador'];
    $id_campana = $_POST['id_campana'];
    $dto        = $_POST['dto'];
    $dias       = $_POST['dias'];
    $hasta      = $_POST['hasta'];
    $pack       = $_POST['pack'];
    $extras     = $_POST['extras'];
    $firma      = $_POST['firma'];
    $lineas     = $_POST['lineas'];
    $cliente    = $_POST['cliente'];
    $permanencia= $_POST['permanencia'];
    $idpaquete  = $_POST['id_paquete'];
    $pack       = $_POST['preciopaquete'];

    $idContrato = $contrato->setNuevoContrato($cliente, $permanencia, 3);
    $idDocumento = $contrato->setNuevoDocumento($idContrato, 1, '', $firma);

    $idOrden = $orden->crearOrdenTrabajo($idContrato,null);

    $enpack = 0;
    $productoyaagregado=0;

    foreach ($lineas as $linea) {

        $fecha = date('Y-m-d');
        $nuevafecha = strtotime('+' . intval($linea[9]) . ' month', strtotime($fecha));
        $permanLinea = date('Y-m-d', $nuevafecha);

        $r = $util->selectWhere('servicios', array("PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO"), ' id = ' . $linea[0]);
        $row = mysqli_fetch_array($r);

        if ($enpack == 0)
        {
            $idLinea = $contrato->setNuevaLineaContrato(1, $idpaquete, $idContrato, $row[0], $row[1], $row[2], $pack, $permanencia, 3, null, null);
            $enpack = 1;
        }


        if ($linea[5] == 'e') {
            $idLinea = $contrato->setNuevaLineaContrato(2, $linea[0], $idContrato, $row[0], $row[1], $row[2],
                $linea[2], $permanLinea, 3, null, $enpack, $linea[6], $linea[7]);
        }

        // agregamos la linea de detalle al contrato
        if (intval($linea[1]) == 2 ) {
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], 45, $linea[7], 3, null);
            // damos el producto de alta
            $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
        }

        if (intval($linea[1]) == 3) {
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], 48, $linea[7], 3, null);
            // damos el producto de alta
            $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
        }

        $r2 = $util->selectWhere('servicios_atributos', array("ID_TIPO_ATRIBUTO", "VALOR", "VALOR_TECNICO"), ' ID_SERVICIO = ' . $linea[0],'','');

        while ($row2 = mysqli_fetch_array($r2)) {
            $esnuevo = true;
            $idLinea2=$contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], $row2[0], $row2[1], 3, null);

            // damos el producto de alta
            if($esnuevo && $linea[8]!=$productoyaagregado) {
                $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
                $esnuevo=false;
                $productoyaagregado=$linea[8];
            }
        }

        $orden->crearLineaOrden($idOrden, 1, $linea[8],$idLinea2);
        $contrato->setProductoAlta($linea[8], 2);
    }

    $util->insertInto('contratos_campanas', array('ID_CONTRATO', 'ID_CAMPANA', 'DTO', 'DTO_DIAS', 'DTO_HASTA'),
        array($idContrato, $id_campana, $dto, $dias, $hasta));

    echo $idContrato;

} else {
    echo "nose";
    die();
}


//sippstnuser add 5 9 1 mgid 1 username 485754432A15AE9C password bniea4089tEAd telno 485754432A15AE9C


/*
*
*
0:"42"      servicio
1:"3"       tipo
2:"5.32"    precio
3:1         cant
4:"TARIFA MASMOVIL1"    nombre
5:"p"       pack o extra
6:"0"       0 porta / 1 nuevo
7:"234"     numero telefono
8:"24"      id producto
9:"6"       permanencia meses
10:""18092018IPTVM1100000001""

*/

//        if($linea[5]=='p' && $enpack==0) {
//            $idLinea = $contrato->setNuevaLineaContrato(1, $idpaquete, $idContrato, $row[0], $row[1], $row[2],
//                $pack, $permanLinea, 3, null, $enpack, $linea[6], $linea[7]);
//            $enpack=1;
/*
 */
?>

