<?php

error_reporting('0');
ini_set('display_errors', '0');

include_once('../../pdf/utilPDF.php');

require_once('../../pdf/tcpdf/tcpdf.php');
if (!isset($_SESSION)) {@session_start();}
$util=new utilPDF();


$listado= $util->selectWhere3('contratos,empresas,clientes,municipios,provincias,comunidades,pais',
    array("contratos.id,empresas.nombre,clientes.nombre,clientes.apellidos,clientes.dni,clientes.direccion,municipios.municipio,provincias.provincia,comunidades.comunidad,clientes.cp,clientes.fijo,clientes.movil,pais.paisnombre,contratos.fecha_inicio,contratos.fecha_fin"),
    "contratos.id_empresa=empresas.id AND contratos.id_cliente=clientes.id  AND municipios.id=clientes.localidad AND provincias.id=clientes.provincia AND clientes.comunidad=comunidades.id AND clientes.nacionalidad=pais.id AND contratos.id=".$_GET['idContrato']."
                                            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);



$idContrato=$listado[0][0];
$empresa=$listado[0][1];
$nombre=$listado[0][2];
$apellidos=$listado[0][3];
$dni=$listado[0][4];
$direccion=$listado[0][5];
$localidad=$listado[0][6];
$provincia=$listado[0][7];
$comunidad=$listado[0][8];
$cp=$listado[0][9];
$fijo=$listado[0][10];
$movil=$listado[0][11];
$pais=$listado[0][12];
$inicio=$listado[0][13];
$fin=$listado[0][14];


$lineasContrato=$util->selectWhere3("contratos,contratos_lineas"
    ,array("contratos_lineas.impuesto,contratos_lineas.pvp,contratos_lineas.fecha_alta,contratos_lineas.id_tipo,contratos_lineas.id_asociado"),
    "contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
            AND contratos.id=contratos_lineas.id_contrato 
            AND contratos_lineas.estado=1 AND contratos.id=".$_GET['idContrato']);


for($i=0;$i<count($lineasContrato);$i++)
{

    $impuesto=$lineasContrato[$i][0];
    $pvp=$lineasContrato[$i][1];
    $fechaAlta=$lineasContrato[$i][2];
    $tipoLinea=$lineasContrato[$i][3];
    $idAsociado=$lineasContrato[$i][4];

    if($tipoLinea==1)
    {
        $nombre=$util->selectWhere3("paquetes"
            ,array("paquetes.nombre"),
            "paquetes.id=$idAsociado");
    }
    if($tipoLinea==2)
    {
        $nombre=$util->selectWhere3("servicios"
            ,array("servicios.nombre"),
            "servicios.id=$idAsociado");
    }

}

?>
