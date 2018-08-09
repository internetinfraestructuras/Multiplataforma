<?php
include_once 'config.php';
$linea=new Linea($url,$user,$pass);

    //Todas las líneas, busqueda sin filtros
    if(empty($_REQUEST))
        echo $linea->getAllLineas();
    else
    {
        if(isset($_GET['numeros'])&& $_GET['numeros']=="libres")//?numeros=libres
            echo $linea->getNumerosLibres();
        else if(!empty($_GET['dni']))
            echo $linea->getLineasPorDni($_GET['dni']);//?dni=X
        else if(!empty($_GET['activo']))
        {
            if($_GET['activo']=="SI" ||$_GET['activo']=="NO" && empty($_GET['activo']))//activo=SI
                echo $linea->getLineasActivas($_GET['activo']);
            else
                Exception::errorLlamada(500,"El estado solo puede ser SI/NO");
        }
        else if(isset($_GET['activas']))//?activas&telefono=X
        {
            if(!empty($_GET['telefono']))
                 echo $linea->getLineasActivas($_GET['telefono']);
            else
                Exception::errorLlamada(400,"Falta el telefono");
        }
        else if(!empty($_GET['tipoCliente']))//?tipoCliente=1
            echo $linea->getLineaTipoCliente($_GET['tipoCliente']);
        else if(!empty($_GET['fechaAlta']))//?fechaAlta=2018 || 2018-07 || 2018-07-01
            echo $linea->getLineaFechaAlta($_GET['fechaAlta']);
        else if(isset($_GET['consumoMaximo']))
            echo $linea->getLineaConsumoMaximo($_GET['consumoMaximo']);
        else if(isset($_GET['alertaFacturacion']))
            echo $linea->setAlertaFacturacion($_GET['alertaFacturacion'], $_GET['telefono']); //?alertaFacturacion=1/0&telefono=X
        else if(isset($_GET['solicitudesLineas']))//?solicitudesLineas
        {
            if(!isset($_GET['telefono'])&& !isset($_GET['sim']))
                echo $linea->getSolicitudesLineas();
            else if(isset($_GET['telefono']))
                echo $linea->getSolicitudesLineaTelefono($_GET['telefono']);//&telefono=X
            else if(isset($_GET['sim']))
                echo $linea->getSolicitudesLineaSim($_GET['sim']);//&sim=ICC-SIM
        }
        else if(isset($_GET['servicios']))
        {
            echo $linea->getServiciosLinea($_GET['telefono']);//?telefono=X
        }
        else if(isset($_GET['consumo']))
        {
            echo $linea->setConsumoMaximo($_GET['telefono'],$_GET['maximo']);
        }
        else if(isset($_GET['altaNueva']))
        {

            $data = json_decode(file_get_contents('php://input'), true);
            $linea->setAltaNueva($data);
        }
        else if(isset($_GET['altaPortabilidad']))
        {

            $data = json_decode(file_get_contents('php://input'), true);
            $linea->setAltaPortabilidad($data);
        }
        else if(isset($_GET['cambioTitular']))
        {

            $data = json_decode(file_get_contents('php://input'), true);
            $linea->setCambioTitular($data);
        }
        else if(isset($_GET['subirDocumento']))
        {

            $data = json_decode(file_get_contents('php://input'), true);
            $linea->setDocumentos($data);
        }



    }


