<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/08/2018
 * Time: 13:36
 */
require_once 'FTP.php';
require_once 'FileProcess.php';
require_once 'CDRLinea.php';
require_once 'CDR.php';

class MasMovilAPI
{

    var $servicio;
    var $parametrosCliente;
    var $resellerId;
    var $pass;


    /**
     * MasMovilAPI constructor.
     */
    public function __construct()
    {
        $this->servicio="http://dev-wsr.xtratelecom.es/cableoperador/wsdl/";

        $this->parametrosCliente= array(
            'soap_version' => SOAP_1_1,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding' => 'UTF-8',
            'trace' => 0,
            'exceptions' => false,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        );

        $this->resellerId="216358";
        $this->pass="ksRsJjH039qCJ";
        $this->ftpServer="ftp.xtratelecom.es";
        $this->userFtp="c216358";
        $this->passFtp="8roA1WK6";


    }



    private function getTimeStamp()
    {
        $fecha = new DateTime();
        return $fecha->getTimestamp();
    }

    public function ping($mensajePruebas)
    {

        $parametros=array();
        $parametros['soap_request']=array("Operation"=>array("instruction"=>array("message"=>$mensajePruebas)));
        $client = new SoapClient($this->servicio."cablePing-test.wsdl", $this->parametrosCliente);
        $resultado=$client->ping($parametros);
        // var_dump($client->__getFunctions());
        return json_encode($resultado->return);
    }

    /*
     * =================================================================================================================
     * |                                                                                                               |
     * |                        FUNCIONES PARA GESTIONAR CLIENTES DE LA PLATAFORMA MÁS MOVIL                           |
     * |                                                                                                               |
     * =================================================================================================================
     */
    /*
     * Función que nos devuelve un listado de clientes.
     * $nombre=Puede ser nombre cliente/nombre empresa/nif/cif
     * ESTADOS: 000 =ACTIVO
     *          300 =BLOQUEADO
     *          400 =BAJA
     */
    public function getListadoClientes($nombre=null,$numero=null,$estado=null)
    {

        $ts=$this->getTimeStamp();

        $parametros=array();

        $arrayFind=array("findName"=>$nombre,
            "phone"=>$numero,
            "status"=>$estado,
            "activateDate"=>"");

        $paramsInstruction=array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "operationType"=>"FIND",
            "refCustomerId"=>"");


        $parametros['soap_request']=array("Operation"=>array("instruction"=>$paramsInstruction,"find"=>$arrayFind));
        $client = new SoapClient($this->servicio."cableCustomersFind-test.wsdl", $this->parametrosCliente);

        $resultado=$client->cableCustomerMaintenance($parametros);
        return json_encode($resultado->return->clientsList);
    }



    /*
     * Crear un nuevo cliente
     * CODIGO TIPO DOCUMENTO: DNI,CIF,RES,PAS,NIE
     */
    public function crearNuevoCliente($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                                      $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                                      $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                                      $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "operationType"=>"NEW");
        $individual=array("Name"=>$nombre,"companyName"=>$nombreEmpresa,"identityType"=>$tipoDocumento,"identityValue"=>$dni);
        $contact=array("contactName"=>$nombreContacto,"contactPhone"=>$telContacto,"contactMobile"=>$movilContacto,"contactFax"=>$faxContacto,"contactEmail"=>$emailContacto);
        $address=array("streetName"=>$calle,"locality"=>$localidad,"postalTown"=>$codigoProvincia,"country"=>$codigoPais,"postcode"=>$codigoPostal);
        $bankDetails=array("holderName"=>$titularCuenta,"bankAccountName"=>$nombreBanco,"bankCode"=>$codigoBanco,"branchCode"=>$oficina,"checkDigit"=>$digitoControl,"bankAccountNumber"=>$numeroCuenta);
        $services=array("defaultTariffPlan"=>"");
        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("individualDetails"=>$individual,"contact"=>$contact,"address"=>$address,"bankDetails"=>$bankDetails,"services"=>$services)));


        $client = new SoapClient($this->servicio."cableCustomersNew-test.wsdl", $this->parametrosCliente);
        $resultado=$client->cableCustomerMaintenance($parametros);
        return json_encode($resultado->return);
    }
    public function modificarCliente($refCliente,$nombre,$nombreEmpresa,$tipoDocumento,$dni,
                                     $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                                     $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                                     $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "operationType"=>"UPDATE",
            "refCustomerId"=>$refCliente);

        $individual=array("Name"=>$nombre,"companyName"=>$nombreEmpresa,"identityType"=>$tipoDocumento,"identityValue"=>$dni);
        $contact=array("contactName"=>$nombreContacto,"contactPhone"=>$telContacto,"contactMobile"=>$movilContacto,"contactFax"=>$faxContacto,"contactEmail"=>$emailContacto);
        $address=array("streetName"=>$calle,"locality"=>$localidad,"postalTown"=>$codigoProvincia,"country"=>$codigoPais,"postcode"=>$codigoPostal);
        $bankDetails=array("holderName"=>$titularCuenta,"bankAccountName"=>$nombreBanco,"bankCode"=>$codigoBanco,"branchCode"=>$oficina,"checkDigit"=>$digitoControl,"bankAccountNumber"=>$numeroCuenta);
        $services=array("defaultTariffPlan"=>"");
        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("individualDetails"=>$individual,"contact"=>$contact,"address"=>$address,"bankDetails"=>$bankDetails,"services"=>$services)));


        $client = new SoapClient($this->servicio."cableCustomersUpdate-test.wsdl", $this->parametrosCliente);
        $resultado=$client->cableCustomerMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function bajaCliente($refCliente)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "operationType"=>"DELETE",
            "refCustomerId"=>$refCliente);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions));


        $client = new SoapClient($this->servicio."cableCustomersDelete-test.wsdl", $this->parametrosCliente);
        $resultado=$client->cableCustomerMaintenance($parametros);
        return json_encode($resultado->return);
    }

    /*
 * =================================================================================================================
 * |                                                                                                               |
 * |                        FUNCIONES PARA ALTAS,BAJAS,SUSPENSIONES Y ACTIVACIÓN LINEAS MÓVILES                    |
 * |                                                                                                               |
 * =================================================================================================================
 */


    public function altaLineaMovil($refCliente,$icc,$perfilProducto,$bonos)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"NEWLINE");

        $lineDetails=array("iccid"=>$icc);
        $lineProducts=array("productProfile"=>$perfilProducto,"bonosAlta"=>$bonos);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails,"lineProductDetails"=>$lineProducts)));


        $client = new SoapClient($this->servicio."cableMsisdnsNewline-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function suspensionLineaMovil($refCliente,$msid)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"SERVICE");

        $lineDetails=array("msisdn"=>$msid);
        $lineServices=array("serviceAction"=>"J");

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails,"lineServices"=>$lineServices)));


        $client = new SoapClient($this->servicio."cableMsisdnsErased-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function reactivacionLineaMovil($refCliente,$msid)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"SERVICE");

        $lineDetails=array("msisdn"=>$msid);
        $lineServices=array("serviceAction"=>"R");

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails,"lineServices"=>$lineServices)));


        $client = new SoapClient($this->servicio."cableMsisdnsErased-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function bajaLineaMovil($refCliente,$msid)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"ERASED");

        $lineDetails=array("msisdn"=>$msid);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails)));


        $client = new SoapClient($this->servicio."cableMsisdnsErased-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }



    /*
     * Gestiona los estados de las líneas móviles
     * $serviceAction:
     * J: Call Barring,restringir llamadas salientes, si recibe llamads.
     * H: Hot line, todas las llamadas que realicen van al call center.
     * F: Fraude. No se pueden recibir ni realizar llamadas.
     * X: Hurto. No se pueden recibir ni realizar llamadas.
     * R: Desbloqueo de la línea.
     */
    public function gestionEstados($refCliente,$msid,$serviceAction)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"SERVICE");

        $lineDetails=array("msisdn"=>$msid);
        $lineServices=array("serviceAction"=>$msid);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails,"lineServices"=>$lineServices)));


        $client = new SoapClient($this->servicio."cableMsisdnsServices-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function buscarLineaIcc($refCliente,$iccid)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"FIND");

        $find=array("iccid"=>$iccid,"msisdn"=>"","status"=>"","activateDate"=>"");


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"find"=>$find));


        $client = new SoapClient($this->servicio."cableMsisdnsFind-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    public function cambioProducto($refCliente,$fecha,$msid,$bonos)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "fechaCambioProducto"=>$fecha,
            "operationType"=>"CHANGE_PRODUCT");

        $lineDetails=array("msisdn"=>$msid);
        $lineServices=array("productProfile"=>$msid,"bonosAlta"=>$bonos);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails,"lineProductDetails"=>$lineServices)));


        $client = new SoapClient($this->servicio."cableMsisdnsChangeProduct-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    /*
     * BUSCA UNA LINEA MOVIL POR ICC O POR MSIDNS
     * ESTADOS:
     *  A:Linea Activa
     *  B: Linea baja
     *  D: Desactivada
     *  S: suspension
     *  J: Bloque Call Barring
     *  R: Bloqueo por riesgo
     *  L: Bloqueo temporal
     */
    public function getLineasMsisdnsIccids($refCliente,$msidns,$icc,$estado)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"FIND");

        $find=array("msisdn"=>$msidns,"iccid"=>$icc,"status"=>$estado,"activateDate"=>"");

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"find"=>$find));


        $client = new SoapClient($this->servicio."cableMsisdnsFind-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    /*
     * Activación/desactivación de roaming a una línea.
     * $activar A: Activación roaming
     * D: Desactivación roaming.
     */
    public function setRoaming($refCliente,$msidns,$activar,$observaciones)
    {
        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"FIND");

        $details=array("lineDetails"=>array("msisdn"=>$msidns),"roaming"=>array("activar"=>$activar,"observations"=>$observaciones));



        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>$details));


        $client = new SoapClient($this->servicio."cableMsisdnsRoaming-test.wsdl", $this->parametrosCliente);
        $resultado=$client->msisdnsMaintenance($parametros);
        return json_encode($resultado->return);
    }

    /*
 * =================================================================================================================
 * |                                                                                                               |
 * |                        FUNCIONES PARA UTILES LINEAS MÓVILES                                                   |
 * |                                                                                                               |
 * =================================================================================================================
 */


    public function getListadoProductos($refCliente,$icc,$porta)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"GET_PRODUCT");

        $lineDetails=array("iccid"=>$icc);
        $getProductPort=array("getProductPort"=>$porta);

        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("auxData"=>$porta)));


        $client = new SoapClient($this->servicio."cableMsisdnsUtilsGetPro-test.wsdl", $this->parametrosCliente);

        $resultado=$client->msisdnUtilsMaintenance($parametros);
        // var_dump($resultado);
        return json_encode($resultado->return);
    }

    /*
 * =================================================================================================================
 * |                                                                                                               |
 * |                        FUNCIONES PARA PORTABILIDADES MOVILES                                                  |
 * |                                                                                                               |
 * =================================================================================================================
 */
    public function getListadoOperadores($refCliente)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"GET_OPE");


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions));


        $client = new SoapClient($this->servicio."cableMsisdnsPortingGetOpe-test.wsdl", $this->parametrosCliente);

        $resultado=$client->msisdnsPortingMaintenance($parametros);

        return json_encode($resultado->return);
    }
    public function getListadoPortabilidades($refCliente,$donante,$msisdns,$numero,$nombreCliente,$fechaDesde,$fechaHasta)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"LISTPOR");

        $portDetails=array("fromServiceProvider"=>$donante,"fromPhoneNumber"=>$msisdns,"Contract"=>$numero,"name"=>$nombreCliente,"sinceDate"=>$fechaDesde,"untilDate"=>$fechaHasta);


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("portDetails"=>$portDetails)));


        $client = new SoapClient($this->servicio."cableMsisdnsPortingListPor-test.wsdl", $this->parametrosCliente);

        $resultado=$client->msisdnsPortingMaintenance($parametros);

        return json_encode($resultado->return);
    }
    public function altaPortabilidad($refCliente,$numero)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"DETPOR");

        $portDetails=array("Contract"=>$numero);


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("portDetails"=>$portDetails)));


        $client = new SoapClient($this->servicio."cableMsisdnsPortingDetPor-test.wsdl", $this->parametrosCliente);

        $resultado=$client->msisdnsPortingMaintenance($parametros);

        return json_encode($resultado->return);
    }

    /*
 * =================================================================================================================
 * |                                                                                                               |
 * |                        FUNCIONES PARA GESTIÓN DE LÍNEAS MOVILES                                               |
 * |                                                                                                               |
 * =================================================================================================================
 */

    public function getPeticionRiesgo($refCliente,$msisdns)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"GETRISK");

        $lineDetails=array("msisdn"=>$msisdns);


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"activate"=>array("lineDetails"=>$lineDetails),"riskService"=>""));


        $client = new SoapClient($this->servicio."cableMsisdnsRiskGetRisk-test.wsdl", $this->parametrosCliente);

        $resultado=$client->msisdnsRiskMaintenance($parametros);

        return json_encode($resultado->return);
    }

    //public function getPeticionRiesgo($refCliente,$msisdns)


    /*
 * =================================================================================================================
 * |                                                                                                               |
 * |                        FUNCIONES PARA GESTIÓN DE CAMBIOS DE SIM EN UN MSISDNS                                 |
 * |                                                                                                               |
 * =================================================================================================================
 */
    /*
     * El proceso de cambio no es inmediato, transcurridos unos minutos consultar el estado de cambios
     * El motivo de cambio puede ser:
     * PER:Perdida tarjeta.
     * ROT: Rotura/deterioro.
     * ROB: Robo
     * R4G: REmplazo a 4g
     * OTH: Otros
     */
    public function peticionCambioIccparaMsisdn($refCliente,$msisdns,$iccNuevo,$motivoCambio)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"CHANGE_ICCID");

        $changeIccd=array("msisdn"=>$msisdns,"iccidReplacement"=>$iccNuevo,"changeReason"=>$motivoCambio);


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"changeIccid"=>$changeIccd));


        $client = new SoapClient($this->servicio."cableIccidChange-test.wsdl", $this->parametrosCliente);

        $resultado=$client->iccidMaintenance($parametros);

        return json_encode($resultado->return);
    }

    public function getEstadoCambioIccid($refCliente,$msisdns,$iccNuevo,$fechaCambio)
    {

        $ts=$this->getTimeStamp();
        $parametros=array();
        $instructions= array("timeStamp"=>$ts,
            "resellerId"=>$this->resellerId,
            "resellerPin"=>$this->pass,
            "branchId"=>"",
            "posId"=>"",
            "transactionId"=>$ts,
            "refCustomerId"=>$refCliente,
            "operationType"=>"CHANGE_ICCID");

        $changeIccd=array("msisdn"=>$msisdns,"iccidReplacement"=>$iccNuevo,"dateFind"=>$fechaCambio);


        $parametros['soap_request']=
            array("Operation"=>
                array("instruction"=>$instructions,"find"=>$changeIccd));


        $client = new SoapClient($this->servicio."cableIccidFind-test.wsdl", $this->parametrosCliente);

        $resultado=$client->iccidMaintenance($parametros);

        return json_encode($resultado->return);
    }
    /*
* =================================================================================================================
* |                                                                                                               |
* |                                        FUNCIONES PARA GESTIONES DE CDR´S                                      |
* |                                                                                                               |
* =================================================================================================================
*/
    public function getCDRFTP()
    {
        $ftp=new FTP();
        $ftp->conectar();
        $ftp->descargarCDRDia();

    }

    /*
     * OBTIENE EL CDR DE LA LÍNEA DÍA A DÍA DEL MES EN CURSO
     */
    public function getCDRMesActualDias($msidn)
    {
        $primerDia= $this->getPrimerDiaMes();
        $cdr=new CDR();
        $hoy=date('d');

        for($i=$primerDia;$i<$hoy;$i++)
            $cdr->setLineas($this->getCDRLineaDia($msidn,$this->getFechaMesActual($i)));


        return $cdr;

    }

    /* RECORRE EL FICHERO Y NOS DEVUELVE LAS LÍNEAS DE ESE FICHERO*/
    private function getCDRLineaDia($msidn,$fecha)
    {

        $nombreFichero=$fecha.".txt";

        $arrayLineas=array();
        if(file_exists("C://xampp/htdocs/cdr/".$nombreFichero))
        {
            try
            {
            $fp = fopen("C://xampp/htdocs/cdr/".$nombreFichero, "r");

            while (!feof($fp))
            {
                $linea = fgets($fp);
                $array = explode(';', $linea);

                if ($array[1] == $msidn)
                {
                    //$tipo, $origen, $destino, $tarifa, $fecha, $hora, $tiempo, $trafico, $velocidad, $detalle

                    $linea=new CDRLinea($array[0], $array[1], $array[2], $array[3], $array[5], $array[6], $array[7], $array[8], $array[9], $array[10]);
                    array_push($arrayLineas, $linea);
                }
            }

            fclose($fp);
            }catch (Exception $ex)
            {
                echo "No se puede abrir";
            }
        }


        return $arrayLineas;

    }



    private function getUltimoDiaMes()
    {
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));

        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }

    private function getPrimerDiaMes()
    {
        $month = date('m');
        $year = date('Y');
        //return date('Ymd', mktime(0,0,0, $month, 1, $year));
        return date('d', mktime(0,0,0, $month, 1, $year));
    }

    private function getFechaMesActual($dia)
    {
        $month = date('m');
        $year = date('Y');
        //return date('Ymd', mktime(0,0,0, $month, 1, $year));
        return date('Ymd', mktime(0,0,0, $month, $dia, $year));
    }



}