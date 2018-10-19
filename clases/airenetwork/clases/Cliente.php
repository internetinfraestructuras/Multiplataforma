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
        $this->url=$url;
        $this->user=$user;
        $this->pass=$pass;
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
//        var_dump($error);

        if ($error)
            return $error;
        else
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
            return $error;
        else
            return $result;
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

        var_dump($datos);

       $return = $cliente->call("setAltaClienteFinal",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "crearCliente: <pre>".$error."</pre>";

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
/*
array(23) {
    ["user"]=>
  string(9) "B10452795"
    ["pass"]=>
  string(11) "Telereq1430"
    ["subscriberType"]=>
  string(1) "1"
    ["marketingConsent"]=>
  string(1) "4"
    ["documentType"]=>
  string(1) "1"
    ["fiscalId"]=>
  string(9) "31689245Y"
    ["name"]=>
  string(5) "RUBEN"
    ["contactName"]=>
  string(5) "RUBEN"
    ["contactFamilyName1"]=>
  string(8) "CORRALES"
    ["contactFamilyName2"]=>
  string(8) "CORBACHO"
    ["birthday"]=>
  string(10) "1974-05-15"
    ["contactDocumentType"]=>
  string(1) "1"
    ["contactFiscalId"]=>
  string(9) "31689245Y"
    ["emailAddress"]=>
  string(14) "info@ruben.com"
    ["contactPhone"]=>
  string(9) "691934413"
    ["addressRegion"]=>
  string(10) "Andalucía"
    ["addressProvince"]=>
  string(6) "Cádiz"
    ["addressCity"]=>
  string(10) "Bosque, El"
    ["addressPostalCode"]=>
  string(5) "11670"
    ["addressStreet"]=>
  string(14) "CALLE JEREZ, 7"
    ["addressNumber"]=>
  string(1) "0"
    ["documento_name"]=>
  string(0) ""
    ["documento"]=>
  string(0) ""
}
*/