<?php


require_once 'MasMovilAPI.php';

$api=new MasMovilAPI();
//216358003724 AYUNTAMIENTO DE TETAR O ALGO DE ESO
$regCliente="216358003724";
$msid="";
//$api->ping("PROBANDADADASD");
//echo $api->getListadoClientes("","","400");

/*echo $api->crearNuevoCliente("DIEGO","NEXWRF","DNI","32075536N",
    "DIEGO","674646893","674646893","", "diego.puya91@gmail.com",
    "Calle del arco","Estella del Marques","11","ES","11593",
    "Diego Pua","ING",0000,0000,00,1234567890);*/

/*echo $api->modificarCliente("216358003724","DIEGO","NEXWRF","DNI","32075536N",
    "DIEGO","674646893","674646893","", "diego.puya91@gmail.com",
    "Calle del arco","Estella del Marques","11","ES","11593",
    "Diego Pua","ING",0000,0000,00,1234567890);*/

//echo $api->bajaCliente($regCliente);

//echo $api->altaLineaMovil($regCliente,"12345678","CM_POSTPAGO_CTS","B0060");
//echo $api->bajaLineaMovil($regCliente,"MSID");
//echo $api->gestionEstados($regCliente,$msid,"J");
$fecha="";

//echo $api->cambioProducto($regCliente,$fecha,$msid,"CM_POSTPAGO_CTS");
$icc="";
$estado="";
//echo $api->getLineasMsisdnsIccids($regCliente,$msid,$icc,$estado);
//echo $api->setRoaming($regCliente,$msid,$estado,"");
//echo $api->getListadoProductos($regCliente,"","");
//echo $api->getListadoOperadores($regCliente);
$donante="183";
//echo $api->getListadoPortabilidades($regCliente,$donante,$msid,"674646893","Diego","","");
//echo $api->altaPortabilidad($regCliente,"674646893");
//echo $api->getPeticionRiesgo($regCliente,$msid);
$motivo="ROT";
$iccNuevo="ASDA";
//echo $api->peticionCambioIccparaMsisdn($regCliente,$msid,$iccNuevo,$motivo);
echo $api->getEstadoCambioIccid($regCliente,$msid,$iccNuevo,$fecha);

?>
