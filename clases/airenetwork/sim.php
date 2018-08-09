<?php
include_once 'config.php';
$sim=new Sim($url,$user,$pass);
if(empty($_GET))
    $sim->getAllSims();
else if(!empty($_GET['telefono']))
    $sim->getSimsTel($_GET['telefono']);
    ?>