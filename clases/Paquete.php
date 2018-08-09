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

public static function modificarPaqueteContrato($idContrato,$idLinea,$id,$precioProveedor,$beneficio,$impuestos,$pvp)
{

  $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);//LINEA DEL CONTRATO
  $lineaDetalles=Contrato::getLineaDetalles($idLinea);//ARRAY DE LOS ATRIBUTOS DE LOS SERVICIOS DEL PAQUETE
  $lineasProductos=Contrato::getProductosLinea($idLinea);


  $idTipo=$lineaContrato[0]['ID_TIPO'];//Tipo de servicio
  $idAsociado=$lineaContrato[0]['ID_ASOCIADO'];//ID del servicio/paquete
  $permanencia=$lineaContrato[0]['PERMANENCIA'];

    //ACTUALIZAR LINEA CONTRATO A BAJA
    Contrato::setLineaContratoBaja($idContrato,$idLinea,$idAsociado);


    //ACTUALIZAR LINEA DETALLES A BAJA



    //CREAR NUEVA LINEA DE CONTRATO
    //CREAR NUEVAS LÍNEAS DE DETALLES A BAJA
    //ACTUALIZAR PRODUCTOS A LA NUEVA LINEA
    //GENERAR ANEXO

}

}