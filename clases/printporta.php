<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 07/08/2018
 * Time: 9:39
 */

include('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);


$pdf->Image('http://ftth.internetinfraestructuras.es/img/logo.png',10,8,33);
// Arial bold 15
$pdf->SetFont('Arial','B',15);
// Movernos a la derecha
$pdf->Cell(80);
// Título
$pdf->Cell(30,10,'Title',1,0,'C');
// Salto de línea
$pdf->Ln(20);


$pdf->Cell(40,10,'¡Hola, Mundo!');
$pdf->Output();

