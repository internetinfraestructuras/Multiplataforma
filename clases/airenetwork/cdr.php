<?php
//606216038
include_once 'config.php';
$cdr=new CDR($url,$user,$pass);

echo $cdr->getDatosCDR("606216038",2018,"7","MOVIL_ENTRANTE");

?>