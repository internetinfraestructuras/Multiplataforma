<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 22/08/2018
 * Time: 13:39
 */

/*
-El operador donante no es necesario, permitir pasar sin elegir ningún donante.
-Modalidad actual. (prepago/postpago).
-El icc de origen y dc origen, aunque para más movil no es necesario DC. (Esto solo para AIRE).
-ICC destino(MASMOVIL) y en AIRE (ICC y DC).
-Descargar documento de portabilidad AIRE, y poder subirlo ya firmado.
 */

//require_once '../config/define.php';

include_once ('../config/util.php');
require_once ('Servicio.php');

class AltaTecnica
{
    /*
     * FUNCION PARA AÑADIR UNA LÍNEA FIJA, SI EL CLIENTE PASADO NO EXISTE SE CREA EN LA APLICACIÓN Y A POSTERIOR SE LE AÑADE LA LINEA NUEVA
     *
     */
    public static function addNuevoFijo($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,$nombreGrupoRecarga,
                                        $paqueteDestino,$numero)
    {

        require_once ($_SERVER['DOCUMENT_ROOT'].'clases/telefonia/classTelefonia.php');

        $telefonia=new Telefonia();

        if(!$telefonia->existeCliente($cifCliente))
        {
            try
            {
                $telefonia->addCliente($cifEmpresa,$cifCliente,$nombreCliente,$direccion,$email,"","","SPRE",$nombreGrupoRecarga);
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }

            try
            {
                $troncal=$telefonia->addLinea($cifCliente,"","",$numero);
//                echo $troncal;
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
            try
            {
                $telefonia->setTarifasTroncalFromPaqueteDestinos($troncal,$paqueteDestino,"0");
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
        }
        else
        {

            try
            {
                $troncal=$telefonia->addLinea($cifCliente,"","",$numero);
//                echo "<hr/>la troncal es".$troncal;
//                echo "<hr>";
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
            try
            {
                $telefonia->setTarifasTroncalFromPaqueteDestinos($troncal,$paqueteDestino,"0");
            }catch(Exception $ex)
            {
                echo $ex."<br/>";
            }
        }

    }

    /*
     * FUNCION PARA AÑADIR UNA LÍNEA EN MASMOVIL, SI EL CLIENTE PASADO NO EXISTE SE CREA EN LA APLICACIÓN Y A POSTERIOR SE LE AÑADE LA LINEA NUEVA
     *
     */

    public static function addNuevaLineaMasMovil($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                                                 $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                                                 $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                                                 $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta,$iccTarjeta,$idServicio)
    {

        require_once ('masmovil/MasMovilAPI.php');

        $apiMasMovil=new MasMovilAPI();
        $rs=$apiMasMovil->getListadoClientes($dni);

        if(@$rs->activationCode==NO_EXISTE_CLIENTE_MASMOVIL)
        {
            sleep(1);
            $rs=$apiMasMovil->crearNuevoCliente($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta);

            if($rs->activationCode==OPERACION_OK_MASMOVIL)
            {

                $refCliente=$rs->customerId;
                $ser=new Servicio();
                $idExterno=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);
                if($idExterno!=NULL)
                {
                    $idExterno=$idExterno[0][0];
                    $rs=$apiMasMovil->altaLineaMovil($refCliente,$iccTarjeta,$idExterno,"");

                }

            }
        }
        else
        {

            $refCliente=$rs->Client[0]->refCustomerId;
            $ser=new Servicio();
            $idExterno=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);
            if($idExterno!=NULL)
            {
                $idExterno=$idExterno[0][0];
                $rs=$apiMasMovil->altaLineaMovil($refCliente,$iccTarjeta,$idExterno,"");
            }


        }

        return $rs;
    }


    public static function addNuevaPortabilidadMasMovil($nombre,$nombreEmpresa,$tipoCliente,$tipoDocumento,$dni,
                                                        $nombreContacto,$apellido1,$apellido2,$telContacto,$movilContacto,$faxContacto,$emailContacto,$telefonoContacto,
                                                        $calle,$numeroCalle,$piso,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                                                        $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta,$iccTarjeta,$idServicio,$iccNuevo,$donante,$telefono,$tipoAbono,$fechaPortabilidad
    )
    {

        require_once ('masmovil/MasMovilAPI.php');

        $apiMasMovil=new MasMovilAPI();
        $rs=$apiMasMovil->getListadoClientes($dni);

        if(@$rs->activationCode==NO_EXISTE_CLIENTE_MASMOVIL)
        {
            sleep(1);
            $rs=$apiMasMovil->crearNuevoCliente($nombre,$nombreEmpresa,$tipoDocumento,$dni,
                $nombreContacto,$telContacto,$movilContacto,$faxContacto,$emailContacto,
                $calle,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,
                $titularCuenta,$nombreBanco,$codigoBanco,$oficina,$digitoControl,$numeroCuenta);

            if($rs->activationCode==OPERACION_OK_MASMOVIL)
            {

                $refCliente=$rs->customerId;
                $ser=new Servicio();
                $idExterno=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);
                if($idExterno!=NULL)
                {
                    $idExterno=$idExterno[0][0];
                    $rs=$apiMasMovil->altaPortabilidad($refCliente,$iccNuevo,$donante,$telefono,$tipoAbono,$fechaPortabilidad,$idExterno,"",$tipoCliente,$nombre
                        ,$apellido1,$apellido2,$tipoDocumento,$dni,$nombreEmpresa,$emailContacto,$telefonoContacto,$calle,$numeroCalle,$piso,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,$iccTarjeta);

                }

            }
        }
        else
        {

            $refCliente=$rs->Client[0]->refCustomerId;
            $ser=new Servicio();
            $idExterno=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);
            if($idExterno!=NULL)
            {
                $idExterno=$idExterno[0][0];
                $rs=$apiMasMovil->altaPortabilidad($refCliente,$iccNuevo,$donante,$telefono,$tipoAbono,$fechaPortabilidad,$idExterno,"",$tipoCliente,$nombre
                    ,$apellido1,$apellido2,$tipoDocumento,$dni,$nombreEmpresa,$emailContacto,$telefonoContacto,$calle,$numeroCalle,$piso,$localidad,$codigoProvincia,$codigoPais,$codigoPostal,$iccTarjeta);
            }


        }

        return $rs;
    }

    /*
     * FUNCION PARA AÑADIR UNA LÍNEA EN AIRENETWORK, SI EL CLIENTE PASADO NO EXISTE SE CREA EN LA APLICACIÓN Y A POSTERIOR SE LE AÑADE LA LINEA NUEVA
     *
     */
    public static function addNuevaLineaAire($tipoCliente,$consentimiento,$tipoDocumento,$numeroDocumento,$nombre,
                                             $apellido1,$apellido2,$fechaNacimiento,$email,$telefono,$region,
                                             $provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento,$icc,$dc,$idServicio)
    {
        require_once ('airenetwork/clases/Cliente.php');
        require_once ('airenetwork/clases/Linea.php');
        require_once ('Empresa.php');

        $configuracion=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);



        $clienteAire=new Cliente($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);
        $lineaAire=new Linea($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);

        $rs=$clienteAire->getClientByDNI($numeroDocumento);

        if($rs==NULL)
            $rs=$clienteAire->crearCliente($tipoCliente,$consentimiento,$tipoDocumento,$numeroDocumento,$nombre,$apellido1,$apellido2,$fechaNacimiento,$email,$telefono,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento);

        $pool=$lineaAire->getNumerosLibres();
        $codigoReserva=$pool['codigo_reserva'];
        $numeroTelefono=$pool['n1'];
        $idExterno=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);

        if($idExterno!=NULL)
        {
            $idExterno=$idExterno[0][0];
            $rs=$lineaAire->setAltaNueva($idExterno,$tipoCliente,$numeroDocumento,$icc,$dc,$numeroTelefono,$codigoReserva);


        }

        return $rs;



    }

    public static function addNuevaPortabilidadAireNetworks($idEmpresa,$idServicio,$tipoCliente,$consentimiento,$nombre,$apellido1,$apellido2,$fechaNacimiento,$email,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento,$tipoDocumento,$nif,$icc,$dc,$telefono,$modalidadActual,$iccOrigen,$dcOrigen)
    {

        require_once ('airenetwork/clases/Cliente.php');
        require_once ('airenetwork/clases/Linea.php');
        require_once ('Empresa.php');
        require_once ('Servicio.php');


        $configuracion=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);



        $clienteAire=new Cliente($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);
        $lineaAire=new Linea($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);

        $rs=$clienteAire->getClientByDNI($nif);


        if($rs==NULL)
            $rs=$clienteAire->crearCliente($tipoCliente,$consentimiento,$tipoDocumento,$nif,$nombre,$apellido1,$apellido2,$fechaNacimiento,$email,$telefono,$region,$provincia,$ciudad,$cp,$direccion,$numero,$docNombre,$documento);
//            echo "AltaTecnica L262: " ; var_dump($rs);

        $rservicio=Servicio::getIdExternoApi($idServicio,$_SESSION['REVENDEDOR']);

        if($rservicio!=null)
        {

            $tarifa=$rservicio[0]['ID_EXTERNO'];

            $rsP=$lineaAire->setAltaPortabilidad($tarifa,$tipoCliente,$nif,$icc,$dc,$telefono,$modalidadActual,$iccOrigen,$dcOrigen);
            echo $rsP;
        }

    }

    public static function getEstadosPortabilidadesAireNetworks($filtro)
    {
        require_once ('airenetwork/clases/Cliente.php');
        require_once ('airenetwork/clases/Linea.php');
        require_once ('Empresa.php');
        require_once ('Servicio.php');

        $configuracion=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
        $lineaAire=new Linea($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);
        $rs=$lineaAire->getSolicitudesLineas($filtro);

        var_dump($rs);
    }

    public static function getDocumentosPortabilidadAireNetworks($codigoSolicitud,$tipoDocumento)
    {
        require_once ('airenetwork/clases/Linea.php');
        require_once ('Empresa.php');
        $configuracion=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
        $lineaAire=new Linea($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);

        $rs=$lineaAire->getDocumentosSolicitud($codigoSolicitud,$tipoDocumento);
        var_dump($rs);

    }

    /*
     * SUBIR DOCUMENTACIÓN POR API AIRENETWORKS
     * $dniCliente
     * $codSolicitud=Es el código de solicitud de la línea
     * $documento64=Documento en base64
     * $tipoDocumento= DOCUMENTO/PORTABILIDAD/CONTRATOS/OTROS
     * $nombreFichero= Nombre del fichero pasado en base64.
     */

    public static function setDocumentosPortabilidadAireNetworks($dniCliente,$codSolicitud,$documento64,$tipoDocumento,$nombreFichero)
    {
        require_once ('airenetwork/clases/Linea.php');
        require_once ('Empresa.php');
        $configuracion=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
        $lineaAire=new Linea($configuracion[0][3],$configuracion[0][1],$configuracion[0][2]);

        $rs=$lineaAire->setDocumentosSolicitud($dniCliente,$codSolicitud,$documento64,$tipoDocumento,$nombreFichero);
        var_dump($rs);

    }
}

/*

<br />
<b>Warning</b>:  file_get_contents(): Filename cannot be empty in <b>C:\Users\Ruben\Dropbox\Multiplataforma\content\ventas\guardar-porta.php</b> on line <b>68</b><br />
<br />
<b>Notice</b>:  Object of class stdClass could not be converted to int in <b>C:\Users\Ruben\Dropbox\Multiplataforma\content\ventas\guardar-porta.php</b> on line <b>137</b><br />
<br />
<b>Catchable fatal error</b>:  Object of class stdClass could not be converted to string in <b>C:\Users\Ruben\Dropbox\Multiplataforma\content\ventas\guardar-porta.php</b> on line <b>159</b><br />

 */
?>



