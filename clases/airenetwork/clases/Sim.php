<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 24/07/2018
 * Time: 11:33
 */

class Sim
{
    var $url;
    var $user;
    var $pass;

    public function __construct($url, $user, $pass)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function getAllSims()
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }

        $datos = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getPoolSIM", array($datos));
        print_R($result);
        //return json_encode($result);
    }
    public function getSimsTel($numTel)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }

        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$numTel);
        $result = $client->call("getPoolSIM", array($datos));
        print_R($result);
        //return json_encode($result);
    }
}