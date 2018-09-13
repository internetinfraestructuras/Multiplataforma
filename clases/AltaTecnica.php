<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 22/08/2018
 * Time: 13:39
 */

//ini_set('display_errors', '1');

class AltaTecnica
{
    public static function addNuevoFijo($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,$nombreGrupoRecarga,
                                        $paqueteDestino,$numero)
    {

        require_once ($_SERVER['DOCUMENT_ROOT'].'clases/telefonia/classTelefonia.php');

        $telefonia=new Telefonia();

        if(!$telefonia->existeCliente($cifCliente))
        {
            try
            {
                $telefonia->addCliente($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,"","","SPRE",$nombreGrupoRecarga);
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }

            try
            {
                $troncal=$telefonia->addLinea($cifCliente,"","",$numero);
//                echo $troncal;
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
            try
            {
                $telefonia->setTarifasTroncalFromPaqueteDestinos($troncal,$paqueteDestino,"0");
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
        }
        else
        {

            try
            {
                $troncal=$telefonia->addLinea($cifCliente,"","",$numero);
//                echo "<hr/>la troncal es".$troncal;
//                echo "<hr>";
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
            try
            {
                $telefonia->setTarifasTroncalFromPaqueteDestinos($troncal,$paqueteDestino,"0");
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
        }

    }

}