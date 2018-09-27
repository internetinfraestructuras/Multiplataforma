<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
require_once ('../../clases/airenetwork/clases/CDR.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();

$msidn=$_GET['numero'];

$apiAire=new CDRAire();

$lsLlamadas=$apiAire->getDatosCDR($msidn,"2018","08","MOVIL");

$lsSMS=$apiAire->getDatosCDR($msidn,"2018","08","SMS");
var_dump($lsLlamadas);
for($i=0;$i<count($lsLlamadas);$i++)
{
    $fecha=$lsLlamadas[$i]['fecha'];
    $origen=$lsLlamadas[$i]['origen'];
    $destino=$lsLlamadas[$i]['destino'];
    $descripcion=$lsLlamadas[$i]['descripcion'];
    $tiempo=$lsLlamadas[$i]['segundos'];
    $importe=$lsLlamadas[$i]['importe'];

}






?>
