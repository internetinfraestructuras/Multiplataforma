<?php
include ("bloquedeseguridad.php");
include("funciones.php");
require('fpdf.php');


#
$data[] = array('num'=>1, 'mes'=>'Enero');
#
$data[] = array('num'=>2, 'mes'=>'Febrero');


$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$titles = array('num'=>'<b>Numero</b>', 'mes'=>'<b>Mes</b>');
$pdf->ezTable($data,$titles,'',$options );




$pdf->Cell(40,10,'Este es un ejemplo de creación de un documento PDF con PHP');
$pdf->Output();

?>