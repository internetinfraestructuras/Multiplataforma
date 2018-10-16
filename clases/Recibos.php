<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 15/10/2018
 * Time: 17:06
 */


class Recibo
{
    public static function getTiposRecibos()
    {
        $util = new util();
        return $util->selectWhere3("recibos_tipos",array("id","nombre"));

    }

    public static function getRecibosEmpresaMesActual($idEmpresa,$idContrato)
    {
        $util = new util();
        $mesActual=date('m');
        return $util->selectWhere3("recibos,recibos_lineas",
            array("recibos.id","recibos_lineas.id as id_linea"),
            "recibos.id=recibos_lineas.id_recibo AND recibos.id_empresa=$idEmpresa 
         AND recibos_lineas.id_contrato=$idContrato AND MONTH(recibos_lineas.FECHA_COBRO) = $mesActual");

    }

    public static function setIdFacturaRecibo($idEmpresa,$idRecibo,$idLineaRecibo,$idFactura)
    {
        $util = new util();
        $campos = array("ID_FACTURA");
        $values = array($idFactura);
        return $util->update('recibos_lineas', $campos, $values,"id_recibo=$idRecibo AND id=$idLineaRecibo");
    }
}