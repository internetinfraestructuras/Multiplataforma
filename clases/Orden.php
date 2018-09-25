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

    public static function obtenerIdOrden($idLineaDetalle)
    {
        $util=new util();
        return $util->selectWhere3("ordenes,ordenes_lineas,contratos", array("ordenes.id"),
            "ordenes.id=ordenes_lineas.id_orden 
            AND ordenes_lineas.id_linea_detalle_contrato=".$idLineaDetalle." 
            AND ordenes.id_contrato=contratos.id 
            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']);
    }

    // ruben
    public static function obtenerOrdenesAsignadas($idEmpresa, $idEmpleado, $estado, $fechaInicio, $fechaFin)
    {
        $util=new util();
        $campos=array('ordenes.ID,clientes.NOMBRE','clientes.APELLIDOS','clientes.DIRECCION','clientes.MOVIL',
                        'municipios.municipio','ordenes_usuario.FECHA_ASIGNACION');
        $join=' JOIN ordenes ON ordenes_usuario.ID_ORDEN = ordenes.ID 
                JOIN contratos ON contratos.ID = ordenes.ID_CONTRATO 
                JOIN clientes ON clientes.id = contratos.ID_CLIENTE 
                JOIN municipios ON municipios.id = clientes.LOCALIDAD ';
        $where='contratos.ID_EMPRESA = '.$idEmpresa . ' AND ordenes_usuario.ID_USUARIO='.$idEmpleado .' AND ordenes.ID_TIPO_ESTADO='.$estado;

        if($fechaInicio!='' && $fechaFin!='')
            $where = $where. ' AND ordenes_usuario.FECHA_ASIGNACION BETWEEN "'.$fechaInicio . '" AND "'.$fechaFin.'"';

        return $util->selectJoin('ordenes_usuario', $campos,  $join, 'ordenes_usuario.FECHA_ASIGNACION', $where);

    }

// ruben
    public static function obtenerOrdenesAsignadas2()
    {
        $util=new util();
        $campos=array('ordenes.ID, clientes.NOMBRE','clientes.APELLIDOS','usuarios.NOMBRE','usuarios.APELLIDOS',
           'ordenes_usuario.FECHA_ASIGNACION','ordenes.ID_TIPO_ESTADO');
        $join=' JOIN ordenes ON ordenes_usuario.ID_ORDEN = ordenes.ID 
                JOIN contratos ON contratos.ID = ordenes.ID_CONTRATO 
                JOIN clientes ON clientes.id = contratos.ID_CLIENTE  
                JOIN usuarios ON usuarios.ID = ordenes_usuario.ID_USUARIO ';
        $where='ordenes.ID_TIPO_ESTADO != 3 AND contratos.ID_EMPRESA = '.$_SESSION['USER_ID'];

        return $util->selectJoin('ordenes_usuario', $campos,  $join, 'ordenes_usuario.FECHA_ASIGNACION', $where);
    }


// ruben
    public static function obtenerOrdenesCerradas()
    {
        $util=new util();
        $campos=array('ordenes.ID, clientes.NOMBRE','clientes.APELLIDOS','usuarios.NOMBRE','usuarios.APELLIDOS',
            'ordenes_usuario.FECHA_ASIGNACION','ordenes.ID_TIPO_ESTADO');
        $join=' JOIN ordenes ON ordenes_usuario.ID_ORDEN = ordenes.ID 
                JOIN contratos ON contratos.ID = ordenes.ID_CONTRATO 
                JOIN clientes ON clientes.id = contratos.ID_CLIENTE  
                JOIN usuarios ON usuarios.ID = ordenes_usuario.ID_USUARIO ';
        $where='ordenes.ID_TIPO_ESTADO = 3 AND contratos.ID_EMPRESA = '.$_SESSION['USER_ID'];

        return $util->selectJoin('ordenes_usuario', $campos,  $join, 'ordenes_usuario.FECHA_ASIGNACION', $where);
    }


    public static function crearLineaOrden($idOrden,$idTipoOrden,$idProducto,$idLineaDetalle)
    {
        $util=new util();

        if($idProducto==null || $idProducto=='' || intval($idProducto)<=0)
            $idProducto=null;

        $t_ordenes=array("ID_ORDEN","ID_TIPO_ORDEN","ID_PRODUCTO","ID_LINEA_DETALLE_CONTRATO");

        $values=array($idOrden, $idTipoOrden, $idProducto, $idLineaDetalle);//TIPO DE ESTADO ES 1 DE APERTURA

        $resOrden= $util->insertInto2('ordenes_lineas', $t_ordenes, $values);

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

    public static function asignarOrdenUsuario($idOrden,$idUsuario)
    {
        $util=new util();
        $values = array($idOrden,$idUsuario);
        $t_ordenes_usuario=array("ID_ORDEN","ID_USUARIO");
        $result = $util->insertInto('ordenes_usuario', $t_ordenes_usuario, $values);

        self::cambiarEstadoOrden($idOrden,2);
        $result= $util->log('El usuario:'.$_SESSION['USER_ID'].' ha asociado a asignado la orden:'.$idOrden.'al usuario:'.$idUsuario);
    }
    public static function cambiarEstadoOrden($idOrden,$idEstado)
    {
        $util=new util();
        $campos=array("id_tipo_estado");
        $values=array($idEstado);
        $result = $util->update('ordenes', $campos, $values, "ordenes.id=".$idOrden);
    }

    public static function getOrdenesPendientes($id=null)
    {
        if($id!=null)
            $idBuscado=" AND ordenes.id = " .$id;
        else
            $idBuscado="";

        $util=new util();
        return $util->selectWhere3('ordenes,ordenes_estados,contratos,clientes',
            array("ordenes.id,ordenes.fecha_alta,ordenes_estados.nombre,ordenes_estados.id,clientes.nombre,clientes.id,clientes.apellidos,clientes.direccion, clientes.movil"),
                    "ordenes.id_contrato=contratos.id 
                    AND ordenes_estados.id=ordenes.id_tipo_estado 
                    AND contratos.id_cliente=clientes.id
                    $idBuscado
                    AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND ordenes.fecha_alta<=DATE(now()) AND ordenes.id_tipo_estado=1");
    }

    public static function getOrdenesSinAsignar()
    {
        $util=new util();
        $campos=array('ordenes.ID, clientes.NOMBRE','clientes.APELLIDOS', 'ordenes.FECHA_ALTA');
        $join='JOIN contratos ON contratos.ID = ordenes.ID_CONTRATO  
               JOIN clientes ON clientes.id = contratos.ID_CLIENTE ';
        $where='ordenes.ID NOT IN (SELECT ID_ORDEN from ordenes_usuario) AND contratos.ID_EMPRESA = '.$_SESSION['USER_ID'];


        return $util->selectJoin('ordenes', $campos,  $join, 'ordenes.FECHA_ALTA', $where);

    }


    public static function getOrden($id=null)
    {

        $util=new util();
        return $util->selectWhere3('ordenes,ordenes_estados,contratos,clientes',
            array("ordenes.id as id,ordenes.fecha_alta,ordenes_estados.nombre,ordenes_estados.id,clientes.nombre,clientes.id as idcliente, clientes.apellidos,clientes.direccion, clientes.movil"),
            "ordenes.id_contrato = contratos.id 
                    AND ordenes_estados.id = ordenes.id_tipo_estado 
                    AND contratos.id_cliente = clientes.id
                    AND ordenes.id = $id
                    AND contratos.id_empresa = ".$_SESSION['REVENDEDOR']);
    }


    // nota: obtengo las cabeceras del cablero

    public static function getOlts()
    {

        $util=new util();
        return $util->selectWhere3('tec_olt_x_empresa',
            array("ID_OLT"),
            "ID_EMPRESA = ".$_SESSION['REVENDEDOR']);
    }


    public static function getLineasOrden($id=null)
    {

        $util=new util();
        $campos=array('ordenes_lineas.ID_LINEA_DETALLE_CONTRATO','ordenes_lineas.ID_PRODUCTO',
                        'productos.NUMERO_SERIE as serial','productos_modelos.NOMBRE as modelo',
                        'productos_tipos.NOMBRE as tipo','contratos_lineas_detalles.ID_TIPO_SERVICIO as servicio',
                        'ordenes_lineas.ID','etiquetas.series.pon');

        $where='ordenes_lineas.id_orden='.$id.' AND ordenes.id_empresa='.$_SESSION['REVENDEDOR'];

        $join=' JOIN contratos_lineas_detalles ON contratos_lineas_detalles.ID = ordenes_lineas.ID_LINEA_DETALLE_CONTRATO  
                LEFT JOIN productos ON productos.ID =ordenes_lineas.ID_PRODUCTO 
                LEFT JOIN servicios_tipos ON servicios_tipos.ID = contratos_lineas_detalles.ID_TIPO_SERVICIO
                LEFT JOIN productos_modelos ON productos_modelos.ID = productos.ID_MODELO_PRODUCTO
                LEFT JOIN productos_tipos ON productos_tipos.ID = productos_modelos.ID_TIPO
                LEFT JOIN ordenes ON ordenes.ID = ordenes_lineas.id_orden 
                LEFT JOIN etiquetas.series ON etiquetas.series.pathnumber = productos.NUMERO_SERIE ';

        return $util->selectJoin('ordenes_lineas',$campos, $join,' servicios_tipos.ID ',$where);

    }

    public static function getLineasOrdenDetalles($id=null)
    {


        $util=new util();

        $campos=array('contratos_lineas_detalles.VALOR','contratos_lineas_detalles.ID_ATRIBUTO_SERVICIO',
            'servicios_tipos_atributos.NOMBRE','contratos_lineas_detalles.ESTADO');

        $join=' JOIN servicios_tipos_atributos on servicios_tipos_atributos.ID = contratos_lineas_detalles.ID_ATRIBUTO_SERVICIO ';

        $where=' contratos_lineas_detalles.id_servicio = 
                (select id_servicio from contratos_lineas_detalles where id = '.$id.') 
                and id_linea = (select id_linea from contratos_lineas_detalles where id = '.$id.')';

        return $util->selectJoin('contratos_lineas_detalles',$campos, $join,'',$where);

    }
}