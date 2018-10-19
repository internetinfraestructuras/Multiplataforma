<?php


//require_once './clases/AltaTecnica.php';
//$cifSuperUsuario='B45782687';
//
//AltaTecnica::addNuevoFijo($cifSuperUsuario,"12345678A","diego","c/comercio 18","diego@diego.vom","Mi nuevo grupo",2,"956303860");
//

include_once 'config/util.php';
//include_once 'clases/Clientes.php';
//include_once 'clases/AltaTecnica.php';
//$cli=new Clientes();
//
//$datosCliente = $cli->getClienteAltaMasMovil(2900);
//$datosCliente = mysqli_fetch_array($datosCliente);
////print_r($datosCliente);
//
//$codBanco=substr($datosCliente[13],4,4);
//$oficina=substr($datosCliente[13],8,4);
//$dc=substr($datosCliente[13],12,2);
//$ccc=substr($datosCliente[13],14,10);
//$codProv=substr($datosCliente[11],0,2);
//$icc="";
//$idServicio=55;
//
//echo $codBanco. " ".$oficina. " ".$dc. " ".$ccc. " ".$codProv;
//
//var_dump(AltaTecnica::addNuevaLineaMasMovil($datosCliente[0]." ".$datosCliente[1],
//    $datosCliente[0], $datosCliente[2],$datosCliente[3], $datosCliente[0], $datosCliente[4],
//    $datosCliente[5], $datosCliente[4], $datosCliente[6], $datosCliente[7], $datosCliente[8], $codProv,
//    $datosCliente[10], $datosCliente[11], $datosCliente[0] . " ". $datosCliente[1],$datosCliente[12],
//    $codBanco, $oficina, $dc, $ccc, $icc, $idServicio));

/*
 * * $nombre, 0 + 1
 * $nombreEmpresa, 1
 *
 * $tipoDocumento, 2
 * $dni, 3
 * $nombreContacto, 0
 * $telContacto, 4
 * $movilContacto, 5
 * $faxContacto,4
 * $emailContacto, 6
 * $calle, 7
 * $localidad, 8
 * $codigoProvincia,
 * $codigoPais, 10
 * $codigoPostal, 11
 * $titularCuenta, 0 + 1
 * $nombreBanco, 12
 * $codigoBanco, codbanco
 * $oficina,    oficina
 * $digitoControl,   dc
 * $numeroCuenta,   ccc
 * $iccTarjeta, icc
 * $idServicio idservicio
 *
 * [0] => Diego [NOMBRE] => Diego
 * [1] => Puya [APELLIDOS] => Puya
 * [2] => 1 [TIPO_DOCUMENTO] => 1
 * [3] => 32010205n [DNI] => 32010205n
 * [4] => 956000102 [FIJO] => 956000102
 * [5] => 674646893 [MOVIL] => 674646893
 * [6] => diego.puya91@gmail.com [EMAIL] => diego.puya91@gmail.com
 * [7] => C/del Arco n 13 [DIRECCION] => C/del Arco n 13
 * [8] => Jerez de la Frontera [municipio] => Jerez de la Frontera
 * [9] => Cádiz [provincia] => Cádiz
 * [10] => ESP [iso] => ESP
 * [11] => 11593 [CP] => 11593
 * [12] => ING [BANCO] => ING
 * [13] => ES4621005091570100215103 [IBAN] => ES4621005091570100215103 )
 *
 *

 */
$util = new util();
echo $util->enviarEmail('rubencorralescorbacho@gmail.com', 'desarrollo@grupoliontelecom.es',null, 'asunto', 'texto');


?>