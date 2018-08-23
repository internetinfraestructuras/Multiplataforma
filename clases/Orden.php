<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 07/08/2018
 * Time: 11:03
 */

class Orden
{
    public static function crearOrdenTrabajo($idContrato,$fechaAlta)
    {
        //CREAR LA ORDEN DE TRABAJO PRIMERO
        $util=new util();

        $t_ordenes=array("ID_CONTRATO","FECHA_ALTA","ID_TIPO_ESTADO");

        if($fechaAlta==null)
            $fechaAlta=date('Y-m-d h:i:s ');

        $values=array($idContrato,$fechaAlta,1);//TIPO DE ESTADO ES 1 DE APERTURA

        $resOrden= $util->insertInto('ordenes', $t_ordenes, $values);

        return $resOrden;

    }

    /*SELECT ordenes.ID
FROM ordenes,ordenes_lineas,contratos
WHERE ordenes.id=ordenes_lineas.ID_ORDEN AND ordenes_lineas.ID_LINEA_DETALLE_CONTRATO=972 AND ordenes.ID_CONTRATO=contratos.id AND contratos.ID_EMPRESA=2*/

    public static function obtenerIdOrden($idLineaDetalle)
    {
        $util=new util();
        return $util->selectWhere3("ordenes,ordenes_lineas,contratos", array("ordenes.id"),
            "ordenes.id=ordenes_lineas.id_orden 
            AND ordenes_lineas.id_linea_detalle_contrato=".$idLineaDetalle." 
            AND ordenes.id_contrato=contratos.id 
            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);
    }


    public static function crearLineaOrden($idOrden,$idTipoOrden,$idProducto,$idLineaDetalle)
    {
        $util=new util();
        $t_ordenes=array("ID_ORDEN","ID_TIPO_ORDEN","ID_PRODUCTO","ID_LINEA_DETALLE_CONTRATO");

        $values=array($idOrden, $idTipoOrden, $idProducto, $idLineaDetalle);//TIPO DE ESTADO ES 1 DE APERTURA

        $resOrden= $util->insertInto('ordenes_lineas', $t_ordenes, $values);

        return $resOrden;
    }


    public static function cancelarOrdenTrabajo($idProducto,$idLinea,$idTipoEstado)
    {
        $util=new util();
        $idOrden=Orden::obtenerIdOrden($idLinea[0]['ID']);


        $t_ordenes=array("FECHA_FIN","ID_TIPO_ESTADO");

        $values=array(date('Y-m-d h:i:s '),4);//TIPO DE ESTADO ES 1 DE APERTURA

        $util->update('ordenes', $t_ordenes, $values,"ID=".$idOrden[0]['id']);
    }
}