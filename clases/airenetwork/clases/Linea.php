<?php
require_once "lib/nusoap.php";


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
        $this->url=$url;
        $this->user=$user;
        $this->pass=$pass;
    }
    public function getAllLineas()
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);

        $err=$client->getError();

        if($err)
        {
            echo "ERROR";
        }
        echo "ERROR";
        $datos = array("user" => $this->user, "pass" => $this->pass);
        $result = $client->call("getLineas", array($datos));

        var_dump($result);


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


        return $result;
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


        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>array($telefono));
        $result = $client->call("getLineas", array($datos));

        if($err)
            return $err;
        else
            return $result;
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

    public function getSolicitudesLineas($filtro=null)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"tipo"=>$filtro,"ordenacion"=>true);
        $result = $client->call("getLineasSolicitud", array($datos));

        return $result;
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
        $result = $client->call("setConsumoMaximo", array($datos));

        return $result;
    }

    public function setCorteImpago($telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("solicitud_corte_impago", array($datos));

        return $result;
    }

    public function setCancelarCorteImpago($telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("cancelar_solicitud_impago", array($datos));

        return $result;
    }

    public function setCancelarSolicitudLinea($codigoSolicitud)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"codigo"=>$codigoSolicitud);
        $result = $client->call("cancelLineasSolicitud", array($datos));

        return $result;
    }

    public function getDocumentosSolicitud($codigoSolicitud,$tipoDocumento)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();
        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"codigo_linea"=>$codigoSolicitud,"tipo_documento"=>$tipoDocumento);
        $result = $client->call("obtenerDocumento", array($datos));

        return $result;
    }

    public function setRestablecerCorteImpago($telefono)
    {

        $client = new nusoap_client($this->url,$proxyhost=false,$proxyport=false,$proxyusername=false,$proxupassword=false,$timeout=0,$response_timeout=160);
        $err=$client->getError();

        if($err)
        {
            echo "ERROR";
        }
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("restablecer_corte_impago", array($datos));

        return $result;
    }

    public function setAltaNueva($tarifa,$tipoCliente,$nif,$icc,$dc,$telefono,$codigoReserva)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "tarifa"=>$tarifa,
            "subscriberType"=>$tipoCliente,
            "nif"=>$nif,
            "icc"=>$icc,
            "digito_control"=>$dc,
            "telefono"=>$telefono,
            "codigo_reserva"=>$codigoReserva,
        );

        $return = $cliente->call("setAltaLineaNueva",array($datos));
        $error = $cliente->getError();
        if ($error)
            return $error;
        else
            return $return;

    }
    public function setAltaPortabilidad($tarifa,$tipoCliente,$nif,$icc,$dc,$telefono,$modalidadActual,$iccOrigen,$dcOrigen)
    {
        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "tarifa"=>$tarifa,
            "subscriberType"=>$tipoCliente,
            "nif"=>$nif,
            "icc"=>$icc,
            "digito_control"=>$dc,
            "telefono"=>$telefono,
            "modalidad_actual"=>$modalidadActual,
            "icc_origen"=>$iccOrigen,
            "dc_origen"=>$dcOrigen,
            "responseNsolicitud"=>'S'

        );
        $return = $cliente->call("setAltaLineaPortabilidad",array($datos));

        $error = $cliente->getError();

        if ($error)
            return $error;
        else
            return $return;
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