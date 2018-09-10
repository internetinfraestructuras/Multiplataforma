<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');
require_once ('./../../clases/Portabilidad.php');
require_once('../../pdf/tcpdf/tcpdf.php');

if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();

$portabilidad=Portabilidad::getDatosPortabilidadPDF(1);

$titular=$portabilidad[0][0];
$cif=$portabilidad[0][1];
$direccion=$portabilidad[0][2];
$localidad=$portabilidad[0][3];
$provincia=$portabilidad[0][4];
$comunidad=$portabilidad[0][5];
$cp=$portabilidad[0][6];
$numero=$portabilidad[0][7];
$operador=$portabilidad[0][8];

$listado=$util->selectWhere3("textos_legales",array("texto,id_servicio"),"ubicacion='portaForm' AND id_empresa=".$_SESSION['REVENDEDOR']."");

$texto=$listado[0][0];

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('PORTABILIDAD');

$pdf->SetTitle('FORMULARIO DE PORTABILIDAD');
$pdf->SetSubject('FORMULARIO DE PORTABILIDAD');
$pdf->SetKeywords('FORMULARIO DE PORTABILIDAD');
$pdf->SetHeaderData("","","", " FORMULARIO DE PORTABILIDAD", array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();


//SUSTITUCIÓN DE VARIABLES
$direccion.=" ".$localidad.", ";
$direccion.=$provincia.", ";
$direccion.=$comunidad." ";
$direccion.=" CP:".$cp." ";
$logo="<img src='".$_SESSION['LOGO']."' style='width:150px';/>";

$texto=str_replace("{logo}",$logo,$texto);
$texto=str_replace("{nombreTitular}",$titular,$texto);
$texto=str_replace("{direccionTitular}",$direccion,$texto);
$texto=str_replace("{dniTitular}",$cif,$texto);
$texto=str_replace("{numeroPortar}",$numero,$texto);
$texto=str_replace("{operadorDonante}",$operador,$texto);
$texto=str_replace("{fecha}",date("d/m/y"),$texto);
$nombreCompleto=$nombre." ".$apellidos;
$pdf->writeHTML($texto);
$pdf->Image('images/image_demo.jpg', 15, 140, 75, 113, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);

//echo $texto;

$pdf->output('Reporte.pdf', 'I');




?>

