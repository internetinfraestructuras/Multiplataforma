<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 02/08/2018
 * Time: 13:15
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ('Contrato.php');
/*

require_once('../config/def_tablas.php');
*/


class Servicio
{

    /*
     * METODO PARA ACTUALIZAR UN SERVICIO CONTRATADO
     * 1.idContrato:Identificador del contrato
     * 2.idLinea:Identificador de la línea del contrato.
     * 3.idServicio:Identificador del servicio asociado a esa línea.
     * 4.idServicioNuevo:Identificador del servicio nuevo
     * 5.
     * Explicación de la rutina;
     * 1. Se obtiene la línea de contrato del servicio X. Para tener los datos para el volcado.
     * 2. Se obtiene las lineas de detalles para el volcado de datos.
     * 3. Seteamos la línea de detalle obtenida y la establecemos en baja.(2)
     * 4. Seteamos la línea de contrato a baja.(2)
     * 5. Se genera la nueva línea de contrato en alta (1)
     * 6. Se vuelca la información en la nueva línea de contrato.
     * 7. Se vuelca la información de las líneas de detalles.
     * 8. Se buscan los productos asociados a la línea anterior y se actualizan apuntando a la nueva línea generada.
     * 9. Se genera el anexo del contrato.
     */
public static function actualizarServicioContrato($idContrato,$idLinea,$idServicio,$idServicioNuevo,$precioProveedor,$beneficio,$pvp,$impuesto,$atributos)
{
    $lineaContrato=Contrato::getLineaContratoServicio($idContrato,$idLinea,$idServicio);

    $lineaDetalles=Contrato::getLineaDetalles($idLinea);


    //Obtenemos los datos necesarios para volcarlos en la nueva tupla
    $tipo=$lineaContrato[0][0];
    $idAsoc=$lineaContrato[0][1];
    $idContrato=$lineaContrato[0][2];
    $permanencia=$lineaContrato[0][7];

    Contrato::setLineaDetallesBaja($idLinea);//Seteamos la linea actual a baja
    Contrato::setLineaContratoBaja($idContrato,$idLinea,$idServicio);

    $idLineaNueva=Contrato::setNuevaLineaContrato($tipo,$idServicioNuevo,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1);

    //Seteamos los detalles de una línea de contrato

    for($i=0;$i<count($atributos);$i++)
    {

        $idAtrib=$atributos['id'][$i];
        $valor=$atributos['valor'][$i];

        $tipo=$lineaDetalles[$i][0];
        $atributo=$lineaDetalles[$i][1];

        echo "El nuevo atributo es".$atributo." con un valor de".$valor." y con el ID".$idAtrib."<br>";

        Contrato::setNuevaLineaDetalles($idLineaNueva,$tipo,$idAtrib,$valor,1);

        $productosLinea=Contrato::getProductosLinea($idLinea);

        for($j=0;$j<count($productosLinea);$j++)
        {
            Contrato::cambiarLineaProducto($idLinea,$idLineaNueva);
        }

    }

/*
    $values=array($precioProveedor,$impuesto,$beneficio,$pvp,$permanencia,1,);
    $campos=array("precio_proveedor","impuesto","beneficio","pvp");
    $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=".$idServicio. " AND id_contrato=".$_POST['idContrato']." AND id=".$_POST['idLinea']);
*/
    Contrato::generarAnexo($idContrato,$idServicio,"Se ha cambiado el servicio");








}


}