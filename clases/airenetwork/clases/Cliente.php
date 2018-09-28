<?php

require_once "lib/nusoap.php";

class Cliente
{
    var $url;
    var $user;
    var $pass;

    /**
     * Cliente constructor.
     * @param $url
     * @param $user
     * @param $pass
     */
    public function __construct($url, $user, $pass)
    {
        $this->url="https://wscliente.airenetworks.es/ws_desarrollo/mv/gestMOVIL_2.php?wsdl";
        $this->user="B10452795";
        $this->pass="aSo2Onc03H";
    }

    /**
     * Cliente constructor.
     * @param $url
     */


    public function getAllClients()
    {
        $client = new nusoap_client($this->url,true);
        $data = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getCliente", array($data));

        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";

        return json_encode($result);
    }

    /*
     * OBTIENE LOS CLIENTES POR UN DNI DADO
     */
    public function getClientByDNI($dni)
    {
        $client = new nusoap_client($this->url,true);
        $data = array("user" => $this->user, "pass" => $this->pass,"fiscalId"=>$dni);
        $result = $client->call("getCliente", array($data));
        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";

        return $result;
    }

    public function getClientByName($name)
    {
        $client = new nusoap_client($this->url,true);
        $data = array("user" => $this->user, "pass" => $this->pass,"name"=>$name);
        $result = $client->call("getCliente", array($data));
        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";

        return json_encode($result);
    }

    public function getClientesPaginados($paginas,$registros)
    {
        $client = new nusoap_client($this->url,true);
        $data = array("user" => $this->user, "pass" => $this->pass,"pagina"=>$paginas,"registros"=>$registros);
        $result = $client->call("getCliente", array($data));
        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";
        var_dump($result);
        return json_encode($result);
    }

    public function crearCliente($tipoCliente,$consentimiento,$tipoDocumento,$numeroDocumento,$nombre,$apellido1,$apellido2,$fechaNacimiento,$email,$telefono,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento)
    {

        $cliente = new nusoap_client($this->url,true);

        $datos= array
        (
            "user" => $this->user,
            "pass" => $this->pass,
            "subscriberType" => $tipoCliente,
            "marketingConsent" => $consentimiento,
            "documentType" => $tipoDocumento,
            "fiscalId" => $numeroDocumento,
            "name" => $nombre,
            "contactName" => $nombre,
            "contactFamilyName1" => $apellido1,
            "contactFamilyName2" =>  $apellido2,
            "birthday" => $fechaNacimiento,
            "contactDocumentType" => $tipoDocumento,
            "contactFiscalId" => $numeroDocumento,
            "emailAddress" => $email,
            "contactPhone" => $telefono,
            "addressRegion" => $region,
            "addressProvince"=> $provincia,
            "addressCity" => $ciudad,
            "addressPostalCode" => $cp,
            "addressStreet" => $direccion,
            "addressNumber" => $numero,
            "documento_name"=>$docNombre,
            "documento"=>$documento);

       $return = $cliente->call("setAltaClienteFinal",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

        echo json_encode($return);
    }

    public function modificarCliente($rs)
    {

        $cliente = new nusoap_client($this->url,true);

        $datos= array
        (
            "user" => $this->user,
            "pass" => $this->pass,
            "subscriberType" => $rs[0]['tipoCliente'],
            "marketingConsent" => $rs[0]['consentimiento'],
            "documentType" => $rs[0]['tipoDocumento'],
            "fiscalId" => $rs[0]['fiscal'],
            "name" => $rs[0]['nombre'],
            "contactName" => $rs[0]['nombre'],
            "contactFamilyName1" => $rs[0]['apellido1'],
            "contactFamilyName2" =>  $rs[0]['apellido2'],
            "birthday" => $rs[0]['fechaNac'],
            "contactDocumentType" => $rs[0]['tipoDocumento'],
            "contactFiscalId" => $rs[0]['fiscal'],
            "emailAddress" => $rs[0]['email'],
            "contactPhone" => $rs[0]['telefono'],
            "addressRegion" => $rs[0]['region'],
            "addressProvince"=> $rs[0]['provincia'],
            "addressCity" => $rs[0]['ciudad'],
            "addressPostalCode" => $rs[0]['cp'],
            "addressStreet" => $rs[0]['direccion'],
            "addressNumber" => $rs[0]['numero'],
            "documento_name"=>$rs[0]['docNombre'],
            "documento"=>$rs[0]['documento']);

        $return = $cliente->call("editCliente",array($datos));
        $error = $cliente->getError();

        if ($error)
            return $error;
        else
            return $return;

    }



}