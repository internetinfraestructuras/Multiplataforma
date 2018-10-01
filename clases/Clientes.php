<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 23/08/2018
 * Time: 13:54
 */

include_once "../config/util.php";

class Clientes
{
    public $util;

    public function __construct()
    {
        $this->util=new util();
    }



    public  function getDniDuplicado($dni)
    {


        return $this->util->selectMax('clientes',
            array("clientes.id"),
            "DNI = '".$dni."' AND id_empresa=".$_SESSION['REVENDEDOR']);
    }

    public  function getEmailDuplicado($email)
    {
        return $this->util->selectMax('clientes',
            array("clientes.id"),
            "EMAIL = '".$email."' AND id_empresa=".$_SESSION['REVENDEDOR']);
    }


    public  function getClienteAltaMasMovil($id)
    {
        return $this->util->selectJoin('clientes',
            array("NOMBRE","APELLIDOS",'TIPO_DOCUMENTO',"DNI",
                "FIJO","MOVIL","EMAIL","DIRECCION","municipios.municipio","provincias.provincia","pais.iso",
                "CP","BANCO","IBAN")," JOIN municipios ON municipios.id = clientes.LOCALIDAD 
                                            JOIN provincias ON provincias.id = clientes.PROVINCIA 
                                            JOIN pais ON pais.id = clientes.NACIONALIDAD ",
            "","clientes.ID = '".$id."'");
    }
}