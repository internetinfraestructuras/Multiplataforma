<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 11/09/2018
 * Time: 8:04
 */

class Empresa
{

    public static function getListadoEmpresas()
    {
        $util = new util();
        return $util->selectWhere3('empresas', array('ID'),'',"ID");
    }

    public static function getConfiguracionEmpresaFacturacion($idEmpresa)
    {
        $util = new util();
        return $util->selectWhere3('empresas_configuracion', array('DIA_FACTURACION','FACTURACION_AUTOMATICA')," id_empresa=$idEmpresa");
    }
}