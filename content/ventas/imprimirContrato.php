<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('../../pdf/utilPDF.php');
require_once('../../pdf/tcpdf/tcpdf.php');

$descripcionPaquete="";

if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


    $listado= $util->selectWhere3('contratos,empresas,clientes,municipios,provincias,comunidades,pais',
        array("contratos.id,empresas.nombre,clientes.nombre,clientes.apellidos,clientes.dni,clientes.direccion,municipios.municipio,
        provincias.provincia,comunidades.comunidad,clientes.cp,clientes.fijo,clientes.movil,pais.paisnombre,contratos.fecha_inicio,
        contratos.fecha_fin"),
        "contratos.id_empresa=empresas.id AND contratos.id_cliente=clientes.id  AND municipios.id=clientes.localidad 
        AND provincias.id=clientes.provincia AND clientes.comunidad=comunidades.id AND clientes.nacionalidad=pais.id 
        AND contratos.id=".trim($util->cleanstring($_GET['idContrato']))."AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);



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

$listado=$util->selectWhere3("textos_legales",array("texto,id_servicio"),"id_empresa=".$_SESSION['REVENDEDOR']." AND ubicacion=''");

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
             AND contratos.id=".trim($util->cleanstring($_GET['idContrato']))."
             AND contratos.id_empresa=".$_SESSION['REVENDEDOR']."");

//Flags que controlan la impresión de las condiciones particulares del servicio.

$flagInternet=false;
$flagFijo=false;
$flagMovil=false;
$flagTv=false;

$pvpTotal=0;

$contentServiciosContratados="<br><br><br><h3>Anexo 1: Servicios Contratados</h3><table border=\"1\" style=\"text-align: center;padding:5px;\"><tr style=\"background-color: #9e9e9e\"><th>CONCEPTO</th><th>CUOTA/MES</th></tr>";

//BUSCAMOS LAS LINEAS DEL SERVICIO PARA HACER EL DESGLOSE EN EL CONTRATO
for($i=0;$i<count($lineasContrato);$i++)
{
    if($lineasContrato[$i][0]==1)
    {
        $flagPaquete=true;
        $paquete=$util->selectWhere3("contratos,contratos_lineas,paquetes",array("paquetes.nombre,contratos_lineas.pvp,contratos_lineas.id"),
            "contratos.id=contratos_lineas.id_contrato 
             AND contratos_lineas.id=".$lineasContrato[$i][2]."
             AND paquetes.id=contratos_lineas.id_asociado
             AND contratos_lineas.estado!=2
             AND contratos.id=".trim($util->cleanstring($_GET['idContrato']))."
             AND contratos.id_empresa=".$_SESSION['REVENDEDOR']."");
            $nombrePaq=$paquete[0][0];
            $pvp=$paquete[0][1];

            $pvpTotal+=$pvp;



        //Desglosar los servicios del paquete buscando en las lineas de detalles
        //SELECT servicios.NOMBRE,servicios.ID_SERVICIO_TIPO,contratos_lineas_detalles.*
        //FROM contratos_lineas,contratos_lineas_detalles,servicios
        //WHERE contratos_lineas.id=contratos_lineas_detalles.ID_LINEA
        //AND contratos_lineas_detalles.ID_LINEA=1
        //AND contratos_lineas_detalles.ESTADO!=5
        //AND contratos_lineas_detalles.ID_SERVICIO=servicios.id;

        $servicio=$util->selectWhere3("contratos_lineas,contratos_lineas_detalles,servicios",array("distinct(servicios.NOMBRE),servicios.ID_SERVICIO_TIPO"),
            "contratos_lineas.id=contratos_lineas_detalles.ID_LINEA
             AND contratos_lineas_detalles.id_linea=".$paquete[$i][2]." 
             AND contratos_lineas.ESTADO!=2 
             AND contratos_lineas_detalles.id_servicio=servicios.id");

        $contentServiciosContratados.="<tr><td >PAQUETE:$nombrePaq *</td><td>$pvp &euro;</td></tr>";


        //HACEMOS EL DESGLOSE EN OTRA TABLA A PARTE PARA DETALLAR LOS SERVICIOS DEL PAQUETE CONTRATADO
        $descripcionPaquete="<br><br><br><h4>*Detalles Paquete contratado:</h4><table border=\"1\" style=\"text-align: center;padding:5px;\" ><tr style=\"background-color: #9e9e9e\"><th>DESCRIPCIÓN</th></tr>";
        for($j=0;$j<count($servicio);$j++)
        {
            $nombreSer1=$servicio[$j][0];

            $descripcionPaquete.="<tr><td>SERVICIO:$nombreSer1</td></tr>";

            if($servicio[$j][1]==1)
                $flagInternet=true;
            if($servicio[$j][1]==2)
                $flagFijo=true;
            if($servicio[$j][1]==3)
                $flagMovil=true;
            if($servicio[$j][1]==4)
                $flagTv=true;
        }
        $descripcionPaquete.="</table>";

    }

    //SI LA LINEA DE CONTRATO ES UN SERVICIO APARTE SE MUESTRAN LOS DATOS
    if($lineasContrato[$i][0]==2)
    {

        $flagPaquete=true;
        $servicio=$util->selectWhere3("contratos,contratos_lineas,servicios",array("servicios.nombre,contratos_lineas.pvp,servicios.id_servicio_tipo"),
            "contratos.id=contratos_lineas.id_contrato 
             AND contratos_lineas.id=".$lineasContrato[$i][2]."
             AND servicios.id=contratos_lineas.id_asociado
             AND contratos.id=".trim($util->cleanstring($_GET['idContrato']))."
             AND contratos.id_empresa=".$_SESSION['REVENDEDOR']."");
        $nombreSer=$servicio[0][0];
        $pvp=$servicio[0][1];



        if($servicio[$i][2]==1)
            $flagInternet=true;
        if($servicio[$i][2]==2)
            $flagFijo=true;
        if($servicio[$i][2]==3)
            $flagMovil=true;
        if($servicio[$i][2]==4)
            $flagTv=true;

        $pvpTotal+=$pvp;
        $contentServiciosContratados.="<tr><td>SERVICIO:$nombreSer</td><td>$pvp &euro;</td></tr>";
    }
}
$contentServiciosContratados.="<tr><td style=\"background-color: #9e9e9e\">TOTAL:</td><td style=\"background-color: #9e9e9e\"><b>$pvpTotal &euro;</b></td></tr>";
$contentServiciosContratados.="</table>";

$contentServiciosContratados.=$descripcionPaquete;

/*
 * BUSCAMOS LAS CAMPAÑAS DE PROMOCIÓN APLICADAS A ESE CONTRATO


SELECT campanas.NOMBRE,campanas.DURACION
from campanas,contratos_campanas
where campanas.id=contratos_campanas.ID_CONTRATO-*/


$campanas=$util->selectWhere3("campanas,contratos_campanas",array("campanas.nombre,contratos_campanas.dto,contratos_campanas.dto_hasta"),
    "campanas.id=contratos_campanas.id_campana AND contratos_campanas.id_contrato=".trim($util->cleanstring($_GET['idContrato']))." 
      AND campanas.id_empresa=".$_SESSION['REVENDEDOR']." ");
$contentCampanas="";


if($campanas[0]!=NULL)
{

$contentCampanas="<h3>Anexo 2: Campañas promocionales</h3>";
$contentCampanas.="<table border=\"1\" style=\"text-align: center;padding:5px;\"><tr style=\"background-color: #9e9e9e\"><th>CAMPAÑA</th><th>DESCUENTO %</th><th>HASTA</th></tr>";
    for($k=0;$k<count($campanas);$k++)
    {
        $nombreCampa=$campanas[$k][0];
        $descuento=$campanas[$k][1];
        $hasta=$campanas[$k][2];
        $contentCampanas.="<tr><td>$nombreCampa</td><td>$descuento %</td><td>$hasta</td></tr>";

    }
    $contentCampanas.="</table>";

}


for($i=0;$i<count($listado);$i++)
{
    if($listado[$i][1]==0)
        $textosGenerales=$listado[$i][0];
    if($listado[$i][1]==1)
        $textoInternet=$listado[$i][0];
    if($listado[$i][1]==2)
        $textoFijo=$listado[$i][0];
    if($listado[$i][1]==3)
        $textoMovil=$listado[$i][0];
    if($listado[$i][1]==4)
        $textoTV=$listado[$i][0];
}


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('REVENDEDOR');

$pdf->SetTitle('CONTRATO DE CLIENTE');
$pdf->SetSubject('CONTRATO');
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
                <b>Nombre:</b>'.$nombre." ".$apellidos."<br/>
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
