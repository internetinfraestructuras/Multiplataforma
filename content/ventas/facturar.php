<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


$listado= $util->selectWhere3('contratos,empresas,clientes,municipios,provincias,comunidades,pais',
    array("contratos.id,empresas.nombre,clientes.nombre,clientes.apellidos,clientes.dni,clientes.direccion,municipios.municipio,provincias.provincia,comunidades.comunidad,clientes.cp,clientes.fijo,clientes.movil,pais.paisnombre,contratos.fecha_inicio,contratos.fecha_fin"),
    "contratos.id_empresa=empresas.id AND contratos.id_cliente=clientes.id  AND municipios.id=clientes.localidad AND provincias.id=clientes.provincia AND clientes.comunidad=comunidades.id AND clientes.nacionalidad=pais.id AND contratos.id=".$_GET['idContrato']."
                                            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);



$idContrato=$listado[0][0];
$empresa=$listado[0][1];
$nombre=$listado[0][2];
$apellidos=$listado[0][3];
$dni=$listado[0][4];
$direccion=$listado[0][5];
$localidad=$listado[0][6];
$provincia=$listado[0][7];
$comunidad=$listado[0][8];
$cp=$listado[0][9];
$fijo=$listado[0][10];
$movil=$listado[0][11];
$pais=$listado[0][12];
$inicio=$listado[0][13];
$fin=$listado[0][14];

$listado=$util->selectWhere3("textos_legales",array("texto,id_servicio"),"id_empresa=".$_SESSION['REVENDEDOR']."");

$textosGenerales="";
$textoInternet="";
$textoFijo="";
$textoMovil="";
$textoTV="";

/*
 * BUSCAMOS TODAS LAS LINEAS DE UN CONTRATO PARA SABER QUE TIENE EL CLIENTE CONTRATADO
 * SELECT *
FROM contratos,contratos_lineas
where contratos.ID=contratos_lineas.ID_CONTRATO AND contratos.id=1 AND contratos.ID_EMPRESA=1
 */


$lineasContrato=$util->selectWhere3("contratos,contratos_lineas",array("contratos_lineas.id_tipo,contratos_lineas.id_asociado,contratos_lineas.id"),
    "contratos.id=contratos_lineas.id_contrato and contratos_lineas.estado!=2
             AND contratos.id=".$_GET['idContrato']."
             AND contratos.id_empresa=".$_SESSION['REVENDEDOR']."");



$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('FACTURA');
$pdf->SetSubject('FACTURA');
$pdf->SetKeywords('CONTRATO DE CLIENTE');
$pdf->SetHeaderData("","","", " CONTRATO #".$idContrato, array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('dejavusans', '', 9, '', true);
$pdf->AddPage();
$content = '';


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

$textosGenerales=str_replace("{nombreEmpresa}",$empresa,$textosGenerales);
$textosGenerales=str_replace("{direccionEmpresa}","DATOS DIRECCION",$textosGenerales);

$textosGenerales=str_replace("{nombreCliente}",$nombre,$textosGenerales);
$nombreCompleto=$nombre." ".$apellidos;
$textosGenerales=str_replace("{nombreCliente}",$nombreCompleto,$textosGenerales);
$textosGenerales=str_replace("{direccionCliente}",$direccion,$textosGenerales);
$textosGenerales=str_replace("{dniCliente}",$dni,$textosGenerales);



$content.="<div><h1>Condiciones Generales contrato:</h1>$textosGenerales";
if($flagInternet==true)
    $content.="<div><h1>Condiciones Particulares Internet:</h1>$textoInternet";
if($flagFijo==true)
    $content.="<div><h1>Condiciones Particulares Telefonía Fija:</h1>$textoFijo";
if($flagMovil==true)
    $content.="<div><h1>Condiciones Particulares Telefonía Móvil:</h1>$textoMovil";
if($flagTv==true)
    $content.="<div><h1>Condiciones Particulares Televisión:</h1>$textoTV";

$pdf->writeHTML($content);
$pdf->writeHTML($contentServiciosContratados);

$pdf->writeHTML($contentCampanas);



$pdf->output('Reporte.pdf', 'I');
?>


?>