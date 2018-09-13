<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/09/2018
 * Time: 8:53
 * REVISA TODOS LOS CONTRATOS EN EL SISTEMA Y COMPRUEBA LAS FECHAS DE BAJA DE LOS CONTRATOS, SI ES A DÍA DE HOY SE LE DA DE BAJA A TODOS SUS DATOS Y DETALLES
 */

require_once ('../clases/Contrato.php');
require_once ('../clases/Factura.php');
require_once ('../clases/Empresa.php');
require_once ('../config/util.php');

$listadoEmpresas=Empresa::getListadoEmpresas();



for($i=0;$i<count($listadoEmpresas);$i++)
{
    $idEmpresa=$listadoEmpresas[$i][0];

   $listaContratos=Contrato::getContratosBajaHoy($idEmpresa);

    if(!empty($listaContratos))
    {
       for($j=0;$j<count($listaContratos);$j++)
       {
           $idContrato=$listaContratos[$j][0];
           $lineas=Contrato::getLineasContratoAlta($idContrato);

           for($k=0;$k<count($lineas);$k++)
           {
               $idLinea=$lineas[$k][8];
               $idAsociado=$lineas[$k][1];
               $detalles=Contrato::getLineaDetalles($idLinea);

               for($l=0;$l<count($detalles);$l++)
               {
                   // Contrato::setLineaDetallesBaja($idLinea);
               }

               Contrato::setLineaContratoBaja($idContrato,$idLinea,$idAsociado);
           }
           Contrato::setContratoBaja($idContrato,$idEmpresa);
       }
    }




}
