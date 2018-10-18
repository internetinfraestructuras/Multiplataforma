<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
require_once ('../../clases/masmovil/MasMovilAPI.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


$masMovil=new MasMovilAPI();
$cdr=$masMovil->getCDRMesActualDias($_GET['msidn']);

$tableCDR="<br><br><br><h3>Listado:</h3>
<table border=\"1\" style=\"text-align: center;padding:5px;font-size: 8px;\"><tr style=\"background-color: #9e9e9e\">
<th>TIPO</th><th>FECHA</th><th>HORA</th><th>ORIGEN</th><th>DESTINO</th><th>DURACIÓN</th><th>TRAFICO</th><th>VELOCIDAD</th><th>DETALLES</th></tr>";

$i=0;
//foreach($cdr->getLineas() as $linea)
for($i=0;$i<count($cdr->getLineas());$i++)
{
    for($j=0;$j<count($cdr->getLineas()[$i]);$j++)
    {
        $tipo = $cdr->getLineas()[$i][$j]->getTipo();
        $origen = $cdr->getLineas()[$i][$j]->getOrigen();
        $destino = $cdr->getLineas()[$i][$j]->getDestino();
        $tarifa = $cdr->getLineas()[$i][$j]->getTarifa();
        $fecha = $cdr->getLineas()[$i][$j]->getFecha();
        $hora = $cdr->getLineas()[$i][$j]->getHora();
        $tiempo = $cdr->getLineas()[$i][$j]->getTiempo();
        $trafico = $cdr->getLineas()[$i][$j]->getTrafico();
        $velocidad = $cdr->getLineas()[$i][$j]->getVelocidad();
        $detalle = $cdr->getLineas()[$i][$j]->getDetalle();

        $tableCDR.="<tr><td>$tipo</td><td>$fecha</td><td>$hora</td><td>$origen</td><td>$destino</td><td>$tiempo</td><td>$trafico</td><td>$velocidad</td><td>Detalles</td></tr>";
    }

}
$tableCDR.="</table>";

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('CONTRATO DE CLIENTE');
$pdf->SetSubject('LISTADO DE LLAMADAS');
$pdf->SetKeywords('CONTRATO DE CLIENTE');
$pdf->SetHeaderData($file="../..".$_SESSION['LOGO'],"10px", "  REGISTRO DE LLAMADAS ", array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();

$datosCliente = 'DATOS DE LA EMPRESA<BR>
C/ Pruebas 123<br>
11400 Jerez de la Frontera<br>
España<br>
CIF<br>';
$pdf->MultiCell(0, 0, $datosCliente, 0, 'J', false, 1, 10, 11, true, 0, true, true, 0, 'T', false);


$datosCliente = '<B>DATOS CLIENTE:</B><br>
                <b>Nombre:</b>'.$cliente." ".$apellidos."<br/>
                <b>Dirección:</b>".$direccion."<br/>"
    ."<b>Localidad:</b>$localidad<br/>
                <b>Provincia:</b>$provincia<br/>
                <b>Teléfono:</b>$fijo<br/>";
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 100, 11, true, 0, true, FALSE, 0, 'T', false);


$pdf->writeHTML($tableCDR);


$pdf->output('Reporte.pdf', 'I');




?>
