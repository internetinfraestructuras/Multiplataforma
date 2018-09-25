<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 23/08/2018
 * Time: 13:54
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../config/util.php";

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

}