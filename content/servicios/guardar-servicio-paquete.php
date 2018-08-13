<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 17/07/2018
 * Time: 11:50
 */
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../config/def_tablas.php');
require_once ('../../clases/Servicio.php');

$util = new util();

check_session(3);
date_default_timezone_set('Etc/UTC');

error_reporting(E_ALL);
ini_set('display_errors', 1);


// todo: --------------------------------------------
// cuando el cliente es creado por primera vez
// todo: --------------------------------------------

if(
    (isset($_POST['oper']) && $_POST['oper'] == 'edit')
    &&
    (isset($_POST['id']) && $_POST['id'] != '')
)
{

    $romperPaquete=($_POST['romper-paquete']);

    $idContrato=$util->cleanstring($_POST['idContrato']);
    $idLinea=$util->cleanstring($_POST['idLinea']);
    $id=$util->cleanstring($_POST['id']);
    $tipo = $util->cleanstring($_POST['tipo']);
    $servicio = $util->cleanstring($_POST['servicio']);
    $fechaCambio=$util->cleanstring($_POST['fecha-baja']);
    @$atributos=$_POST['atributo'];


    //Si el servicio proviende de un contrato
    if(isset($_POST['idContrato']))
    {
       // Llamada a la rutina compleja actualizar servicio de un contrato
        if($romperPaquete!='on')
         Servicio::actualizarServicioPaqueteContrato($idContrato,$idLinea,$id,$tipo,$servicio,$atributos,$fechaCambio);
        else
            Servicio::romperPaquete($idContrato,$idLinea,$servicio,$fechaCambio);

    }





} else{
    echo "nose";
    die();
}


function buscarContratosConServicio($idServicio)
{
    /*
     * SELECT *
FROM contratos,contratos_lineas
WHERE contratos.id=contratos_lineas.ID_CONTRATO
AND contratos.ID_EMPRESA=1 AND contratos_lineas.ID_TIPO=2 AND contratos_lineas.ID_ASOCIADO=24;
*/
    $util = new util();
    return $util->selectWhere3('contratos,contratos_lineas',
        array("contratos_lineas.id,contratos_lineas.id_contrato"),
        "contratos_lineas.estado=1 AND contratos.id=contratos_lineas.id_contrato AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos_lineas.id_tipo=2 AND contratos_lineas.id_asociado=".$idServicio);
}
function _redirect($hash) {

    $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    if($HTTP_REFERER === null)
        die("Invalid Referer. Output Message: {$hash}");

    header("Location: {$HTTP_REFERER}{$hash}");
    exit;
}



?>