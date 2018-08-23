<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 22/08/2018
 * Time: 13:39
 */

class AltaTecnica
{

    public static function addNuevoFijo($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,$nombreGrupoRecarga,$paqueteDestino,$numero)
    {


        $telefonia=new Telefonia();

        $telefonia->addCliente($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,"","","SPRE",$nombreGrupoRecarga);

        $troncal=$telefonia->addLinea($cifCliente,"","",$numero);

        $telefonia->setTarifasTroncalFromPaqueteDestinos($troncal,$paqueteDestino,"0");

    }
}