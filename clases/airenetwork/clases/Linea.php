<?php

class Linea
{
    var $url;
    var $user;
    var $pass;

    /**
     * Linea constructor.
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
    public function getAllLineas()
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }

        $datos = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }
    public function getNumerosLibres()
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }

        $datos = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getPoolMsisdn", array($datos));


        return json_encode($result);
    }

    public function getLineasPorDni($dni)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }

        $datos = array("user" => $this->user, "pass" => $this->pass,"fiscalId"=>$dni);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }

    public function getLineasActivas($activa)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"activo"=>$activa);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }

    public function getLineaNumero($telefono)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>array($telefono));
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }
    public function getLineaTipoCliente($tipoCliente)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"subscriberType"=>$tipoCliente);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }
    public function getLineaFechaAlta($fecha)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"fecha"=>$fecha);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }

    public function getLineaConsumoMaximo($consumo)
    {
        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"consumo_maximo"=>$consumo);
        $result = $client->call("getLineas", array($datos));


        return json_encode($result);
    }

    public function setAlertaFacturacion($alerta,$telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"alerta_facturacion"=>$alerta,"telefono"=>$telefono);
        $result = $client->call("setAlertaFacturacion", array($datos));

        return json_encode($result);
    }

    public function getSolicitudesLineas()
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getLineasSolicitud", array($datos));

        return json_encode($result);
    }

    public function getSolicitudesLineaTelefono($telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("getLineasSolicitud", array($datos));

        return json_encode($result);
    }
    public function getSolicitudesLineaSim($sim)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"icc"=>$sim);
        $result = $client->call("getLineasSolicitud", array($datos));

        return json_encode($result);
    }
    public function getServiciosLinea($telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("getServicios", array($datos));

        return json_encode($result);
    }
    public function setConsumoMaximo($telefono,$consumoMaximo)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono,"consumo_maximo"=>$consumoMaximo);
        $result = $client->call("getServicios", array($datos));

        return json_encode($result);
    }

    public function setAltaNueva($requestBody)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "tarifa"=>$requestBody[0]["tarifa"],
            "subscriberType"=>$requestBody[0]["tipoCliente"],
            "nif"=>$requestBody[0]["nif"],
            "icc"=>$requestBody[0]["icc"],
            "digito_control"=>$requestBody[0]["digito_control"],
            "telefono"=>$requestBody[0]["telefono"],
            "codigo_reserva"=>$requestBody[0]["codigo_reserva"],
        );
        var_dump($datos);
        $return = $cliente->call("setAltaLineaNueva",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

        echo json_encode($return);
    }
    public function setAltaPortabilidad($requestBody)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "tarifa"=>$requestBody[0]["tarifa"],
            "subscriberType"=>$requestBody[0]["tipoCliente"],
            "nif"=>$requestBody[0]["nif"],
            "icc"=>$requestBody[0]["icc"],
            "digito_control"=>$requestBody[0]["digito_control"],
            "telefono"=>$requestBody[0]["telefono"],
            "modalidad_actual"=>$requestBody[0]["modalidad_actual"],
            "icc_origen"=>$requestBody[0]["icc_origen"],
            "dc_origen"=>$requestBody[0]["dc_origen"]

        );
        var_dump($datos);
        $return = $cliente->call("setAltaLineaPortabilidad",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

        echo json_encode($return);
    }

    public function setCambioTitular($requestBody)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "tarifa"=>$requestBody[0]["tarifa"],
            "subscriberType"=>$requestBody[0]["tipoCliente"],
            "nif"=>$requestBody[0]["nif"],
            "telefono"=>$requestBody[0]["telefono"]);

        var_dump($datos);
        $return = $cliente->call("setAltaLineaCambioTitular",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

        echo json_encode($return);
    }

    public function setDocumentos($requestBody)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "subscriberType"=>$requestBody[0]["tipoCliente"],
            "nif_cliente"=>$requestBody[0]["nif"],
            "codigo_solicitud"=>$requestBody[0]['idSolicitud'],
            "documento"=>$requestBody[0]['documento'],
            "tipo_documento"=>$requestBody[0]['tipoDocumento'],
            "documento_name"=>$requestBody[0]["nombre"]);

        var_dump($datos);
        $return = $cliente->call("subirDocumento",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

        echo json_encode($return);
    }

}