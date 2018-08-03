<?php
//require_once('../config/util.php');
require_once('../../config/def_tablas.php');

class Contrato
{
    //Devuelve la linea contratos_lineas de un contrato de una dicha linea con un idServicio especifico.
    public static function getLineaContratoServicio($idContrato,$idLinea,$idServicio)
    {

        $util=new util();

        return $util->selectWhere3("contratos_lineas", array("ID_TIPO","ID_ASOCIADO","ID_CONTRATO","PRECIO_PROVEEDOR", "BENEFICIO","IMPUESTO","PVP","PERMANENCIA"),
            "contratos_lineas.id_asociado=".$idServicio." AND contratos_lineas.id_contrato=".$idContrato." AND id=".$idLinea);
    }
    public static function getLineaContrato($idContrato,$idLinea)
    {

        $util=new util();

        return $util->selectWhere3("contratos_lineas", array("ID_TIPO","ID_ASOCIADO","ID_CONTRATO","PRECIO_PROVEEDOR", "BENEFICIO","IMPUESTO","PVP","PERMANENCIA"),
            "contratos_lineas.id_contrato=".$idContrato." AND id=".$idLinea);
    }

    //obtiene las linea de detalle de un contrato
    public static function getLineaDetalles($idLinea)
    {
        $util=new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO","ID_ATRIBUTO_SERVICIO","VALOR"),
            "contratos_lineas_detalles.id_linea=".$_POST['idLinea']);
    }
    //Establece una línea de un contrato de baja
    public static function setLineaContratoBaja($idContrato,$idLinea,$idServicio)
    {
        $util=new util();
        $campos=array('ESTADO','FECHA_BAJA');
        $values=array("2",date('Y-m-d'));
        $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=".$idServicio." AND id_contrato=".$idContrato." AND id=".$idLinea);
    }

    //Establece una línea detalle a baja
    public static function setLineaDetallesBaja($idLineaContrato)
    {
        $util=new util();
        $campos=array('ESTADO','FECHA_BAJA');
        $values=array(2,date('Y-m-d '));
        $result = $util->update('contratos_lineas_detalles', $campos, $values, "id_linea=".$_POST['idLinea']);
    }
    //Genera una nueva línea en un contrato
    public static function setNuevaLineaContrato($tipo,$servicio,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,$estado)
    {
        $util=new util();
        $t_contratos_lineas=array("ID_TIPO","ID_ASOCIADO","ID_CONTRATO","PRECIO_PROVEEDOR","BENEFICIO","IMPUESTO","PVP","PERMANENCIA","ESTADO","FECHA_ALTA","FECHA_BAJA");
        //SE SETEA LA NUEVA LÍNEA DE CONTRATO EN ALTA
        $values=array($tipo,$servicio,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,$estado,date('Y-m-d '),"");
        return $util->insertInto('contratos_lineas', $t_contratos_lineas, $values);
    }
    //Establece una nueva línea de detalle
    public static function setNuevaLineaDetalles($idLinea,$tipo,$atributo,$valor,$estado)
    {
        $util=new util();
        $t_contratos_lineas_detalles=array("ID_LINEA","ID_TIPO_SERVICIO","ID_ATRIBUTO_SERVICIO","VALOR","FECHA_ALTA","FECHA_BAJA","ESTADO");
        $values=array($idLinea,$tipo,$atributo,$valor,date('Y-m-d '),'',$estado);
        $util->insertInto('contratos_lineas_detalles', $t_contratos_lineas_detalles, $values);
    }

    //obtener productos asociados a una línea de contrato
    public static function getProductosLinea($idLinea)
    {
        $util=new util();
        return $util->selectWhere3("contratos_lineas_productos", array("ID_PRODUCTO","ESTADO"),
            "contratos_lineas_productos.id_linea=".$idLinea);
    }

    //Cambiar el producto a otra línea
    public static function cambiarLineaProducto($idLinea,$idLineaDestino)
    {
        $util=new util();
        $campos=array("ID_LINEA");
        $values=array($idLineaDestino);
        $result = $util->update('contratos_lineas_productos', $campos, $values, "id_linea=".$idLinea);
    }

    //Generar un anexo de contrato
    public static function generarAnexo($idContrato,$idServicio,$stringAnexo)
    {
        $util=new util();
        $t_contratos_anexos=array("ID_CONTRATO","FECHA","DESCRIPCION","FICHERO");
        $values=array($idContrato,date('Y-m-d h:i:s '),$stringAnexo."->".$idServicio." PARA EL CLIENTE","");
        $resAnexo= $util->insertInto('contratos_anexos', $t_contratos_anexos, $values);
    }
}