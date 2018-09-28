<?php

error_reporting('0');
ini_set('display_errors', '0');

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


echo $tableCDR;






?>
