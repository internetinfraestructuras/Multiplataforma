<?php



include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
require_once ('../../clases/airenetwork/clases/CDR.php');

if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


$msidn=$_GET['numero'];

if(empty($_GET['anio']))
    $anio=date("Y");
else
    $anio=$_GET['anio'];
if(empty($_GET['mes']))
    $mes=date("m");
else
    $mes=$_GET['mes'];

$apiAire=new CDRAire();

$lsLlamadas=$apiAire->getDatosCDR($msidn,$anio,$mes,"MOVIL");

$lsSMS=$apiAire->getDatosCDR($msidn,$anio,$mes,"SMS");

$tableCDR="<br><br><br><h3>Listado llamadas:</h3>
<table border=\"1\" style=\"text-align: center;padding:5px;font-size: 8px;\"><tr style=\"background-color: #9e9e9e\">
<th>FECHA</th><th>ORIGEN</th><th>DESTINO</th><th>DESCRIPCION</th><th>TIEMPO(sg)</th><th>IMPORTE</th></tr>";

for($i=0;$i<count($lsLlamadas);$i++)
{
    $fecha=$lsLlamadas[$i]['fecha'];
    $origen=$lsLlamadas[$i]['origen'];
    $destino=$lsLlamadas[$i]['destino'];
    $descripcion=$lsLlamadas[$i]['descripcion'];
    $tiempo=$lsLlamadas[$i]['segundos'];
    $importe=$lsLlamadas[$i]['importe'];

    $tableCDR.="<tr><td>$fecha</td><td>$origen</td><td>$destino</td><td>$descripcion</td><td>$tiempo</td><td>$importe</td></tr>";
}

$tableCDR.="</table>";

if($lsSMS!=null)
{

    $tableCDR.="<br><br><br><h3>Listado SMS:</h3>
    <table border=\"1\" style=\"text-align: center;padding:5px;font-size: 8px;\"><tr style=\"background-color: #9e9e9e\">
    <th>FECHA</th><th>ORIGEN</th><th>DESTINO</th><th>DESCRIPCION</th><th>TIEMPO(sg)</th><th>IMPORTE</th></tr>";

    for($i=0;$i<count($lsSMS);$i++)
    {
        $fecha=$lsLlamadas[$i]['fecha'];
        $origen=$lsLlamadas[$i]['origen'];
        $destino=$lsLlamadas[$i]['destino'];
        $descripcion=$lsLlamadas[$i]['descripcion'];
        $tiempo=$lsLlamadas[$i]['segundos'];
        $importe=$lsLlamadas[$i]['importe'];

        $tableCDR.="<tr><td>$fecha</td><td>$origen</td><td>$destino</td><td>$descripcion</td><td>$tiempo</td><td>$importe</td></tr>";
    }

    $tableCDR.="</table>";
}


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$idContrato="1";
$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('CONTRATO DE CLIENTE');
$pdf->SetSubject('LISTADO DE LLAMADAS');
$pdf->SetKeywords('CONTRATO DE CLIENTE');
$pdf->SetHeaderData("","","", " LISTADO DE LLAMADAS MENSUAL A DÍA DE HOY".$idContrato, array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();


/*
$datosCliente = 'DATOS DE LA EMPRESA<BR>
C/ Pruebas 123<br>
11400 Jerez de la Frontera<br>
España<br>
CIF<br>';

$pdf->MultiCell(0, 0, $datosCliente, 0, 'J', false, 1, 10, 11, true, 0, true, true, 0, 'T', false);

/*
$datosCliente = '<B>DATOS CLIENTE:</B><br>
                <b>Nombre:</b>'.$cliente." ".$apellidos."<br/>
                <b>Dirección:</b>".$direccion."<br/>"
    ."<b>Localidad:</b>$localidad<br/>
                <b>Provincia:</b>$provincia<br/>
                <b>Teléfono:</b>$fijo<br/>";
$pdf->MultiCell(70, 50, $datosCliente, 0, 'J', false, 1, 100, 11, true, 0, true, FALSE, 0, 'T', false);


$pdf->writeHTML($tableCDR);

*/

//$pdf->output('Reporte.pdf', 'I');

echo $tableCDR;


?>
