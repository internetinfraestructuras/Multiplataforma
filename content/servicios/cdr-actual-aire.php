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

$rs=$apiAire->getDatosCDR($msidn,"2018","08","MOVIL");
var_dump($rs);





?>
