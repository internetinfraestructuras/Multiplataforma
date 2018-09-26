<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 22/08/2018
 * Time: 13:39
 */
ini_set('display_errors', 1);

error_reporting(E_ALL);

require_once '../config/define.php';
require_once ('./masmovil/MasMovilAPI.php');
require_once ('./../config/util.php');
require_once ('Servicio.php');
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

    public static function addNuevaLineaMasMovil($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                                                 $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                                                 $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                                                 $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta,$iccTarjeta,$idServicio)
    {


        $apiMasMovil=new MasMovilAPI();
        $rs=$apiMasMovil->getListadoClientes($dni);

        if(@$rs->activationCode==NO_EXISTE_CLIENTE_MASMOVIL)
        {
            sleep(1);
            $rs=$apiMasMovil->crearNuevoCliente($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta);

            if($rs->activationCode==OPERACION_OK_MASMOVIL)
            {

                $refCliente=$rs->customerId;
                $rs=$apiMasMovil->altaLineaMovil($refCliente,$iccTarjeta,"","");
            }
        }
        else
        {

            $refCliente=$rs->Client[0]->refCustomerId;
            $ser=new Servicio();
            $idExterno=Servicio::getIdExternoApi($idServicio);
            if($idExterno!=NULL)
            {
                $idExterno=$idExterno[0][0];
                $rs=$apiMasMovil->altaLineaMovil($refCliente,$iccTarjeta,$idExterno,"");

            }


        }

    }
    
}