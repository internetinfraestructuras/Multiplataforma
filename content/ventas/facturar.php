<?php
error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');
require_once('../../pdf/tcpdf/tcpdf.php');

if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();

$lineas= $util->selectWhere3('contratos_lineas,contratos_lineas_tipo,contratos,estados_contratos',
    array("contratos_lineas.id","contratos_lineas_tipo.nombre as Tipo","contratos_lineas_tipo.id","contratos_lineas.id_asociado","contratos_lineas.pvp","estados_contratos.nombre as nombrecontrato","estados_contratos.id as idestado","contratos_lineas.fecha_alta","contratos_lineas.fecha_baja"),
    "contratos.id=contratos_lineas.id_contrato 
            and contratos_lineas_tipo.id=contratos_lineas.id_tipo
            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos.id=".$_GET['idContrato']." AND contratos_lineas.estado!=2 AND estados_contratos.id=contratos_lineas.estado");


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('REVENDEDOR');
$pdf->SetTitle('FACTURA');
$pdf->SetSubject('FACTURA MES CURSO');
$pdf->SetKeywords('FACTURA CLIENTE DEMO');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 14, '', true);
$pdf->AddPage();
$content = '';
$reporte="PRUEBAS";

//CABECERA DEL LISTADO
$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
$datosCliente = 'DATOS CLIENTE';
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 10, 30, true, 0, false, true, 0, 'T', false);
$datosCliente = 'FACTURA Nº XXX';
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 120, 30, true, 0, false, FALSE, 0, 'T', false);

$tabla="<table cellspacing=\"0\" cellpadding=\"1\" border=\"1\">
                        <tr>
                        <th>REF.</th><th>CONCEPTO</th><th>IMPORTE</th><TH>IMPUESTOS</TH><TH>PVP</TH>
                        </tr>";


$total=0;
for($i=0;$i<count($lineas);$i++)
{
    $id=$lineas[$i][0];
    $tipo=$lineas[$i][1];
    $idTipo=$lineas[$i][2];
    $idAsociado=$lineas[$i][3];
    $pvp=$lineas[$i][4];
    $estado=$lineas[$i][5];
    $idEstado=$lineas[$i][6];
    $alta=$lineas[$i][7];
    $baja=$lineas[$i][8];

    if($idEstado!=3)
        $totalContrato+=$pvp;


    $total+=$pvp;

    $tipo=strtolower($tipo);

    if($idTipo!=3)
        $listado= $util->selectWhere3($tipo, array("ID","nombre"),  $tipo.".id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);
    else
        $listado= $util->selectWhere3($tipo.",almacenes", array("productos.ID","numero_serie"),  "almacenes.id=productos.id_almacen AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);

    $idServicioTipo=$listado[0][2];

    $nombre=$listado[0][1];
    $tabla.="<tr><td>$id</td><td>$nombre</td><td>$pvp</td><td>21%</td><td>$pvp</td></tr>";

}
$tabla.="<tr><td colspan=\"3\">TOTAL</td><td>21%</td><td>$total</td></tr>";
$tabla.="</table>";
$pdf->writeHTML($tabla);

$pdf->output('Reporte.pdf', 'I');

$totalContrato=0;






?>