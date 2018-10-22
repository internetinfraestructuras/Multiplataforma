<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 22/10/2018
 * Time: 9:06
 */

require_once ('../config/util.php');
require_once ('../clases/masmovil/MasMovilAPI.php');
require_once ('../clases/Empresa.php');
require_once ('../clases/Contrato.php');

$listadoEmpresas=Empresa::getListadoEmpresas();
$apiMasMovil=new MasMovilAPI();


for($i=0;$i<count($listadoEmpresas);$i++)
{

    $idEmpresa=$listadoEmpresas[$i][0];
    $pendientes=$apiMasMovil->getNumerosPendientes($idEmpresa);

    for($j=0;$j<count($pendientes);$j++)
    {
        echo "ENtramos rutina<hr>";
        $id=$pendientes[$j]['ID'];
        $idLineaDetalle=$pendientes[$j]['ID_LINEA_DETALLE'];
        $icc=$pendientes[$j]['ICC'];

        $contrato=Contrato::getClienteDatosPorLineaDetalle($idEmpresa,$idLineaDetalle);
        $dni=$contrato[0]['DNI'];
        $idContrato=$contrato[0]['ID_CONTRATO'];


        $listClientes=$apiMasMovil->getListadoClientes($dni);

        //si hay resultado correcto
        if(isset($listClientes->Client[0]->refCustomerId))
        {

            $refClienteApi=$listClientes->Client[0]->refCustomerId;
            $estadoLinea=json_decode($apiMasMovil->buscarLineaIcc($refClienteApi,$icc));

            $numero=$estadoLinea->msisdnList->Msisdn->msisdn;
            if($numero!=null)
            {
                Contrato::setAtributoLineaDetalle($idLineaDetalle,$numero);
                Contrato::setNumeroPendiente($id,4);

            }
        }




    }
    echo "<hr>";


}