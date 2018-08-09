<?php
require_once "lib/nusoap.php";
require_once "clases/AirNetworkConnector.php";

$api=new AirNetwortk();
$telefono="606216038";

$client = new nusoap_client("http://wsr.xtratelecom.es/cableoperador/wsdl/cablePing.wsdl",true);

print_r($client);

/*$data = array("resellerId" => "216358", "resellerPin" => "ksRsJjH039qCJ");

$result = $client->call("pingRequest", array($data));

$error = $client->getError();
if ($error)
    echo "<pre>".$error."</pre>";
*/


/*$api->getTodosClientes();
$api->getClienteDNI("B90013699");*/



/*$api->altaNuevoCliente("0","1","1","123456789A","Nombre","Nombre contacto",
                        "Apelliod1","Apellido2","11/02/1990","1","123456789B",
    "info@email.com","674646893","Andalucia","Jerez","Cadiz","11408","C/del comercio 18",
    "18","Andalucia","cadiz","Jerez","11408","Calle",
    "18","descripcion de envios","","","","","");*/

//$api->consultarLineas("606216038");

?>


