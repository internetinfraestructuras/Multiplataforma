<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/09/2018
 * Time: 16:07
 */

class Factura
{
    //Crea la cabecera de un nuevo contrato
    public static function setNuevaFactura($idContrato,$idEmpresa,$impuesto,$descuento)
    {
        $util = new util();
        $tabla = array("NUMERO","ID_CONTRATO","ID_EMPRESA","ESTADO","IMPUESTO","DESCUENTO");

        $numero=self::getUltimaFacturaEmpresa($idEmpresa);

        $values = array($numero,$idContrato,$idEmpresa,1,$impuesto,$descuento);

        return $util->insertInto('facturas', $tabla, $values);
    }

    public static function setNuevaLineaFactura($idFactura,$importe,$impuesto,$concepto)
    {
        $util = new util();

        $tabla = array("ID_FACTURA","IMPORTE","IMPUESTO","CONCEPTO");
        $values = array($idFactura,$importe,$impuesto,$concepto);

        return $util->insertInto('facturas_lineas', $tabla, $values);
    }

    public static function getUltimaFacturaEmpresa($idEmpresa)
    {
        $util = new util();
        return intval($util->selectMax('facturas', 'NUMERO','ID_EMPRESA ='.$idEmpresa))+1;
    }

    public static function setImporteTotal($idFactura,$importe,$impuestos,$descuento,$total)
    {

        $util = new util();
        $campos = array("IMPORTE_BRUTO","TOTAL","IMPUESTO","DESCUENTO");
        $values = array($importe,$total,$impuestos,$descuento);
        return $util->update('facturas', $campos, $values,"id=$idFactura");
    }
    public static function setPagada($idEmpresa,$idFactura)
    {

        $util = new util();
        $campos = array("ESTADO");
        $values = array(ID_FACTURA_PAGADA);
        return $util->update('facturas', $campos, $values,"id=$idFactura AND id_empresa=$idEmpresa");
    }
    public static function getDiaFacturacion($idEmpresa)
    {
        $util = new util();
        return $util->selectWhere3('empresas_configuracion', array('DIA_FACTURACION'),'ID_EMPRESA ='.$idEmpresa);
    }

    public static function getFacturacionAutomatica($idEmpresa)
    {
        $util = new util();
        return $util->selectWhere3('empresas_configuracion', array('FACTURACION_AUTOMATICA'),'ID_EMPRESA ='.$idEmpresa);
    }

    public static function getDatosFactura($idFactura,$idEmpresa)
    {
        $util = new util();
        return $util->selectWhere3('facturas,contratos,clientes',
            array('facturas.NUMERO','facturas.FECHA','facturas.IMPUESTO','facturas.DESCUENTO','facturas.TOTAL',"facturas.ID_CONTRATO","clientes.nombre","clientes.apellidos","clientes.dni","clientes.id"),
            'facturas.ID_EMPRESA ='.$idEmpresa.' AND facturas.id='.$idFactura." 
        AND contratos.id=facturas.id_contrato AND contratos.id_cliente=clientes.id");
    }
    public static function getLineasFacturas($idFactura)
    {
        $util = new util();
        return $util->selectWhere3('facturas,facturas_lineas', array('facturas_lineas.id_linea_contrato','facturas_lineas.importe','facturas_lineas.descuento','facturas_lineas.impuesto'),
            ' facturas.id=facturas_lineas.id_factura AND facturas.id='.$idFactura);
    }
    public static function getFacturasRecurrentesMesCurso($idEmpresa)
    {
        $util = new util();
        $diaFacturacion=self::getDiaFacturacion($idEmpresa);
        $diaFacturacion=$diaFacturacion[0][0];
        $mesActual=date('m');
        $anio=date('Y');
        $mesAnterior=$mesActual-1;
        $fechaInicio=$anio."-".$mesAnterior."-".$diaFacturacion;
        $diaFacturacion++;
        $fechaFin=$anio."-".$mesActual."-".$diaFacturacion;



        return $util->selectWhere3('facturas', array('NUMERO','FECHA','IMPUESTO','DESCUENTO','TOTAL','ID_CONTRATO'),'ID_EMPRESA ='.$idEmpresa." AND facturas.fecha>='$fechaInicio' AND facturas.fecha<='$fechaFin' AND facturas.ID_CONTRATO IS NOT null");
    }

    public static function getFacturasMes($idEmpresa,$mesActual)
    {
        $util = new util();
        $diaFacturacion=self::getDiaFacturacion($idEmpresa);
        $diaFacturacion=$diaFacturacion[0][0];
        $anio=date('Y');
        $fechaFin=$anio."-".$mesActual."-".$diaFacturacion;

        if($mesActual==1)
        {
            $mesAnterior=12;
            $anio--;
        }
        else
        {
            $mesAnterior=$mesActual-1;
        }

        $diaFacturacion++;

        $fechaInicio=$anio."-".$mesAnterior."-".$diaFacturacion;

        return $util->selectWhere3('facturas', array('NUMERO','FECHA','IMPUESTO','DESCUENTO','TOTAL'),'ID_EMPRESA ='.$idEmpresa." AND facturas.fecha>='$fechaInicio' AND facturas.fecha<='$fechaFin'");
    }
}