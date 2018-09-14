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
require_once('../../clases/Contrato.php');
require_once('../../clases/Orden.php');
require_once('../../clases/Servicio.php');
require_once('../../clases/AltaTecnica.php');
$tel = new AltaTecnica();


$cifSuperUsuario = 'B45782687';

$util = new util();
$contrato = new Contrato();
$orden = new Orden();
$servicio = new Servicio();

check_session(3);
date_default_timezone_set('Etc/UTC');


if (isset($_POST['action']) && $_POST['action'] == 'contrato') {

    $borrador = $_POST['id_borrador'];
    $id_campana = $_POST['id_campana'];
    $dto = $_POST['dto'];
    $dias = $_POST['dias'];
    $hasta = $_POST['hasta'];
    $pack = $_POST['pack'];
    $extras = $_POST['extras'];
    $firma = $_POST['firma'];
    $lineas = $_POST['lineas'];
    $cliente = $_POST['cliente'];
    $permanencia = $_POST['permanencia'];
    $idpaquete = $_POST['id_paquete'];
    $pack = $_POST['preciopaquete'];

    /* para la telefonia fija*/
    $cifCliente = $_POST['cif'];
    $nombreCliente = $_POST['nombreapellidos'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];

    $idContrato = $contrato->setNuevoContrato($cliente, $permanencia, 3);
    $idDocumento = $contrato->setNuevoDocumento($idContrato, 1, '', $firma);

    $idOrden = $orden->crearOrdenTrabajo($idContrato, null);

    $enpack = 0;
    $productoyaagregado = 0;
    $nombreGrupoRecarga = "";

    foreach ($lineas as $linea) {

        $fecha = date('Y-m-d');
        $nuevafecha = strtotime('+' . intval($linea[9]) . ' month', strtotime($fecha));
        $permanLinea = date('Y-m-d', $nuevafecha);

        $r = $util->selectWhere('servicios', array("PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO"), ' id = ' . $linea[0]);
        $row = mysqli_fetch_array($r);

        if ($enpack == 0 && intval($idpaquete) > 0) {
            $idLinea = $contrato->setNuevaLineaContrato(1, $idpaquete, $idContrato, $row[0], $row[1], $row[2], $pack, $permanencia, 3, null, null);
            $enpack = 1;
        }


        if ($linea[5] == 'e') {
            $idLinea = $contrato->setNuevaLineaContrato(2, $linea[0], $idContrato, $row[0], $row[1], $row[2],
                $linea[2], $permanLinea, 3, null, $enpack, $linea[6], $linea[7]);
        }

        // agregamos la linea de detalle al contrato
        if (intval($linea[1]) == 2) {
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], 45, $linea[7], 3, null);
            // damos el producto de alta
            $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
        }

        if (intval($linea[1]) == 3) {
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], 48, $linea[7], 3, null);
            // damos el producto de alta
            $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
        }


        $r2 = $util->selectWhere('servicios_atributos', array("ID_TIPO_ATRIBUTO", "VALOR"), ' ID_SERVICIO = ' . $linea[0], '', '');

        while ($row2 = mysqli_fetch_array($r2)) {

            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], $row2[0], $row2[1], 3, null);

            // damos el producto de alta
            if ($linea[8] != $productoyaagregado) {
                $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 2);
                $productoyaagregado = $linea[8];
            }

            /* Alta Tecnica Telefonía Fija */
            if (intval($linea[1]) == 2 && intval($linea[7]) > 0 && intval($row2[1]) > 0) {

                // busco el paquete destino y el grupo recarga del servicio, me devuelve array 2 tuplas
                $GrupoyDestino = $servicio->getGrupoRecargayPaqueteDestino($linea[0]);
                $paqueteDestino = $GrupoyDestino[0];
                $nombreGrupoRecarga = $GrupoyDestino[1];

                try {

                    echo $tel->addNuevoFijo($cifSuperUsuario, $cifCliente, $nombreCliente,
                        $direccion, $email, $nombreGrupoRecarga, $paqueteDestino, $linea[7]);

                } catch (Exception $e) {
                    echo 'Excepción capturada: ', $e->getMessage(), "<br>";
                }
            }

            /* --- Alta tecnica telefonia movil --- */
            // compruebo que el tipo de servicio es 3 (moviles) y que es numero nuevo ($linea[6]==1)
            if (intval($linea[1]) == 3 && intval($linea[6]) == 1) {

            }

            /* --- fin alta telefonia movil ---*/

        }

        // creamos las lineas de la orden de trabajo ---------------------------------------

        $orden->crearLineaOrden($idOrden, 1, $linea[8], $idLinea2);
        $contrato->setProductoAlta($linea[8], 2);

        // fin orden de trabajo ----------------------------------------------------

    }

    $util->insertInto('contratos_campanas', array('ID_CONTRATO', 'ID_CAMPANA', 'DTO', 'DTO_DIAS', 'DTO_HASTA'),
        array($idContrato, $id_campana, $dto, $dias, $hasta));


    echo $idContrato;

} else {
    echo "nose";
    die();
}

?>