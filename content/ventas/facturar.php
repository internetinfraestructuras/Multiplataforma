<?php
require_once ('../../pdf/fpdf.php');

if (!isset($_SESSION)) {
    @session_start();
}



$logo=$_SESSION['LOGO'];

$pdf = new FPDF('P','mm','A4');
//add new page
$pdf->AddPage();
//output the result
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )
$pdf->Image($logo,0,0,40);
$pdf->Cell(130 ,40,'EMPRESA NOMBRE',0,0);
$pdf->Cell(59 ,24,'FACTURA #00001',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(130 ,5,'C/PRUEBAS',0,0);
$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->Cell(130 ,5,'JEREZ,11408',0,0);
$pdf->Cell(25 ,5,'FECHA',0,0);
$pdf->Cell(34 ,5,'[dd/mm/yyyy]',0,1);//end of line

$pdf->Cell(130 ,5,'TELEFONO',0,0);
$pdf->Cell(25 ,5,'Factura#',0,0);
$pdf->Cell(34 ,5,'[1234567]',0,1);//end of line

$pdf->Cell(130 ,5,'Fax [+12345678]',0,0);
$pdf->Cell(25 ,5,'CLIENTE.ID',0,0);
$pdf->Cell(34 ,5,'[1234567]',0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//billing address
$pdf->Cell(100 ,5,'PARA',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'CLIENTE DEMO',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'PRADONET',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'C/FALSA',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'950000001',0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(130 ,5,'DESCRIPCIÓN',1,0);
$pdf->Cell(25 ,5,'IMPUESTOS',1,0);
$pdf->Cell(34 ,5,'TOTAL',1,1);//end of line

$pdf->SetFont('Arial','',12);

//Numbers are right-aligned so we give 'R' after new line parameter

$pdf->Cell(130 ,5,'PAQUETE 1',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'3,250',1,1,'R');//end of line

$pdf->Cell(130 ,5,'SERVICIO',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'1,200',1,1,'R');//end of line

$pdf->Cell(130 ,5,'PRODUCTO',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'1,000',1,1,'R');//end of line

//summary
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Subtotal',0,0);
$pdf->Cell(4 ,5,'€',1,0);
$pdf->Cell(30 ,5,'4,450',1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Impuestos',0,0);
$pdf->Cell(4 ,5,'€',1,0);
$pdf->Cell(30 ,5,'0',1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Impuestos',0,0);
$pdf->Cell(4 ,5,'€',1,0);
$pdf->Cell(30 ,5,'21%',1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Total',0,0);
$pdf->Cell(4 ,5,'€',1,0);
$pdf->Cell(30 ,5,'4,450',1,1,'R');//end of line
$pdf->Output();


?>