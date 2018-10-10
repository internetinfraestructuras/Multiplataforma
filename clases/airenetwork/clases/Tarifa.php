<?php
require_once "lib/nusoap.php";


class Tarifa
{
    var $url;
    var $user;
    var $pass;

    /**
     * Tarifa constructor.
     * @param $url
     * @param $user
     * @param $pass
     */
    public function __construct($url, $user, $pass)
    {
        $this->url="https://wscliente.airenetworks.es/ws/mv/gestMOVIL_2.php?wsdl";
        $this->user="B10452795";
        $this->pass="Telereq1430";
    }

    public function getAllTarifas()
    {
        $client = new nusoap_client($this->url,true);

        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        $data = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getTarifas", array($data));

        $error = $client->getError();
        if ($error)
            return $error;
        else
            return $result;
    }
    public function getTarifasStatus($status)
    {
        $client = new nusoap_client($this->url,true);

        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        $data = array("user" => $this->user, "pass" => $this->pass,"estado"=>$status);
        $result = $client->call("getTarifas", array($data));

        $error = $client->getError();

        if ($error)
          return $error;
        else
            return $result;

    }

    public function getTarifasPaginadas($paginas,$registros)
    {
        $client = new nusoap_client($this->url,true);
        $data = array("user" => $this->user, "pass" => $this->pass,"pagina"=>$paginas,"registros"=>$registros);
        $result = $client->call("getTarifas", array($data));
        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";
        var_dump($result);
        return json_encode($result);
    }
}