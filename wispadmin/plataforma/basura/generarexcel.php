<?php

include ("bloquedeseguridad.php");
include("funciones.php");

$nombrearchivo=$_SESSION['nombrexls'];
$nombrearchivo=$nombrearchivo."xls";

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=$nombrearchivo");
header("Pragma: no-cache");
header("Expires: 0");

echo $_SESSION['tablazo'];


?>

