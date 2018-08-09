<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/07/2018
 * Time: 13:46
 */

class Exception
{

    public static function getErroresAltaCliente($cod)
    {
        switch($cod)
        {
            case "0007":
                return array("codigo"=>$cod,"descripcion"=>"Provincia incorrecta");
            break;
            case "0008":
                return array("codigo"=>$cod,"descripcion"=>"Error obtener provincia");
            break;
            case "0009":
                return array("codigo"=>$cod,"descripcion"=>"Provincia envio incorrecta");
            break;
            case "0010":
                return array("codigo"=>$cod,"descripcion"=>"Error obtener provincia de envio");
                break;
            case "0011":
                return array("codigo"=>$cod,"descripcion"=>"No hay archivo de documentacion");
                break;
            case "0012":
                return array("codigo"=>$cod,"descripcion"=>"Formato invalido documentación");
            break;
            case "0013":
                return array("codigo"=>$cod,"descripcion"=>"DNI incorrecot");
                break;
            case "0014":
                return array("codigo"=>$cod,"descripcion"=>"DAtos de contacto incorrectos.");
                break;
            case "0015":
                return array("codigo"=>$cod,"descripcion"=>"DAtos de contacto incorrectos.");
                break;
            case "0023":
                return array("codigo"=>$cod,"descripcion"=>"Faltan datos personales obligatorios");
             break;




        }
    }

    public static function errorLlamada($codigo,$mensaje)
    {
        return  json_encode(array("status"=>$codigo,"message"=>$mensaje));
    }
}