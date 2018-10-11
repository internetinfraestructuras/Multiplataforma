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
include_once('../../clases/Contrato.php');
include_once('../../clases/Orden.php');
include_once('../../clases/Servicio.php');
include_once('../../clases/AltaTecnica.php');
include_once('../../clases/Clientes.php');
$tel = new AltaTecnica();
$cli = new Clientes();

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
$cifSuperUsuario = 'B45782687';

$util = new util();
$contrato = new Contrato();
$servicio = new Servicio();

check_session(3);
date_default_timezone_set('Etc/UTC');
//
//error_reporting(E_ALL);
//ini_set("display_errors", 0);

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

    $idOrden = Orden::crearOrdenTrabajo($idContrato, null);

    $enpack = 0;
    $productoyaagregado = array();
    $aFijos = array();
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
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], ATRIBUTO_TELEFONO_FIJO, $linea[7], 3, null);

            // damos el producto de alta
            if (intval($linea[8]) > 0 && !in_array(intval($linea[8]), $productoyaagregado)) {
                $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
                array_push($productoyaagregado, intval($linea[8]));
            }
        }

        if (intval($linea[1]) == 3) {
            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], ATRIBUTO_TELEFONO_MOVIL, $linea[7], 3, null);
            $idLineaAtributoNumeroTelefono=$idLinea2;

            // damos el producto de alta
            if (intval($linea[8]) > 0 && !in_array(intval($linea[8]), $productoyaagregado)) {
                array_push($productoyaagregado, intval($linea[8]));
                $contrato->setNuevoProductoContrato($idLinea2, $linea[8], 3);
            }

        }


        $r2 = $util->selectWhere('servicios_atributos', array("ID_TIPO_ATRIBUTO", "VALOR"), ' ID_SERVICIO = ' . $linea[0], '', '');

        while ($row2 = mysqli_fetch_array($r2)) {

            $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], $row2[0], $row2[1], 3, null);


            // damos el producto de alta
            if (!in_array(intval($linea[8]), $productoyaagregado)) {
                $contrato->setNuevoProductoContrato($idLinea2, intval($linea[8]), 2);
                array_push($productoyaagregado, intval($linea[8]));
            }

            /* Alta Tecnica Telefonía Fija */
            if (intval($linea[1]) == ID_SERVICIO_VOZIP && intval($linea[7]) > 0) {

                // busco el paquete destino y el grupo recarga del servicio, me devuelve array 2 tuplas
                $GrupoyDestino = $servicio->getGrupoRecargayPaqueteDestino($linea[0]);

                // el nombre de grupo es texto y el paquete destino numerico
                // como no puedo controlar el orden en el que me lo devuelve
                // compruebo cual es el paquete destino pasandolo a entero

                if(intval($GrupoyDestino[0])>0) {
                    $paqueteDestino = $GrupoyDestino[0];
                    $nombreGrupoRecarga = $GrupoyDestino[1];
                } else {
                    $paqueteDestino = $GrupoyDestino[1];
                    $nombreGrupoRecarga = $GrupoyDestino[0];
                }

                try {
                    if (!in_array(intval($linea[7]), $aFijos)) {
                        $troncal = $tel->addNuevoFijo($cifSuperUsuario, $cifCliente, $nombreCliente,
                            $direccion, $email, $nombreGrupoRecarga, $paqueteDestino, $linea[7]);
                        array_push($aFijos, intval($linea[7]));

                        $idLinea2 = $contrato->setNuevaLineaDetalles($idLinea, $linea[1], $linea[0], ID_ATRIBUTO_TRONCAL, $troncal, 3, null);
                    }
                } catch (Exception $e) {
                    $util->write_log("alta tecnica telefonia-> " . $e->getMessage());
                }
            }


        }

        /* --- Alta tecnica telefonia movil --- */
        // compruebo que el tipo de servicio es 3 (moviles) y que es numero nuevo ($linea[6]==1)

        if (intval($linea[1]) == 3 && intval($linea[6]) == 1) {
            $datosCliente = $cli->getClienteAltaMasMovil($cliente);
            $datosCliente = mysqli_fetch_array($datosCliente);

            $codBanco = substr($datosCliente[13], 4, 4);
            $oficina = substr($datosCliente[13], 8, 4);
            $dc = substr($datosCliente[13], 12, 2);
            $ccc = substr($datosCliente[13], 14, 10);
            $codProv = substr($datosCliente[11], 0, 2);

            $icc ='';

            if(isset($linea[10]))
                $icc = $linea[10];

            $idServicio = $linea[0];

            $res = AltaTecnica::addNuevaLineaMasMovil($datosCliente[0] . " " . $datosCliente[1],
                $datosCliente[0], $datosCliente[2], $datosCliente[3], $datosCliente[0], $datosCliente[4],
                $datosCliente[5], $datosCliente[4], $datosCliente[6], $datosCliente[7], $datosCliente[8], $codProv,
                $datosCliente[10], $datosCliente[11], $datosCliente[0] . " " . $datosCliente[1], $datosCliente[12],
                $codBanco, $oficina, $dc, $ccc, $icc, $idServicio);

            Orden::setresultadoInstalacionLineaOrden($idLineaAtributoNumeroTelefono, $util->hoy('fechahora'), $_SESSION['USER_ID'], '', '', '',
                $res->activateDescription, '', '');

            $serviciosMasmovil = $util->selectWhere3('servicios',array('ID'), 'ID_PROVEEDOR='.ID_PROVEEDOR_MASMOVIL);
            foreach ($serviciosMasmovil as $servMas){
                if($servMas[0]==$idServicio) {
                    $util->consulta("INSERT INTO altas_mas_movil (ID_LINEA_DETALLE, ESTADO, ICC, ID_EMPRESA) VALUES ($idLineaAtributoNumeroTelefono, 1,'".$icc."',".$_SESSION['REVENDEDOR']."); ");
                    break;
                }

            }


//            if(in_array($idServicio, $serviciosMasmovil['ID']))


//                if($res['activationCode']==OPERACION_OK_MASMOVIL){
//
//                }
        }
        /* --- fin alta telefonia movil ---*/

        // creamos las lineas de la orden de trabajo ---------------------------------------

        Orden::crearLineaOrden($idOrden, 1, $linea[8], $idLinea2);
        $contrato->setProductoAlta($linea[8], 2);

        // fin orden de trabajo ----------------------------------------------------

    }
    if (intval($id_campana) > 0)
        $util->insertInto('contratos_campanas', array('ID_CONTRATO', 'ID_CAMPANA', 'DTO', 'DTO_DIAS', 'DTO_HASTA'),
            array($idContrato, $id_campana, $dto, $dias, $hasta));


    echo $idContrato;

} else {
    echo "nose";
    die();
}
?>