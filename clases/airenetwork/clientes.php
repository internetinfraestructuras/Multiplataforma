<?php
include_once 'config.php';

$cliente=new Cliente($url,$user,$pass);


    if(empty($_GET))
        echo $cliente->getAllClients();
    else if(!empty($_GET['dni']))
        echo $cliente->getClientByDNI($_GET['dni']);
    else if(!empty($_GET['name']))
        echo $cliente->getClientByName($_GET['nombre']);
    else if(isset($_GET['alta']))
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $cliente->crearCliente($data);
    }
    else if(isset($_GET['modificar']))
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $cliente->modificarCliente($data);
    }
    else if(!empty($_GET['pagina'] && !empty($_GET['registro'])))
    {
        $cliente->getClientesPaginados($_GET['pagina'],$_GET['registro']);
    }
       // echo

?>