<?php
require_once "Cliente.php";
require_once "Linea.php";
class AirNetwortk
{

    /**
     * AirNetwortk constructor.
     */
    public function __construct()
    {
    }

    /*
     * PRE:
     * POST:Nos devuelve el listado de clientes finales;
     */

/*
 * Obtiene un listado de clientes finales, parametros opcionales:
 * 1.DNI del cliente
 * 2.Tipo de cliente: 0 Residencial,1 Empresa,5 autonomo, 2 Extranjero.
 */


    public function getClienteDNI($dni)
    {
        $cliente=new Cliente($this->url,$this->user,$this->pass);
        return $cliente->getClientByDNI($dni);
    }

    public function getNumerosLibres()
    {

    }


    public function altaNuevoCliente()
    {

        $cliente = new nusoap_client($this->url,true);

        $datos=array(
            "user"=>$this->user,
            "pass"=>$this->pass,
            "suscriberType"=>$suscriberType,
            "marketingConsent"=>$marketingConsent,
            "documentType"=>$documentType,
            "fiscalId"=>$fiscalId,
            "name"=>$name,
            "contactName"=>$contactName,
            "contactFamilyName1"=>$contactFamilyName1,
            "contactFamilyName2"=>$contactFamilyName2,
            "birthday"=>$birthday,
            "contactDocumentType"=>$contactDocumentType,
            "contactFiscalId"=>$contactFiscalId,
            "emailAdress"=>$emailAdress,
            "contactPhone"=>$contactPhone,
            "adressRegion"=>$adressRegion,
            "addressProvince"=>$addressProvince,
            "addressCity"=>$addressCity,
            "addressPostalCode"=>$addressPostalCode,
            "AddressStreet"=>$addressStreet,
            "addressNumber"=>$addressNumber,
            "shippingAddressRegion"=>$shippingAddressRegion,
            "shippingAddressProvince"=>$shippingAddressProvince,
            "shippingAddressCity"=>$shippingAddressCity,
            "shippingAddressPostalCode"=>$shippingAddressPostalCode,
            "shippingAddressStreet"=>$shippingAddressStreet,
            "shippingAddressNumber"=>$shippingAddressNumber,
            "shippingAddressDescription"=>$shippingAddressDescription,
            "documentoName"=>$documentoName,
            "ccc_entidad"=>$ccc_entidad,"ccc_agencia"=>$ccc_agencia,"ccc_dc"=>$ccc_dc,"ccc_cuenta"=>$ccc_cuenta);


        $return = $cliente->call("setAltaClienteFinal",array($datos));
        $error = $cliente->getError();
        if ($error)  echo "<pre>".$error."</pre>";

print_r($return);


    }

    /*
     * PRE:
     * SIN PASAR PARAMETROS OBTENEMOS TODAS LAS LÍNEAS
     * SI PASAMOS EL TELEFONO OBTENEMOS LOS DATOS DE DICHA LÍNEA
     */
    public function consultarLineas($telefono=null)
    {
        $client = new nusoap_client($this->url,true);
        $datos = array("user" => $this->user, "pass" => $this->pass,"telefono"=>$telefono);
        $result = $client->call("getLineas", array($datos));

        $error = $client->getError();
        if ($error)
            echo "<pre>".$error."</pre>";
        print_r($result);
    }


}