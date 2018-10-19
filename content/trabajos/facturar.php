<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');
include_once('../../config/util.php');
include_once('../../clases/Orden.php');
include_once('../../clases/Factura.php');
include_once('../../clases/Contrato.php');
include_once('../../clases/Servicio.php');
require_once('../../pdf/tcpdf/tcpdf.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();

$idLineaOrdenFacturacion=$_GET['idLinea'];
$idContrato=$_GET['idContrato'];

$detallesFacturables=Orden::getCabeceraFacturacion($idLineaOrdenFacturacion);
$datos=Contrato::getClienteDatos($idContrato,$_SESSION['REVENDEDOR']);
var_dump($datos);
if($datos!=null)
{
    $idCliente=$datos[0]['ID'];
    $total=0;
    $importeTotal=0;

    for($i=0;$i<count($detallesFacturables);$i++)
    {
        $id=$detallesFacturables[$i]['id'];
        $idOrden=$detallesFacturables[$i]['id_orden'];
        $lineas=Orden::getLineasFacturacion($id);


        $idFactura=Factura::setNuevaFactura("",$_SESSION['REVENDEDOR'],"21","0",$idCliente);


        for($j=0;$j<count($lineas);$j++)
        {

            $importe=$lineas[$j]['IMPORTE'];
            $impuesto=$lineas[$j]['IMPUESTO'];
            $servicio=$lineas[$j]['ID_SERVICIO'];
            $servicios=Servicio::getDetallesServicio($servicio);

            $total=$total+floatval($importe);
            echo "el total es $total";

            $concepto=$servicios[0]['NOMBRE'];
            Factura::setNuevaLineaFactura($idFactura,$importe,$impuesto,$concepto);
        }

        $importeTotal=($total*$impuesto/100)+$total;
        Factura::setImporteTotal($idFactura,$total,$impuesto,"",$importeTotal);
        Orden::cambiarEstadoOrdenFacturable($id,ID_ORDEN_FACTURADA);
        Orden::cambiarEstadoOrden($idOrden,ID_ORDEN_FACTURADA);

    }

}







?>
