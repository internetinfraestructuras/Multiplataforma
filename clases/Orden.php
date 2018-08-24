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

        $t_ordenes=array("ID_CONTRATO","FECHA_ALTA","ID_TIPO_ESTADO","NUMERO","ID_EMPRESA");

        if($fechaAlta==null)
            $fechaAlta=date('Y-m-d h:i:s ');

        $nuevaOrden = intval($util->selectMax('ordenes', 'NUMERO','ID_EMPRESA ='.$_SESSION['REVENDEDOR']))+1;


        $values=array($idContrato,$fechaAlta,1,$nuevaOrden,$_SESSION['REVENDEDOR']);//TIPO DE ESTADO ES 1 DE APERTURA

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

    public static function obtenerOrdenesAsignadas($idEmpresa, $idEmpleado, $estado, $fechaInicio, $fechaFin)
    {
        $util=new util();
        $campos=array('clientes.NOMBRE','clientes.APELLIDOS','clientes.DIRECCION','clientes.MOVIL',
                        'municipios.municipio','ordenes_usuario.FECHA_ASIGNACION');
        $join='JOIN ordenes ON ordenes_usuario.ID_ORDEN = ordenes.ID JOIN clientes ON clientes.ID = contratos.ID_CLIENTE
                JOIN municipios ON municipios.id = clientes.localidad JOIN contratos ON contratos.ID = ordenes.ID_CONTRATO ';
        $where='contratos.ID_EMPRESA = '.$idEmpresa . ' AND ordenes_usuarios.ID_USUARIO='.$idEmpleado .' AND ordenes.ID_TIPO_ESTADO='.$estado
                        . 'ordenes_usuario.FECHA_ASIGNACION BETWEEN "'.$fechaInicio . '" AND "'.$fechaFin.'"';
        return $util->selectJoin('ordenes_usuario', $campos,  $join, 'ordenes_usuario.FECHA_ASIGNACION', $where);

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