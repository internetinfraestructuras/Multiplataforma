<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 02/08/2018
 * Time: 12:58
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ('Contrato.php');


class Paquete
{

public static function modificarPaqueteContrato($idContrato,$idLinea,$id,$precioProveedor,$beneficio,$impuesto,$pvp)
{


    $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);


    $permanencia=$lineaContrato[0]['PERMANENCIA'];

    $lineaDetallesAll=Contrato::getLineaDetalles($idLinea);


    $idPaquete=Contrato::getIdPaqueteLinea($idContrato,$idLinea);



    $idPaquete=$idPaquete[0]['ID_ASOCIADO'];



    Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete,$fechaCambio);

    $nuevaLinea=Contrato::setNuevaLineaContrato(1,$idPaquete,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1,$fechaCambio);


    $util=new util();
    $numero= $util->selectWhere3('servicios_tipos_atributos',
        array("count(id)"),
        "servicios_tipos_atributos.id_servicio=$tipo AND servicios_tipos_atributos.id_tipo=2");

    $numero=$numero[0][0];
    $numeroMax=$idLineaDetalle+($numero-1);



    for($k=0;$k<count($lineaDetallesAll);$k++)
    {



        if($lineaDetallesAll[$k]['ID_SERVICIO']==$idServicioOriginal && $lineaDetallesAll[$k]['ID']<=$numeroMax )
        {

            if($atributos!=null)
            {


                for($i=0;$i<count($atributos['id']);$i++)
                {


                    if($atributos['id'][$i]==$lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'] )
                    {

                        $idAtrib=$atributos['id'][$i];
                        $valor=$atributos['valor'][$i];

                        $tipo=$lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                        $ser=$lineaDetallesAll[$k]['ID_SERVICIO'];

                        $idLineaDetalleNueva=Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$idServicio,$fechaCambio);

                        Contrato::generarAnexo($idContrato,$ser,3);
                    }

                }

            }


        }
        else
        {

            $idAtrib=$lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
            $valor=$lineaDetallesAll[$k]['VALOR'];

            $tipo=$lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
            $ser=$lineaDetallesAll[$k]['ID_SERVICIO'];

            $idLineaDetalleNueva=Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$ser,$fechaCambio);
        }

        Contrato::setLineaDetallesBajaServicio($idLinea,$ser,$fechaCambio);//Seteamos la linea actual a baja


        $productosLinea=Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

        for($j=0;$j<count($productosLinea);$j++)
        {

            Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'],$idLineaDetalleNueva);
        }

    }



}

}