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

    public static function getConfiguracionAireNetworks($idEmpresa)
    {
        $util = new util();
        return $util->selectWhere3('empresas_configuracion', array('AIRENETWORKS','USUARIO_AIRENETWORKS','PASS_AIRENETWORKS','URL_AIRENETWORKS')," id_empresa=$idEmpresa");
    }

    public static function setConfiguracionAireNetworks($idEmpresa,$usuario,$pass,$url)
    {
        $util = new util();
        $values=array($usuario,$pass,$url);

        $campos=array('USUARIO_AIRENETWORKS','PASS_AIRENETWORKS','URL_AIRENETWORKS');
        return $util->update('empresas_configuracion', $campos, $values, "id_empresa=$idEmpresa");
    }
}