<?php
/*
error_reporting('0');
ini_set('display_errors', 1);*/

error_reporting(E_ALL);
ini_set("display_errors", 0);

include_once('../../pdf/utilPDF.php');
require_once ('./../../clases/Portabilidad.php');
require_once('../../pdf/tcpdf/tcpdf.php');

if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();
$portas=new Portabilidad();

$portabilidad = $portas->getDatosPortabilidadPDF($_REQUEST['idContrato'],$_REQUEST['tipo']);
$portabilidad=mysqli_fetch_array($portabilidad);
/*
 *
0 FECHA_SOLICITUD
1 NOMBRE_TITULAR
2 CIF_TITULAR
3 CP_TITULAR
4 DIR_TITULAR
5 NUMERO_PORTAR
6 FIRMA
7 ICC
8 MOVIL_PORTAR
9 MODALIDAD_ORIGEN
10 municipio
11 provincia
12 comunidad
13 NOMBRE
14 APELLIDOS
15 DNI
16 DIRECCION
17 IBAN
18 SWIFT
19 FIJO
20 MOVIL
21 EMAIL
22 BANCO
23 FECHA_NACIMIENTO
24 OPERADOR
25 TIPO CLIENTE
26 TIPO DOCUMENTO
27 MOD. DONANTE (tipo linea fijo)
28 TARIFA
 */
$fecha=$portabilidad;
$titular=$portabilidad[1];
$cif=$portabilidad[2];
$cp=$portabilidad[3];
$direccion=$portabilidad[4];
$numportar=$portabilidad[5];
$firma=$portabilidad[6];
$icc=$portabilidad[7];
$movilporta=$portabilidad[8];
$modalidad=$portabilidad[9];

$localidad=$portabilidad[10];
$provincia=$portabilidad[11];
$comunidad=$portabilidad[12];

$nomcli=$portabilidad[13];
$apecli=$portabilidad[14];
$dnicli=$portabilidad[15];
$dircli=$portabilidad[16];
$iban=$portabilidad[17];
$swift=$portabilidad[18];
$clifijo=$portabilidad[19];
$climovil=$portabilidad[20];
$email=$portabilidad[21];
$banco=$portabilidad[22];
$f_nacimiento=$portabilidad[23];
$opedonante=$portabilidad[24];
$tipocli=$portabilidad[25];
$tipodoc=$portabilidad[26];
$moddonante=$portabilidad[27];
$tarifa=$portabilidad[28];
$dc=$portabilidad[29];


$listado=$util->selectWhere3("textos_legales",array("texto,id_servicio"),"ubicacion='portaForm' AND id_empresa=".$_SESSION['REVENDEDOR']."");

$texto=$listado[0][0];

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetMargins(10, 30, 10);

$pdf->SetAuthor('PORTABILIDAD');

$pdf->SetTitle('FORMULARIO DE PORTABILIDAD');
$pdf->SetSubject('FORMULARIO DE PORTABILIDAD');
$pdf->SetKeywords('FORMULARIO DE PORTABILIDAD');
//$pdf->SetHeaderData("","","", " FORMULARIO DE PORTABILIDAD", array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();


//SUSTITUCIÓN DE VARIABLES
$direccion.=" ".$localidad.", ";
$direccion.=$provincia.", ";
$direccion.=$comunidad." ";
$direccion.=" CP:".$cp." ";
$logo=$_SESSION['LOGO'];

//$pdf->Image($file=$logo, 1, 1, 210, 30, '', '', '', 0, 200, '', false, false, 0, false, false, false);
$pdf->SetHeaderData($file="../../img/antena.png","10px", "", array(0,64,255), array(0,64,128));
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


$texto=str_replace("{nombreTitular}",$titular,$texto);
$texto=str_replace("{dniTitular}",$cif,$texto);
$texto=str_replace("{direccionTitular}",$direccion,$texto);
$texto=str_replace("{loctitular}",$localidad,$texto);
$texto=str_replace("{provtitular}",$provincia,$texto);
$texto=str_replace("{comunidadtitular}",$comunidad,$texto);
$texto=str_replace("{tipocliente}",$tipocli,$texto);
$texto=str_replace("{nombrecliente}",$nomcli,$texto);
$texto=str_replace("{tipodocumento}",$tipodoc,$texto);
$texto=str_replace("{dnicliente}",$dnicli,$texto);
$texto=str_replace("{direccioncliente}",$dircli,$texto);
$texto=str_replace("{loccliente}",$localidad,$texto);
$texto=str_replace("{provcliente}",$provincia,$texto);
$texto=str_replace("{email}",$email,$texto);
$texto=str_replace("{fechanacimiento}",$f_nacimiento,$texto);
$texto=str_replace("{icc}",$icc . " ". $dc,$texto);
$texto=str_replace("{numportar}",$numportar,$texto);
$texto=str_replace("{donante}",$opedonante,$texto);
$texto=str_replace("{modoacceso}",$moddonante,$texto);
$texto=str_replace("{tarifa}",$tarifa,$texto);
$texto=str_replace("{banco}",$banco,$texto);
$texto=str_replace("{iban}",$iban,$texto);
$texto=str_replace("{swift}",$swift,$texto);
$texto=str_replace("{localidadempresa}",$_SESSION['LOCALIDADEMPRESA'],$texto);

$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;

$texto=str_replace("{operadorDonante}",$operador,$texto);
$texto=str_replace("{fecha}",$fecha,$texto);

$pdf->writeHTML($texto);

$pdf->ImageSVG($file=$firma, 5, 215, 100, 50);

$pdf->output('Reporte.pdf', 'I');
?>

