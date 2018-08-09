<?php
include_once 'config.php';
$tarifas=new Tarifa($url,$user,$pass);

if(empty($_GET))
    echo $tarifas->getAllTarifas();
else if(!empty($_GET['activa']))
    echo $tarifas->getTarifasStatus($_GET['activa']);
else if(!empty($_GET['pagina'] && !empty($_GET['registro'])))
{
    $tarifas->getTarifasPaginadas($_GET['pagina'],$_GET['registro']);
}