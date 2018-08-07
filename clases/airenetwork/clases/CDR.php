<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/07/2018
 * Time: 10:36
 */

class CDR
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
    public function __construct($url, $user, $pass)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
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
        print_r($result);
       // return json_encode($result);
    }


}