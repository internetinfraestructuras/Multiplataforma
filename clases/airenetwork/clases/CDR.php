<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/07/2018
 * Time: 10:36
 */
require_once "lib/nusoap.php";
class CDRAire
{
    var $url;
    var $user;
    var $pass;

    /**
     * CDR constructor.
     * @param $url
     * @param $user
     * @param $pass
     */
    public function __construct()
    {
        $this->url="https://wscliente.airenetworks.es/ws/mv/gestMOVIL_2.php?wsdl";
        $this->user="B10452795";
        $this->pass="Telereq1430";
    }

    public function getDatosCDR($telefono,$anio,$mes,$tipoServicio)
    {


        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"mes"=>$mes,"anyo"=>$anio,"tipo_servicio"=>$tipoServicio,"t_origen"=>$telefono);

        $result = $client->call("getCDR", array($datos));

        return $result;
    }


}