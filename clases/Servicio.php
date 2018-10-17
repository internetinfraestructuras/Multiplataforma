<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 02/08/2018
 * Time: 13:15
 */

require_once ('Contrato.php');
require_once ('Producto.php');
require_once ('Orden.php');
require_once ('Empresa.php');
require_once ('Provision.php');
require_once ('masmovil/MasMovilAPI.php');
require_once ('airenetwork/clases/Linea.php');
require_once ('telefonia/classTelefonia.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');



    class Servicio
    {

        /*
         * METODO PARA ACTUALIZAR UN SERVICIO CONTRATADO
         * 1.idContrato:Identificador del contrato
         * 2.idLinea:Identificador de la línea del contrato.
         * 3.idServicio:Identificador del servicio asociado a esa línea.
         * 4.idServicioNuevo:Identificador del servicio nuevo
         * 5.
         * Explicación de la rutina;
         * 1. Se obtiene la línea de contrato del servicio X. Para tener los datos para el volcado.
         * 2. Se obtiene las lineas de detalles para el volcado de datos.
         * 3. Seteamos la línea de detalle obtenida y la establecemos en baja.(2)
         * 4. Seteamos la línea de contrato a baja.(2)
         * 5. Se genera la nueva línea de contrato en alta (1)
         * 6. Se vuelca la información en la nueva línea de contrato.
         * 7. Se vuelca la información de las líneas de detalles.
         * 8. Se buscan los productos asociados a la línea anterior y se actualizan apuntando a la nueva línea generada.
         * 9. Se genera el anexo del contrato.
         */
        public static function actualizarServicioContrato($idContrato, $idLinea, $idServicio, $idServicioNuevo, $precioProveedor, $beneficio, $pvp, $impuesto, $atributos)
        {
            echo "Obtenemos las líneas<br><br><br>";
            $lineaContrato = Contrato::getLineaContratoServicio($idContrato, $idLinea, $idServicio);


            echo "Obtenemos las líneas de detalles<br><br><br>";
            $lineaDetalles = Contrato::getLineaDetalles($idLinea);


            //Obtenemos los datos necesarios para volcarlos en la nueva tupla
            $tipo = $lineaContrato[0][0];
            $idAsoc = $lineaContrato[0][1];
            $idContrato = $lineaContrato[0][2];
            $permanencia = $lineaContrato[0][7];

            $estado=$lineaDetalles[0]['ESTADO'];

            echo "el tipo de servicio es" . $tipo . "<br>";

            Contrato::setLineaDetallesBaja($idLinea, null);//Seteamos la linea actual a baja
            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idServicio);

            $idLineaNueva = Contrato::setNuevaLineaContrato($tipo, $idServicioNuevo, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado);

            //Seteamos los detalles de una línea de contrato

            echo "<hr>";

            for ($i = 0; $i < count($lineaDetalles); $i++) {

                $idAtrib = $atributos['id'][$i];
                $valor = $atributos['valor'][$i];

                $tipo = $lineaDetalles[$i][0];
                $atributo = $lineaDetalles[$i][1];

                echo "El nuevo atributo es" . $atributo . " con un valor de" . $valor . " y con el ID" . $idAtrib . "<br>";


                $idLineaDetalle = $lineaDetalles[0][3];


                $productosLinea = Contrato::getProductosLinea($idLineaDetalle);

                echo "<br>";
                echo "La linea a buscar es" . $idLineaDetalle;
                echo "<br>";

                if ($tipo == ID_SER_INTERNET) {
                    if ($productosLinea != null) {

                        $idModelo = $productosLinea[0][2];
                        $idProducto = $productosLinea[0][0];
                        echo "ENTRAMOS PRODUCTO";
                        $pon = Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'], $idModelo, "PON");
                        $idpon = $pon[0][0];
                        $valorPon = Producto::getValorAtributoProducto($idpon, $idProducto);
                        $valorPon = $valorPon[0][0];
                    }

                    if ($idAtrib == ID_ATRIBUTO_SUBIDA)
                        $subida = $valor;
                    if ($idAtrib == ID_ATRIBUTO_BAJADA)
                        $bajada = $valor;

                    if ($subida != '' && $bajada != '') {

                        require_once('Provision.php');
                        $ontProvision = new Provision();
                        echo "La velocidad es S:$subida B:$bajada<br>";
                        $r = $ontProvision->cambiarVelocidad($valorPon, $subida, $bajada);
                        self::crearLogApisExternas(ID_API_INTERNET,$r,$valor,$_SESSION['REVENDEDOR'],ID_ANEXO_CAMBIO_TARIFA_PAQ);


                    }

                } else if ($tipo == ID_SER_FIJO)
                {
                    if ($idAtrib == ID_ATRIBUTO_TRONCAL)
                        $usuarioTroncal = $valor;
                    if ($idAtrib == ID_ATRIBUTO_PAQUETE_DESTINO)
                        $paqueteDestino = $valor;
                    if ($idAtrib == ID_ATRIBUTO_GRUPO_RECARGA)
                        $grupoRecarga = $valor;

                    $dni = Contrato::getDNIClienteContrato($_SESSION['REVENDEDOR'], $idContrato);


                    if ($usuarioTroncal != "" && $paqueteDestino != "" && $grupoRecarga != "") {

                        $telefoniaClass = new Telefonia();
                        $telefoniaClass->updateTarifasTroncalFromPaqueteDestinos($usuarioTroncal, $paqueteDestino);

                        $r=$telefoniaClass->updateGrupoRecargaCliente($_SESSION['REVENDEDOR'], $dni[0][0], $grupoRecarga);
                        self::crearLogApisExternas(ID_API_TELEFONIA_FIJA,$r,$usuarioTroncal,$_SESSION['REVENDEDOR'],ID_ANEXO_CAMBIO_TARIFA_PAQ);
                    }
                } else if ($tipo == ID_SER_MOVIL && $idAtrib == ID_NUMERO_MOVIL) {
                    $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                    $idExterno = $externo[0][0];
                    $idProveedor = self::getProveedor($idServicio);
                    $idProveedor = $idProveedor[0][0];

                    if ($idProveedor == ID_PROVEEDOR_MASMOVIL)
                    {
                        $apiMasMovil = new MasMovilAPI();
                        $resultado = $apiMasMovil->getListadoClientes("", $valor);

                        if(!empty($refClienteAPI=$resultado->Client[0]->refCustomerId))
                        {
                            echo "Es el numero".$valor;
                            $rs= $apiMasMovil->cambioProducto($refClienteAPI,"",$valor,$idExterno);

                            Contrato::generarAnexo($idContrato,$idServicio,3,$idLineaDetalle,ID_ANEXO_CAMBIO_SERV);
                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_ANEXO_CAMBIO_TARIFA_PAQ);
                        }

                    } else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                    {
                        echo "El proveedor es AIRENETWORK<HR>";
                    }
                } else if ($tipo == ID_SER_TV) {
                    echo "aqui va el cambio de TV";
                }

                $idLineaDetalleNueva = Contrato::setNuevaLineaDetalles($idLineaNueva, $tipo, $idAsoc, $idAtrib, $valor, 1);


                echo "Productos" . var_dump($productosLinea);

                for ($j = 0; $j < count($productosLinea); $j++) {
                    Contrato::cambiarLineaProducto($idLineaDetalle, $idLineaDetalleNueva);
                }

            }

        }


        /*ACTUALIZAMOS LOS SERVICIOS DENTRO DE LA INSTANCIA DE UN PAQUETE.
        ES DECIR MODIFICAR EL PAQUETE DE UN CLIENTE EN CONCRETO
        */
        public static function actualizarServicioPaqueteContrato($idContrato, $idLinea, $id, $tipo, $idServicio, $atributos, $fechaCambio, $idServicioOriginal, $idLineaDetalle)
        {

            require_once('Producto.php');
            $lineaContrato = Contrato::getLineaContrato($idContrato, $idLinea);


            $precioProveedor = $lineaContrato[0]['PRECIO_PROVEEDOR'];
            $beneficio = $lineaContrato[0]['BENEFICIO'];
            $impuesto = $lineaContrato[0]['IMPUESTO'];
            $pvp = $lineaContrato[0]['PVP'];
            $permanencia = $lineaContrato[0]['PERMANENCIA'];
            $estado = $lineaContrato[0]['ESTADO'];

            $lineaDetallesAll = Contrato::getLineaDetalles($idLinea);


            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);


            $idPaquete = $idPaquete[0]['ID_ASOCIADO'];


            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idPaquete, $fechaCambio);

            $nuevaLinea = Contrato::setNuevaLineaContrato(1, $idPaquete, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado, $fechaCambio);


            $util = new util();
            $numero = $util->selectWhere3('servicios_tipos_atributos',
                array("count(id)"),
                "servicios_tipos_atributos.id_servicio=$tipo AND servicios_tipos_atributos.id_tipo=2");

            $numero = $numero[0][0];
            $numeroMax = $idLineaDetalle + ($numero - 1);


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $subida = "";
            $bajada = "";
            $valorPon = "";


            for ($k = 0; $k < count($lineaDetallesAll); $k++)
            {

                $productosLinea = Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);
                echo "<hr>";
                var_dump($lineaDetallesAll[$k]);
                $estado = $lineaDetallesAll[$k]['ESTADO'];
                echo "El estado ORIGINAL $estado<br>";
                if ($lineaDetallesAll[$k]['ID_SERVICIO'] == $idServicioOriginal && ($lineaDetallesAll[$k]['ID'] <= $numeroMax && $lineaDetallesAll[$k]['ID'] >= $idLineaDetalle))
                {

                    if ($atributos != null)
                    {


                        for ($i = 0; $i < count($atributos['id']); $i++) {


                            if ($atributos['id'][$i] == $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'])
                            {

                                $idAtrib = $atributos['id'][$i];
                                $valor = $atributos['valor'][$i];

                                $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                                $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];
                                $estado=$lineaDetallesAll[$k]['ESTADO'];
                                echo "el estado de esta línea es $estado";
                                $idLineaDetalleNueva = Contrato::setNuevaLineaDetallesPaquete($nuevaLinea, $tipo, $idAtrib, $valor, $estado, $idServicio, $fechaCambio);


                                if ($tipo == ID_SER_INTERNET)
                                {
                                    if ($productosLinea != null) {

                                        $idModelo = $productosLinea[0][2];
                                        $idProducto = $productosLinea[0][0];
                                        echo "ENTRAMOS PRODUCTO";
                                        $pon = Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'], $idModelo, "PON");
                                        $idpon = $pon[0][0];
                                        $valorPon = Producto::getValorAtributoProducto($idpon, $idProducto);
                                        $valorPon = $valorPon[0][0];
                                    }

                                    if ($idAtrib == ID_ATRIBUTO_SUBIDA)
                                        $subida = $valor;
                                    if ($idAtrib == ID_ATRIBUTO_BAJADA)
                                        $bajada = $valor;

                                    if ($subida != '' && $bajada != '') {

                                        require_once('Provision.php');
                                        $ontProvision = new Provision();
                                        echo "El valor es $valorPon la subida $subida y la bajada $bajada<br>";
                                        $r = $ontProvision->cambiarVelocidad($valorPon, $subida, $bajada);


                                    }

                                } else if ($tipo == ID_SER_FIJO) {


                                    if ($idAtrib == ID_ATRIBUTO_TRONCAL)
                                        $usuarioTroncal = $valor;
                                    if ($idAtrib == ID_ATRIBUTO_PAQUETE_DESTINO)
                                        $paqueteDestino = $valor;
                                    if ($idAtrib == ID_ATRIBUTO_GRUPO_RECARGA)
                                        $grupoRecarga = $valor;

                                    $dni = Contrato::getDNIClienteContrato($_SESSION['REVENDEDOR'], $idContrato);


                                    if ($usuarioTroncal != "" && $paqueteDestino != "" && $grupoRecarga != "") {

                                        $telefoniaClass = new Telefonia();
                                        $telefoniaClass->updateTarifasTroncalFromPaqueteDestinos($usuarioTroncal, $paqueteDestino);

                                        $rs=$telefoniaClass->updateGrupoRecargaCliente($_SESSION['REVENDEDOR'], $dni[0][0], $grupoRecarga);
                                        self::crearLogApisExternas(ID_API_TELEFONIA_FIJA,$rs,$usuarioTroncal,$_SESSION['REVENDEDOR'],ID_ANEXO_CAMBIO_TARIFA_PAQ);
                                    }

                                } else if ($tipo == ID_SER_MOVIL && $idAtrib == ID_NUMERO_MOVIL)
                                {
                                    $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                                    $idExterno = $externo[0][0];
                                    $idProveedor = self::getProveedor($idServicio);
                                    $idProveedor = $idProveedor[0][0];

                                    if ($idProveedor == ID_PROVEEDOR_MASMOVIL)
                                    {
                                        $apiMasMovil = new MasMovilAPI();
                                        $resultado = $apiMasMovil->getListadoClientes("", $valor);
                                        $refClienteAPI = $resultado->Client[0]->refCustomerId;

                                        $rs = $apiMasMovil->cambioProducto($refClienteAPI, "", $valor, $idExterno);

                                        self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$usuarioTroncal,$_SESSION['REVENDEDOR'],ID_ANEXO_CAMBIO_TARIFA_PAQ);
                                    } else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS) {
                                        echo "El proveedor es AIRENETWORK<HR>";
                                    }

                                } else if ($tipo == ID_SER_TV)
                                {
                                    echo "Es una televisión aqui va el cambio técnico";
                                }


                            }

                        }

                    }


                } else
                    {

                    $idAtrib = $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                    $valor = $lineaDetallesAll[$k]['VALOR'];

                    $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                    $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];

                    $idLineaDetalleNueva = Contrato::setNuevaLineaDetallesPaqueteEstado($nuevaLinea, $tipo, $idAtrib, $valor, $estado, $ser);
                }

                Contrato::setLineaDetallesBajaServicio($idLinea, $ser, $fechaCambio);//Seteamos la linea actual a baja


                for ($j = 0; $j < count($productosLinea); $j++)
                {
                    Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'], $idLineaDetalleNueva);
                }

            }
            Contrato::generarAnexo($idContrato, 1, $idPaquete, $idLinea, ID_ANEXO_CAMBIO_TARIFA_PAQ);


        }

        public static function setImpagoServicioPaquete($idContrato, $idLinea, $tipo, $fechaCambio, $idServicioOriginal, $idLineaDetalle)
        {

            require_once('Producto.php');


            $lineaContrato = Contrato::getLineaContrato($idContrato, $idLinea);

            $precioProveedor = $lineaContrato[0]['PRECIO_PROVEEDOR'];
            $beneficio = $lineaContrato[0]['BENEFICIO'];
            $impuesto = $lineaContrato[0]['IMPUESTO'];
            $pvp = $lineaContrato[0]['PVP'];
            $permanencia = $lineaContrato[0]['PERMANENCIA'];

            $estado = $lineaContrato[0]['ESTADO'];

            echo "Buscamos linea $idLinea";
            $lineaDetallesAll = Contrato::getLineaDetalles($idLinea);



            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);


            $idPaquete = $idPaquete[0]['ID_ASOCIADO'];


            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idPaquete, $fechaCambio);

            $nuevaLinea = Contrato::setNuevaLineaContrato(1, $idPaquete, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado, $fechaCambio);


            $util = new util();
            $numero = $util->selectWhere3('servicios_tipos_atributos',
                array("count(id)"),
                "servicios_tipos_atributos.id_servicio=$tipo AND servicios_tipos_atributos.id_tipo=2");

            $numero = $numero[0][0];
            $numeroMax = $idLineaDetalle + ($numero - 1);


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $subida = "";
            $bajada = "";
            $valorPon = "";

            $msisdns="";

            $corteImpago=false;

            for ($k = 0; $k < count($lineaDetallesAll); $k++) {

                $productosLinea = Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

                $idAtrib = $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                $estado = $lineaDetallesAll[$k]['ESTADO'];
                $valor = $lineaDetallesAll[$k]['VALOR'];

                $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];

                if($idAtrib==ID_ATRIBUTO_TRONCAL)
                    $usuarioTroncal=$valor;


                if ($lineaDetallesAll[$k]['ID_SERVICIO'] == $idServicioOriginal && ($lineaDetallesAll[$k]['ID'] <= $numeroMax && $lineaDetallesAll[$k]['ID'] >= $idLineaDetalle))
                {
                    $estado=CONTRATO_IMPAGO;



                    if($tipo==ID_SER_INTERNET)
                    {
                        $pon="";
                        if($productosLinea!=null)
                        {
                            for($k=0;$k<count($productosLinea);$k++)
                            {
                                $idProducto=$productosLinea[$k][0];
                                $idModelo=$productosLinea[$k][2];
                                $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                                $idPon=$idPon[0][0];
                                $pon=Producto::getValorAtributoProducto($idPon,$idProducto);



                            }
                        }

                        if($pon!="")
                        {
                            $provision=new Provision();
                            $rs=$provision->bajaServicios($pon[0][1],true,false,false);
                            self::crearLogApisExternas(ID_API_INTERNET,$rs,$pon,$_SESSION['REVENDEDOR'],ID_BLOQUEO_LINEA_TEMPORAL);
                        }

                    }
                    if($tipo==ID_SER_FIJO)
                    {

                        if($idAtrib==ID_ATRIBUTO_TRONCAL)
                            $usuarioTroncal=$valor;

                        if($usuarioTroncal!="")
                        {
                            $telefoniaClass = new Telefonia();
                            $rs=$telefoniaClass->desactivarLinea($usuarioTroncal);
                            self::crearLogApisExternas(ID_API_TELEFONIA_FIJA,$rs,$usuarioTroncal,$_SESSION['REVENDEDOR'],ID_BLOQUEO_LINEA_TEMPORAL);
                        }


                    }
                    if($tipo==ID_SER_MOVIL)
                    {
                        $proveedor=Servicio::getProveedor($ser);

                        if($idAtrib==ID_NUMERO_MOVIL)
                            $msisdns=$valor;
                        echo "El numero es $msisdns<br>";
                        if($proveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS  && $msisdns!=""  && $corteImpago==false)
                        {
                            $conf=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                            $apiAire=new Linea($conf[0][3],$conf[0][1],$conf[0][2]);

                            $a=Contrato::getValorLineaDetalle($idLineaDetalle);
                            $corteImpago = true;

                            $rs=$apiAire->setCorteImpago($msisdns);
                            self::crearLogApisExternas(ID_API_AIRENETWORKS_FIJA,$rs,$valor,$_SESSION['REVENDEDOR'],ID_BLOQUEO_LINEA_TEMPORAL);

                        }
                        if($proveedor[0][0]==ID_PROVEEDOR_MASMOVIL && $msisdns!="" && $corteImpago==false)
                        {

                            $datosContrato=Contrato::getClienteDatosPorLineaDetalle($_SESSION['REVENDEDOR'],$idLineaDetalle);

                            $apiMasMovil = new MasMovilAPI();

                            $resultado = $apiMasMovil->getListadoClientes($datosContrato[0][0], $valor);

                            sleep(1);
                            $refClienteAPI = $resultado->Client[0]->refCustomerId;
                            $rs = $apiMasMovil->suspensionLineaMovil($refClienteAPI, $msisdns);
                            $corteImpago = true;


                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_BLOQUEO_LINEA_TEMPORAL);

                        }

                    }
                    if($tipo==ID_SER_TV)
                    {
                        echo "BAJA TECNICA TV<BR>";
                    }
                }


                $idLineaDetalleNueva = Contrato::setNuevaLineaDetallesPaquete($nuevaLinea, $tipo, $idAtrib, $valor, $estado, $ser, $fechaCambio);

                Contrato::setLineaDetallesBajaServicio($idLinea, $ser, $fechaCambio);//Seteamos la linea actual a baja


                for ($j = 0; $j < count($productosLinea); $j++) {
                    Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'], $idLineaDetalleNueva);
                }

            }
            Contrato::generarAnexo($idContrato, 1, $idPaquete, $idLinea, ID_ANEXO_CAMBIO_TARIFA_PAQ);


        }

        public static function setRestablecerCorteImpago($idContrato, $idLinea, $tipo, $fechaCambio, $idServicioOriginal, $idLineaDetalle)
        {

            require_once('Producto.php');
            $lineaContrato = Contrato::getLineaContrato($idContrato, $idLinea);

            $precioProveedor = $lineaContrato[0]['PRECIO_PROVEEDOR'];
            $beneficio = $lineaContrato[0]['BENEFICIO'];
            $impuesto = $lineaContrato[0]['IMPUESTO'];
            $pvp = $lineaContrato[0]['PVP'];
            $permanencia = $lineaContrato[0]['PERMANENCIA'];

            $lineaDetallesAll = Contrato::getLineaDetalles($idLinea);


            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);


            $idPaquete = $idPaquete[0]['ID_ASOCIADO'];


            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idPaquete, $fechaCambio);

            $nuevaLinea = Contrato::setNuevaLineaContrato(1, $idPaquete, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1, $fechaCambio);


            $util = new util();
            $numero = $util->selectWhere3('servicios_tipos_atributos',
                array("count(id)"),
                "servicios_tipos_atributos.id_servicio=$tipo AND servicios_tipos_atributos.id_tipo=2");

            $numero = $numero[0][0];
            $numeroMax = $idLineaDetalle + ($numero - 1);


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $subida = "";
            $bajada = "";
            $valorPon = "";
            $msisdns="";
            $restablecerCorte=false;

            for ($k = 0; $k < count($lineaDetallesAll); $k++) {

                $productosLinea = Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

                $idAtrib = $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                $estado = $lineaDetallesAll[$k]['ESTADO'];
                $valor = $lineaDetallesAll[$k]['VALOR'];

                $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];

                if($idAtrib==ID_ATRIBUTO_TRONCAL)
                    $usuarioTroncal=$valor;


                if ($lineaDetallesAll[$k]['ID_SERVICIO'] == $idServicioOriginal && ($lineaDetallesAll[$k]['ID'] <= $numeroMax && $lineaDetallesAll[$k]['ID'] >= $idLineaDetalle))
                {
                    $estado=CONTRATO_ALTA;

                    if($tipo==ID_SER_INTERNET)
                    {
                        $pon="";
                        if($productosLinea!=null)
                        {
                            for($k=0;$k<count($productosLinea);$k++)
                            {
                                $idProducto=$productosLinea[$k][0];
                                $idModelo=$productosLinea[$k][2];
                                $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                                $idPon=$idPon[0][0];
                                $pon=Producto::getValorAtributoProducto($idPon,$idProducto);


                            }
                        }
                        echo "El pon es ".var_dump($pon[0]['valor']);
                        if($pon!=null)
                        {
                            $provision=new Provision();
                            $rs=$provision->reactivarServicios($pon[0]['valor'],true);
                            self::crearLogApisExternas(ID_API_INTERNET,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);
                        }


                    }
                    if($tipo==ID_SER_FIJO)
                    {
                        if($idAtrib==ID_ATRIBUTO_TRONCAL)
                            $usuarioTroncal=$valor;

                        if($usuarioTroncal!="")
                        {
                            $telefoniaClass = new Telefonia();
                            $rs=$telefoniaClass->reactivarLinea($usuarioTroncal);
                            self::crearLogApisExternas(ID_API_TELEFONIA_FIJA,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);
                        }

                    }
                    if($tipo==ID_SER_MOVIL)
                    {

                        $proveedor=Servicio::getProveedor($ser);

                        if($idAtrib==ID_NUMERO_MOVIL)
                            $msisdns=$valor;



                        if($proveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS  && $msisdns!=""  && $restablecerCorte==false)
                        {
                            echo "ENTRAMOS CORTE IMPAGO PRIMO <HR>";
                            $conf=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                            $apiAire=new Linea($conf[0][3],$conf[0][1],$conf[0][2]);

                            $rs=$apiAire->setRestablecerCorteImpago($msisdns);

                            $restablecerCorte=true;

                            if($rs=="0016")//SI DEVUELVE EL 0016 LA LINEA TIENE UN PROCESO DE SOLICITUD DE CORTE Y RESTABLECE CANCELANDO ESA SOLICITUD
                                $rs=$apiAire->setCancelarCorteImpago($msisdns);

                            self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);

                        }
                        if($proveedor[0][0]==ID_PROVEEDOR_MASMOVIL && $msisdns!="" && $restablecerCorte==false)
                        {

                            $datosContrato=Contrato::getClienteDatosPorLineaDetalle($_SESSION['REVENDEDOR'],$idLineaDetalle);

                            $apiMasMovil = new MasMovilAPI();

                            $resultado = $apiMasMovil->getListadoClientes($datosContrato[0][0], $valor);

                            sleep(1);
                            $refClienteAPI = $resultado->Client[0]->refCustomerId;
                            $rs=$apiMasMovil->reactivacionLineaMovil($refClienteAPI,$msisdns);
                            $restablecerCorte=true;

                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);

                        }



                    }
                    if($tipo==ID_SER_TV)
                    {
                        echo "BAJA TECNICA TV<BR>";
                    }
                }


                $idLineaDetalleNueva = Contrato::setNuevaLineaDetallesPaquete($nuevaLinea, $tipo, $idAtrib, $valor, $estado, $ser, $fechaCambio);

                Contrato::setLineaDetallesBajaServicio($idLinea, $ser, $fechaCambio);//Seteamos la linea actual a baja


                for ($j = 0; $j < count($productosLinea); $j++) {
                    Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'], $idLineaDetalleNueva);
                }

            }



        }

        public static function darBajaServicio($idContrato, $idLineaContrato, $idServicio, $fechaBaja=null, $productos=null)
        {

            Contrato::setLineaContratoBaja($idContrato, $idLineaContrato, $idServicio, $fechaBaja);
            
            Contrato::setLineaDetallesBaja($idContrato, $fechaBaja);
            $ld = Contrato::getLineaDetalles($idLineaContrato);
             $pon="";

            for($i=0;$i<count($ld);$i++)
            {
                $lineaDetalle = $ld[$i]['ID'];
                $tipo = $ld[$i]['ID_TIPO_SERVICIO'];
                $idAtributo = $ld[$i]['ID_ATRIBUTO_SERVICIO'];

                $productos=Contrato::getProductosLinea($lineaDetalle);



                if ($tipo == ID_SER_INTERNET)
                {
                    if($productos!=null)
                    {
                        for($k=0;$k<count($productos);$k++)
                        {
                            $idProducto=$productos[$k][0];
                            $idModelo=$productos[$k][2];
                            $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                            $idPon=$idPon[0][0];
                            $pon=Producto::getValorAtributoProducto($idPon,$idProducto);
                            $pon=$pon[0][0];
                        }
                    }

                    if($pon!=null)
                    {

                        $provision=new Provision();
                        $provision->bajaServicios($pon,true);
                        $pon="";
                    }


                }
                if ($tipo == ID_SER_FIJO && $idAtributo == ID_ATRIBUTO_TRONCAL) {

                    $troncal = $ld[$i]['VALOR'];
                    $telefonia = new Telefonia();
                    $telefonia->desactivarLinea($troncal);

                }
                if ($tipo == ID_SER_MOVIL)
                {

                    if ($idAtributo == ID_NUMERO_MOVIL)
                    {


                        $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                        $idExterno = $externo[0][0];
                        $idProveedor = self::getProveedor($idServicio);

                        $idProveedor = $idProveedor[0][0];


                        if ($idProveedor == ID_PROVEEDOR_MASMOVIL)
                        {

                            $msidn=$ld[$i]['VALOR'];

                            $apiMasMovil = new MasMovilAPI();

                            $resultado = $apiMasMovil->getListadoClientes("", $msidn);

                            $refClienteAPI = $resultado->Client[0]->refCustomerId;

                            $res = $apiMasMovil->bajaLineaMovil($refClienteAPI,$msidn);

                            self::crearLogApisExternas(ID_API_MASMOVIL,$res,$msidn,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                        } else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                        {

                            $msidn=$ld[$i]['VALOR'];

                            $configuracionAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                            $lineaAire=new Linea($configuracionAire[0][3],$configuracionAire[0][1],$configuracionAire[0][2]);

                            $rs=$lineaAire->setSolicitarBajaLinea($msidn,"1");

                            self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$msidn,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                        }
                    }
                }
                if ($tipo == ID_SER_TV)
                {
                    echo "BAJA TELEVISION";
                }
            }



                if($productos!=null)
                {
                    Contrato::setProductosRMA($idContrato, $productos, $lineaDetalle, $fechaBaja);
                }




         // Contrato::generarAnexo($idContrato, 1, $idPaquete, $idLinea, ID_ANEXO_CAMBIO_TARIFA_PAQ);


        }

        public static function darBajaServicioPaquete($idEmpresa, $idContrato, $idLinea, $idPaquete, $idServicio,$idLineaDetalleBuscado)
        {
            $lineaDetalles = Contrato::getLineaDetalles($idLinea);

            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);
            $idPaquete = $idPaquete[0][0];
            Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

            $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);

            $numeroMax = 0;
            $util = new util();
            $flag = false;
            $idLineaContratoNueva = 0;


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $lineaMax = 0;
            $idOrden=0;
            $ordenGenerada=false;
            $idLineaContratoNueva = Contrato::setNuevaLineaContrato(1, $idPaquete, $idContrato, $lineaContrato[0][3], $lineaContrato[0][4],$lineaContrato[0][5], $lineaContrato[0][6], $lineaContrato[0][7], 1);
            for ($i = 0; $i < count($lineaDetalles); $i++)
            {

                $tipoServicio = $lineaDetalles[$i]['ID_TIPO_SERVICIO'];
                $idLineaDetalle = $lineaDetalles[$i]['ID'];
                $idAtributo = $lineaDetalles[$i]['ID_ATRIBUTO_SERVICIO'];
                $valor = $lineaDetalles[$i]['VALOR'];
                $servicioLinea = $lineaDetalles[$i]['ID_SERVICIO'];

                $productos = Contrato::getProductosLinea($idLineaDetalle);


                if ($numeroMax == $idLineaDetalle || $flag == false) {

                    echo "Creamos nueva linea para la linea $servicioLinea<hr>";
                    $numero = $util->selectWhere3('servicios_tipos_atributos',
                        array("count(id)"),
                        "servicios_tipos_atributos.id_servicio=$tipoServicio AND servicios_tipos_atributos.id_tipo=2");

                    $numero = $numero[0][0];
                    $numeroMax = $idLineaDetalle + ($numero);
                    $idServi = $lineaDetalles[$i]['ID_SERVICIO'];
                    $detallesServicio = Servicio::getDetallesServicio($idServi);

                    $precioProveedor = $detallesServicio[0]['PRECIO_PROVEEDOR'];
                    $impuesto = $detallesServicio[0]['IMPUESTO'];
                    $beneficio = $detallesServicio[0]['BENEFICIO'];
                    $pvp = $detallesServicio[0]['PVP'];

                    $permanencia = null;
                    $flag = true;

                    $lineaMax = $idLineaDetalleBuscado + $numeroMax;
                    $lineaMaximaBuscado = $idLineaDetalleBuscado + $numeroMax;
                    $d = $numeroMax - $idLineaDetalleBuscado;

                }




                if ($idServicio == $servicioLinea && $d == $numero)
                {

                    echo "Entramos en la baja!!!!!";

                        if ($tipoServicio == ID_SER_INTERNET)
                        {
                            if($productos!=null)
                            {
                                for($k=0;$k<count($productos);$k++)
                                {
                                    $idProducto=$productos[$k][0];
                                    $idModelo=$productos[$k][2];
                                    $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                                    $idPon=$idPon[0][0];
                                    $pon=Producto::getValorAtributoProducto($idPon,$idProducto);
                                }
                            }
                            echo "El pon es ".var_dump($pon[0]['valor']);
                            if($pon!=null)
                            {
                                $provision=new Provision();
                                $provision->bajaServicios($pon,true);
                            }


                        }
                        if ($tipoServicio == ID_SER_FIJO && $idAtributo == ID_ATRIBUTO_TRONCAL) {

                            $troncal = $lineaDetalles[$i][2];
                            $telefonia = new Telefonia();
                            $telefonia->desactivarLinea($troncal);

                        }
                        if ($tipoServicio == ID_SER_MOVIL)
                        {

                            if ($idAtributo == ID_NUMERO_MOVIL && $lineaDetalles[$i]['ID_SERVICIO'] == $idServicio) {


                                $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                                $idExterno = $externo[0][0];
                                $idProveedor = self::getProveedor($idServicio);
                                $idProveedor = $idProveedor[0][0];


                                if ($idProveedor == ID_PROVEEDOR_MASMOVIL) {

                                    $apiMasMovil = new MasMovilAPI();
                                    $resultado = $apiMasMovil->getListadoClientes("", $valor);

                                    $refClienteAPI = $resultado->Client[0]->refCustomerId;

                                    $res = $apiMasMovil->bajaLineaMovil($refClienteAPI,$valor);

                                    self::crearLogApisExternas(ID_API_MASMOVIL,$res,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                                } else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                                {
                                    $configuracionAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                                    $lineaAire=new Linea($configuracionAire[0][3],$configuracionAire[0][1],$configuracionAire[0][2]);

                                    $rs=$lineaAire->setSolicitarBajaLinea($valor,"1");

                                    self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                                }
                            }
                        }
                        if ($tipoServicio == ID_SER_TV)
                        {
                            echo "BAJA TELEVISION";
                        }

                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        if($ordenGenerada==0)
                            $ordenGenerada=Orden::crearOrdenTrabajo($idContrato,"");



                        for ($k = 0; $k < count($productos); $k++)
                        {
                            Orden::crearLineaOrden($ordenGenerada,ID_ORDEN_RMA_BAJA,$productos[0][0],$idLineaDetalle);
                            Contrato::setProductoBaja($productos[0][0],ID_PRODUCTO_BAJA);
                        }
                    }

                }
                else
                {


                    echo "Linea Detalle:$idServi<br>";
                    $nuevaLineaDetalles = Contrato::setNuevaLineaDetalles($idLineaContratoNueva, $tipoServicio, $idServi, $idAtributo, $valor, 1);


                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        for ($k = 0; $k < count($productos); $k++)
                            Contrato::cambiarLineaProducto($idLineaDetalle, $nuevaLineaDetalles);
                    }



                }
            }


            Contrato::generarAnexo($idContrato,1,$idPaquete,$idLinea,ID_ANEXO_BAJA_SERVICIO);

        }

        public static function getTipoServicio($idEmpresa,$idServicio)
        {
            $util=new util();
            return $util->selectWhere3("servicios", array("ID_SERVICIO_TIPO"),
                "servicios.id=".$idServicio." AND servicios.id_empresa=$idEmpresa");
        }

        public static function getTipoServicioLineaContrato($idEmpresa,$idServicio)
        {
            $util=new util();
            return $util->selectWhere3("servicios", array("ID_SERVICIO_TIPO"),
                "servicios.id=".$idServicio." AND servicios.id_empresa=$idEmpresa");
        }

        public static function setImpagoServicio($idContrato, $idLinea, $tipo, $fechaCambio, $idServicioOriginal)
        {

            require_once('Producto.php');

            $lineaContrato = Contrato::getLineaContrato($idContrato, $idLinea);

            $precioProveedor = $lineaContrato[0]['PRECIO_PROVEEDOR'];
            $beneficio = $lineaContrato[0]['BENEFICIO'];
            $impuesto = $lineaContrato[0]['IMPUESTO'];
            $pvp = $lineaContrato[0]['PVP'];
            $permanencia = $lineaContrato[0]['PERMANENCIA'];

            $lineaDetallesAll = Contrato::getLineaDetalles($idLinea);





            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);


            $idPaquete = $idPaquete[0]['ID_ASOCIADO'];


            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idPaquete, $fechaCambio);

            $nuevaLinea = Contrato::setNuevaLineaContrato(2, $idPaquete, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, CONTRATO_IMPAGO, $fechaCambio);

            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $subida = "";
            $bajada = "";
            $valorPon = "";

            $msisdns="";

            $corteImpago=false;

            echo "Se vuelcan ".count($lineaDetallesAll);
            for ($k = 0; $k < count($lineaDetallesAll); $k++)
            {

                $productosLinea = Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

                $idAtrib = $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                $estado = $lineaDetallesAll[$k]['ESTADO'];
                $valor = $lineaDetallesAll[$k]['VALOR'];

                $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];
                $idLineaDetalle=$lineaDetallesAll[$k]['ID'];


                if($idAtrib==ID_ATRIBUTO_TRONCAL)
                    $usuarioTroncal=$valor;


                    $estado=CONTRATO_IMPAGO;



                    if($tipo==ID_SER_INTERNET)
                    {
                        $pon="";
                        if($productosLinea!=null)
                        {
                            for($k=0;$k<count($productosLinea);$k++)
                            {
                                $idProducto=$productosLinea[$k][0];
                                $idModelo=$productosLinea[$k][2];
                                $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                                $idPon=$idPon[0][0];
                                $pon=Producto::getValorAtributoProducto($idPon,$idProducto);


                            }
                        }

                        $provision=new Provision();
                        $provision->bajaServicios($pon[0][1],true,false,false);
                    }
                    if($tipo==ID_SER_FIJO)
                    {

                        if($idAtrib==ID_ATRIBUTO_TRONCAL)
                            $usuarioTroncal=$valor;

                        if($usuarioTroncal!="")
                        {
                            echo "Desactivamos la línea $usuarioTroncal";
                            $telefoniaClass = new Telefonia();
                            $telefoniaClass->desactivarLinea($usuarioTroncal);
                        }


                    }
                    if($tipo==ID_SER_MOVIL)
                    {
                        $proveedor=Servicio::getProveedor($ser);

                        if($idAtrib==ID_NUMERO_MOVIL)
                            $msisdns=$valor;
                        echo "El numero es $msisdns<br>";
                        if($proveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS  && $msisdns!=""  && $corteImpago==false)
                        {
                            $conf=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                            $apiAire=new Linea($conf[0][3],$conf[0][1],$conf[0][2]);

                            $a=Contrato::getValorLineaDetalle($idLineaDetalle);
                            $corteImpago = true;

                            $rs=$apiAire->setCorteImpago($msisdns);

                            self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BLOQUEO_TEMPORAL);

                        }
                        if($proveedor[0][0]==ID_PROVEEDOR_MASMOVIL && $msisdns!="" && $corteImpago==false)
                        {

                            $datosContrato=Contrato::getClienteDatosPorLineaDetalle($_SESSION['REVENDEDOR'],$idLineaDetalle);

                            $apiMasMovil = new MasMovilAPI();

                            $resultado = $apiMasMovil->getListadoClientes($datosContrato[0][0], $valor);

                            sleep(1);
                            $refClienteAPI = $resultado->Client[0]->refCustomerId;
                            $rs = $apiMasMovil->suspensionLineaMovil($refClienteAPI, $msisdns);
                            $corteImpago = true;


                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BLOQUEO_TEMPORAL);

                        }

                    }
                    if($tipo==ID_SER_TV)
                    {
                        echo "BAJA TECNICA TV<BR>";
                    }



                $idLineaDetalleNueva = Contrato::setNuevaLineaDetalles($nuevaLinea, $tipo, $idServicioOriginal, $idAtrib, $valor, $estado);


                Contrato::setLineaDetallesBajaServicio($idLinea, $ser, $fechaCambio);//Seteamos la linea actual a baja


                for ($j = 0; $j < count($productosLinea); $j++) {
                    Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'], $idLineaDetalleNueva);
                }

            }
            Contrato::generarAnexo($idContrato, 1, $idPaquete, $idLinea, 10);


        }

        public static function setRestablecerServicioCorteImpago($idContrato, $idLinea, $tipo, $fechaCambio, $idServicioOriginal)
        {

            require_once('Producto.php');
            $lineaContrato = Contrato::getLineaContrato($idContrato, $idLinea);

            $precioProveedor = $lineaContrato[0]['PRECIO_PROVEEDOR'];
            $beneficio = $lineaContrato[0]['BENEFICIO'];
            $impuesto = $lineaContrato[0]['IMPUESTO'];
            $pvp = $lineaContrato[0]['PVP'];
            $permanencia = $lineaContrato[0]['PERMANENCIA'];

            $lineaDetallesAll = Contrato::getLineaDetalles($idLinea);




            Contrato::setLineaContratoBaja($idContrato, $idLinea, $idServicioOriginal, $fechaCambio);

            $nuevaLinea = Contrato::setNuevaLineaContrato(2, $idServicioOriginal, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1, $fechaCambio);



            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $subida = "";
            $bajada = "";
            $valorPon = "";
            $msisdns="";
            $restablecerCorte=false;

            for ($k = 0; $k < count($lineaDetallesAll); $k++)
            {

                $idLineaDetalle=$lineaDetallesAll[$k]['ID'];

                $productosLinea = Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

                $idAtrib = $lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                $estado = $lineaDetallesAll[$k]['ESTADO'];
                $valor = $lineaDetallesAll[$k]['VALOR'];

                $tipo = $lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                $ser = $lineaDetallesAll[$k]['ID_SERVICIO'];

                if($idAtrib==ID_ATRIBUTO_TRONCAL)
                    $usuarioTroncal=$valor;


                if ($lineaDetallesAll[$k]['ID_SERVICIO'] == $idServicioOriginal)
                {
                    $estado=CONTRATO_ALTA;

                    if($tipo==ID_SER_INTERNET)
                    {
                        $pon="";
                        if($productosLinea!=null)
                        {
                            for($k=0;$k<count($productosLinea);$k++)
                            {
                                $idProducto=$productosLinea[$k][0];
                                $idModelo=$productosLinea[$k][2];
                                $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                                $idPon=$idPon[0][0];
                                $pon=Producto::getValorAtributoProducto($idPon,$idProducto);


                            }
                        }

                        $provision=new Provision();
                        $provision->reactivarServicios($pon[0]['valor']);

                    }
                    if($tipo==ID_SER_FIJO)
                    {


                        if($idAtrib==ID_ATRIBUTO_TRONCAL)
                            $usuarioTroncal=$valor;

                        if($usuarioTroncal!="")
                        {
                            $telefoniaClass = new Telefonia();
                            $telefoniaClass->reactivarLinea($usuarioTroncal);
                        }

                    }
                    if($tipo==ID_SER_MOVIL)
                    {

                        $proveedor=Servicio::getProveedor($ser);

                        if($idAtrib==ID_NUMERO_MOVIL)
                            $msisdns=$valor;



                        if($proveedor[0][0]==ID_PROVEEDOR_AIRENETWORKS  && $msisdns!=""  && $restablecerCorte==false)
                        {
                            echo "ENTRAMOS CORTE IMPAGO PRIMO <HR>";
                            $conf=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
                            $apiAire=new Linea($conf[0][3],$conf[0][1],$conf[0][2]);

                            $rs=$apiAire->setRestablecerCorteImpago($msisdns);

                            $restablecerCorte=true;

                            if($rs=="0016")//SI DEVUELVE EL 0016 LA LINEA TIENE UN PROCESO DE SOLICITUD DE CORTE Y RESTABLECE CANCELANDO ESA SOLICITUD
                                $rs=$apiAire->setCancelarCorteImpago($msisdns);

                            self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);

                        }
                        if($proveedor[0][0]==ID_PROVEEDOR_MASMOVIL && $msisdns!="" && $restablecerCorte==false)
                        {

                            $datosContrato=Contrato::getClienteDatosPorLineaDetalle($_SESSION['REVENDEDOR'],$idLineaDetalle);

                            $apiMasMovil = new MasMovilAPI();

                            $resultado = $apiMasMovil->getListadoClientes($datosContrato[0][0], $valor);

                            sleep(1);
                            $refClienteAPI = $resultado->Client[0]->refCustomerId;
                            $rs=$apiMasMovil->reactivacionLineaMovil($refClienteAPI,$msisdns);
                            $restablecerCorte=true;

                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_DESBLOQUEO_LINEA);
                        }



                    }
                    if($tipo==ID_SER_TV)
                    {
                        echo "BAJA TECNICA TV<BR>";
                    }
                }


                $idLineaDetalleNueva = Contrato::setNuevaLineaDetalles($nuevaLinea, $tipo, $idServicioOriginal, $idAtrib, $valor, $estado);

                Contrato::setLineaDetallesBajaServicio($idLinea, $ser, $fechaCambio);//Seteamos la linea actual a baja


                for ($j = 0; $j < count($productosLinea); $j++) {
                    Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'], $idLineaDetalleNueva);
                }

            }
            Contrato::generarAnexo($idContrato, 1, $idServicioOriginal,$idLinea, 10);


        }

        public static function getAtributosTecnicosServicio($idTipoServicio)
        {
            $util = new util();
            $numero = $util->selectWhere3('servicios_tipos_atributos',
                array("count(id)"),
                "servicios_tipos_atributos.id_servicio=$idTipoServicio AND servicios_tipos_atributos.id_tipo=2");

            return $numero[0][0];
        }

        public static function cancelarBajaServicio($idContrato, $idLineaContrato, $idServicio, $productos)
        {

            Contrato::setLineaContratoAlta($idContrato, $idLineaContrato, $idServicio);
            Contrato::setLineaDetallesAlta($idLineaContrato);

            $ld = Contrato::getLineaDetalles($idLineaContrato);
            $lineaDetalle = $ld[0]['ID'];

            if ($productos != '[]' || $productos != null)
                Contrato::setProductoInstalado($idContrato, $productos, $idLineaContrato);


            Contrato::generarAnexo($idContrato, $idServicio, 6);


        }

        //Obtiene el estado al que pasará la línea anterior y la nueva según las fechas
        public static function obtenerEstadosPorFechas($fecha)
        {
            $fecha_actual = strtotime(date("y-m-d", time()));
            $fechaBaja = strtotime(date("y-m-d", strtotime($fecha)));

            if ($fecha_actual < $fechaBaja)
                $tipoEstado = 4;
            else
                $tipoEstado = 2;


            return $tipoEstado;
        }
        /*
         * ROMPER PAQUETE
         * Rompe un paquete cuando hay un cambio de servicio
         */
        public static function romperPaquete($idContrato, $idLinea, $servicio, $fechaCambio, $idLineaDetalleActual, $atributos)
        {


            $lineaDetalles = Contrato::getLineaDetalles($idLinea);

            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);
            $idPaquete = $idPaquete[0][0];
            Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

            $numeroMax = 0;
            $util = new util();
            $flag = false;
            $idLineaContratoNueva = 0;
            $idServicio = 0;

            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";



            for ($i = 0; $i < count($lineaDetalles); $i++) {

                $tipoServicio = $lineaDetalles[$i]['ID_TIPO_SERVICIO'];
                $idLineaDetalle = $lineaDetalles[$i]['ID'];
                $idAtributo = $lineaDetalles[$i]['ID_ATRIBUTO_SERVICIO'];
                $valor = $lineaDetalles[$i]['VALOR'];
                $estado = $lineaDetalles[$i]['ESTADO'];
                $productos = Contrato::getProductosLinea($idLineaDetalle);

                if ($tipoServicio == ID_SER_INTERNET)
                {
                    $pon="";
                    if($productos!=null)
                    {
                        for($k=0;$k<count($productos);$k++)
                        {
                            $idProducto=$productos[$k][0];
                            $idModelo=$productos[$k][2];
                            $idPon=Producto::getIdAtributoProducto($_SESSION['REVENDEDOR'],$idModelo,"pon");
                            $idPon=$idPon[0][0];
                            $pon=Producto::getValorAtributoProducto($idPon,$idProducto);
                            $pon=$pon[0]['valor'];

                        }
                        $provision=new Provision();

                        $provision->bajaServicios($pon,true);
                    }

                }
                if ($tipoServicio == ID_SER_FIJO) {
                    echo "REcorremos los atributos para volvar los pasados nuevos primo<br>";

                    for ($j = 0; $j < count($atributos['id']); $j++) {
                        if ($atributos['id'][$j] == ID_ATRIBUTO_GRUPO_RECARGA)
                            $grupoRecarga = $atributos['valor'][$j];
                        if ($atributos['id'][$j] == ID_ATRIBUTO_PAQUETE_DESTINO)
                            $paqueteDestino = $atributos['valor'][$j];
                        if ($atributos['id'][$j] == ID_ATRIBUTO_TRONCAL)
                            $usuarioTroncal = $atributos['valor'][$j];
                    }

                    $dni = Contrato::getDNIClienteContrato($_SESSION['REVENDEDOR'], $idContrato);


                    if ($usuarioTroncal != "" && $paqueteDestino != "" && $grupoRecarga != "")
                    {
                        $telefoniaClass = new Telefonia();
                        $telefoniaClass->updateTarifasTroncalFromPaqueteDestinos($usuarioTroncal, $paqueteDestino);
                        $telefoniaClass->updateGrupoRecargaCliente($_SESSION['REVENDEDOR'], $dni[0][0], $grupoRecarga);
                    }
                }
                if ($tipoServicio == ID_SER_MOVIL) {

                    if ($idAtributo == ID_NUMERO_MOVIL && $lineaDetalles[$i]['ID_SERVICIO'] == $servicio) {

                        echo "<hr>Entramos en el servicio";


                        $externo = self::getIdExternoApi($servicio, $_SESSION['REVENDEDOR']);

                        $idExterno = $externo[0][0];
                        $idProveedor = self::getProveedor($servicio);
                        $idProveedor = $idProveedor[0][0];


                        if ($idProveedor == ID_PROVEEDOR_MASMOVIL)
                        {
                            $apiMasMovil = new MasMovilAPI();
                            $resultado = $apiMasMovil->getListadoClientes("", $valor);

                            $refClienteAPI = $resultado->Client[0]->refCustomerId;

                            $rs = $apiMasMovil->cambioProducto($refClienteAPI, "", $valor, $idExterno);


                            self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);
                        }
                        else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                        {
                            echo "Esta funcionalidad no esta implementada";

                        }
                    }
                }
                if ($tipoServicio == ID_SER_TV) {
                    echo "TELEVISION";
                }


                if ($idLineaDetalle == $idLineaDetalleActual)
                    $idServicio = $servicio;
                else
                    $idServicio = $lineaDetalles[$i]['ID_SERVICIO'];

                if ($numeroMax == $idLineaDetalle || $flag == false) {

                    $numero = $util->selectWhere3('servicios_tipos_atributos',
                        array("count(id)"),
                        "servicios_tipos_atributos.id_servicio=$tipoServicio AND servicios_tipos_atributos.id_tipo=2");

                    $numero = $numero[0][0];
                    $numeroMax = $idLineaDetalle + ($numero);

                    $detallesServicio = Servicio::getDetallesServicio($idServicio);

                    $precioProveedor = $detallesServicio[0]['PRECIO_PROVEEDOR'];
                    $impuesto = $detallesServicio[0]['IMPUESTO'];
                    $beneficio = $detallesServicio[0]['BENEFICIO'];
                    $pvp = $detallesServicio[0]['PVP'];

                    $permanencia = null;

                    $idLineaContratoNueva = Contrato::setNuevaLineaContrato(2, $idServicio, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado);

                    $flag = true;

                }

                $nuevaLineaDetalles = Contrato::setNuevaLineaDetalles($idLineaContratoNueva, $tipoServicio, $idServicio, $idAtributo, $valor, 1);


                Contrato::setLineaDetallesBaja($idLineaDetalle);



                for ($k = 0; $k < count($productos); $k++) {
                    Contrato::cambiarLineaProducto($idLineaDetalle, $nuevaLineaDetalles);
                }

            }

            Contrato::generarAnexo($idContrato, 1, $idPaquete, $idLinea, 8);


        }

        /*
         * Cuando se interactua con un servicio y una api externa esta función guarda el resultado en BBDD.
         */

        public static function crearLogApisExternas($idApi,$mensaje,$numero,$empresa,$idTransaccion)
        {
            $util=new util();

            $t_ordenes=array("ID_API","DEVOLUCION","NUMERO","ID_EMPRESA","ID_TRANSACCION");



            $values=array($idApi,$mensaje,$numero,$empresa,$idTransaccion);//TIPO DE ESTADO ES 1 DE APERTURA

            $resOrden= $util->insertInto('log_api_moviles', $t_ordenes, $values);

            return $resOrden;
        }

        /*
         * Dar de baja un servicio dentro de un paquete rompiendo el paquete y realizando servicios independientes.
         */
        public static function darBajaPaqueteRompiendoNuevo($idEmpresa, $idContrato, $idLinea, $idServicio, $idLineaDetalleBuscado)
        {

            $lineaDetalles = Contrato::getLineaDetalles($idLinea);

            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);
            $idPaquete = $idPaquete[0][0];
            Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

            $numeroMax = 0;
            $util = new util();
            $flag = false;
            $idLineaContratoNueva = 0;


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $lineaMax = 0;

            for ($i = 0; $i < count($lineaDetalles); $i++)
            {

                $tipoServicio = $lineaDetalles[$i]['ID_TIPO_SERVICIO'];
                $idLineaDetalle = $lineaDetalles[$i]['ID'];
                $idAtributo = $lineaDetalles[$i]['ID_ATRIBUTO_SERVICIO'];
                $valor = $lineaDetalles[$i]['VALOR'];
                $servicioLinea = $lineaDetalles[$i]['ID_SERVICIO'];

                $productos = Contrato::getProductosLinea($idLineaDetalle);


                if ($numeroMax == $idLineaDetalle || $flag == false) {

                    echo "Creamos nueva linea para la linea $servicioLinea<hr>";
                    $numero = $util->selectWhere3('servicios_tipos_atributos',
                        array("count(id)"),
                        "servicios_tipos_atributos.id_servicio=$tipoServicio AND servicios_tipos_atributos.id_tipo=2");

                    $numero = $numero[0][0];
                    $numeroMax = $idLineaDetalle + ($numero);
                    $idServi = $lineaDetalles[$i]['ID_SERVICIO'];
                    $detallesServicio = Servicio::getDetallesServicio($idServi);

                    $precioProveedor = $detallesServicio[0]['PRECIO_PROVEEDOR'];
                    $impuesto = $detallesServicio[0]['IMPUESTO'];
                    $beneficio = $detallesServicio[0]['BENEFICIO'];
                    $pvp = $detallesServicio[0]['PVP'];

                    $permanencia = null;


                    $flag = true;


                    $lineaMax = $idLineaDetalleBuscado + $numeroMax;
                    $lineaMaximaBuscado = $idLineaDetalleBuscado + $numeroMax;
                    $d = $numeroMax - $idLineaDetalleBuscado;

                    echo "La diferencia es $d---$numero";
                    if ($idServicio != $servicioLinea & $d != $numero)
                        $idLineaContratoNueva = Contrato::setNuevaLineaContrato(2, $idServi, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1);
                    if ($idServicio == $servicioLinea & $d != $numero)
                        $idLineaContratoNueva = Contrato::setNuevaLineaContrato(2, $idServi, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1);


                }




                if ($idServicio == $servicioLinea && $d == $numero)
                {


                    if ($tipoServicio == ID_SER_INTERNET) {
                        echo "Baja de internet";
                    }
                    if ($tipoServicio == ID_SER_FIJO && $idAtributo == ID_ATRIBUTO_TRONCAL) {

                        $troncal = $lineaDetalles[$i][2];
                        $telefonia = new Telefonia();
                        $telefonia->desactivarLinea($troncal);

                    }
                    if ($tipoServicio == ID_SER_MOVIL)
                    {
                        echo "OK";
                        if ($idAtributo == ID_NUMERO_MOVIL && $lineaDetalles[$i]['ID_SERVICIO'] == $idServicio) {


                            $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                            $idExterno = $externo[0][0];
                            $idProveedor = self::getProveedor($idServicio);
                            $idProveedor = $idProveedor[0][0];


                            if ($idProveedor == ID_PROVEEDOR_MASMOVIL) {

                                $apiMasMovil = new MasMovilAPI();
                                $resultado = $apiMasMovil->getListadoClientes("", $valor);

                                $refClienteAPI = $resultado->Client[0]->refCustomerId;

                                $rs = $apiMasMovil->bajaLineaMovil($refClienteAPI,$valor);
                                self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                            }
                            else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                            {
                                $configuracionAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);

                                $lineaAire=new Linea($configuracionAire[0][3],$configuracionAire[0][1],$configuracionAire[0][2]);

                                $rs=$lineaAire->setSolicitarBajaLinea($valor,"1");
                                self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);
                            }
                        }
                    }
                    if ($tipoServicio == ID_SER_TV)
                    {
                        echo "BAJA TELEVISION";
                    }

                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        for ($k = 0; $k < count($productos); $k++) {
                            Contrato::setProductoBaja($productos[0][0],ID_PRODUCTO_BAJA);
                    }
                    }

                } else
                    {


                    echo "Linea Detalle:$idServi<br>";
                    $nuevaLineaDetalles = Contrato::setNuevaLineaDetalles($idLineaContratoNueva, $tipoServicio, $idServi, $idAtributo, $valor, 1);


                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        for ($k = 0; $k < count($productos); $k++)
                            Contrato::cambiarLineaProducto($idLineaDetalle, $nuevaLineaDetalles);
                    }



                }
            }


           Contrato::generarAnexo($idContrato,1,$idPaquete,$idLinea,ID_ANEXO_BAJA_SERVICIO);

    }

        public static function darBajaPaqueteRompiendo($idEmpresa, $idContrato, $idLinea, $idServicio, $idLineaDetalleBuscado)
        {

            $lineaDetalles = Contrato::getLineaDetalles($idLinea);

            $idPaquete = Contrato::getIdPaqueteLinea($idContrato, $idLinea);
            $idPaquete = $idPaquete[0][0];
            Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

            $numeroMax = 0;
            $util = new util();
            $flag = false;
            $idLineaContratoNueva = 0;


            $usuarioTroncal = "";
            $paqueteDestino = "";
            $grupoRecarga = "";
            $lineaMax = 0;

            for ($i = 0; $i < count($lineaDetalles); $i++)
            {

                $tipoServicio = $lineaDetalles[$i]['ID_TIPO_SERVICIO'];
                $idLineaDetalle = $lineaDetalles[$i]['ID'];
                $idAtributo = $lineaDetalles[$i]['ID_ATRIBUTO_SERVICIO'];
                $valor = $lineaDetalles[$i]['VALOR'];
                $servicioLinea = $lineaDetalles[$i]['ID_SERVICIO'];

                $productos = Contrato::getProductosLinea($idLineaDetalle);


                if ($numeroMax == $idLineaDetalle || $flag == false) {

                    echo "Creamos nueva linea para la linea $servicioLinea<hr>";
                    $numero = $util->selectWhere3('servicios_tipos_atributos',
                        array("count(id)"),
                        "servicios_tipos_atributos.id_servicio=$tipoServicio AND servicios_tipos_atributos.id_tipo=2");

                    $numero = $numero[0][0];
                    $numeroMax = $idLineaDetalle + ($numero);
                    $idServi = $lineaDetalles[$i]['ID_SERVICIO'];
                    $detallesServicio = Servicio::getDetallesServicio($idServi);

                    $precioProveedor = $detallesServicio[0]['PRECIO_PROVEEDOR'];
                    $impuesto = $detallesServicio[0]['IMPUESTO'];
                    $beneficio = $detallesServicio[0]['BENEFICIO'];
                    $pvp = $detallesServicio[0]['PVP'];

                    $permanencia = null;


                    $flag = true;


                    $lineaMax = $idLineaDetalleBuscado + $numeroMax;
                    $lineaMaximaBuscado = $idLineaDetalleBuscado + $numeroMax;
                    $d = $numeroMax - $idLineaDetalleBuscado;

                    echo "La diferencia es $d---$numero";
                    if ($idServicio != $servicioLinea & $d != $numero)
                        $idLineaContratoNueva = Contrato::setNuevaLineaContrato(2, $idServi, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1);
                    if ($idServicio == $servicioLinea & $d != $numero)
                        $idLineaContratoNueva = Contrato::setNuevaLineaContrato(2, $idServi, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, 1);


                }




                if ($idServicio == $servicioLinea && $d == $numero)
                {


                    if ($tipoServicio == ID_SER_INTERNET) {
                        echo "Baja de internet";
                    }
                    if ($tipoServicio == ID_SER_FIJO && $idAtributo == ID_ATRIBUTO_TRONCAL) {

                        $troncal = $lineaDetalles[$i][2];
                        $telefonia = new Telefonia();
                        $telefonia->desactivarLinea($troncal);

                    }
                    if ($tipoServicio == ID_SER_MOVIL)
                    {
                        echo "OK";
                        if ($idAtributo == ID_NUMERO_MOVIL && $lineaDetalles[$i]['ID_SERVICIO'] == $idServicio) {


                            $externo = self::getIdExternoApi($idServicio, $_SESSION['REVENDEDOR']);

                            $idExterno = $externo[0][0];
                            $idProveedor = self::getProveedor($idServicio);
                            $idProveedor = $idProveedor[0][0];


                            if ($idProveedor == ID_PROVEEDOR_MASMOVIL) {

                                $apiMasMovil = new MasMovilAPI();
                                $resultado = $apiMasMovil->getListadoClientes("", $valor);

                                $refClienteAPI = $resultado->Client[0]->refCustomerId;

                                $rs = $apiMasMovil->bajaLineaMovil($refClienteAPI,$valor);
                                self::crearLogApisExternas(ID_API_MASMOVIL,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);

                            }
                            else if ($idProveedor == ID_PROVEEDOR_AIRENETWORKS)
                            {
                                $configuracionAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);

                                $lineaAire=new Linea($configuracionAire[0][3],$configuracionAire[0][1],$configuracionAire[0][2]);

                                $rs=$lineaAire->setSolicitarBajaLinea($valor,"1");
                                self::crearLogApisExternas(ID_API_AIRENETWORKS,$rs,$valor,$_SESSION['REVENDEDOR'],ID_TRANSACCION_BAJA_LINEA);
                            }
                        }
                    }
                    if ($tipoServicio == ID_SER_TV)
                    {
                        echo "BAJA TELEVISION";
                    }

                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        for ($k = 0; $k < count($productos); $k++) {
                            Contrato::setProductoBaja($productos[0][0],ID_PRODUCTO_BAJA);
                        }
                    }

                }else
                {


                    echo "Linea Detalle:$idServi<br>";
                    $nuevaLineaDetalles = Contrato::setNuevaLineaDetalles($idLineaContratoNueva, $tipoServicio, $idServi, $idAtributo, $valor, 1);


                    Contrato::setLineaDetallesBajaId($idLineaDetalle);

                    if($productos!=null)
                    {
                        for ($k = 0; $k < count($productos); $k++)
                            Contrato::cambiarLineaProducto($idLineaDetalle, $nuevaLineaDetalles);
                    }



                }
            }


            Contrato::generarAnexo($idContrato,1,$idPaquete,$idLinea,ID_ANEXO_BAJA_SERVICIO);

        }

        public static function getIdExternoApi($idServicio,$idEmpresa)
        {
        $util=new util();
        return $util->selectWhere3("servicios,servicios_externos", array("ID_EXTERNO"),
        "servicios.id=".$idServicio." AND servicios.id=servicios_externos.id_servicio AND servicios_externos.id_empresa=$idEmpresa");
        }

        public static function getServicioInternoIdAPIMasMovil($idAPI,$idEmpresa)
        {
            $util=new util();
            return $util->selectWhere3("servicios,servicios_externos", array("servicios.nombre"),
                " servicios.id=servicios_externos.id_servicio AND servicios_externos.id_empresa=$idEmpresa AND servicios_externos.id_externo='$idAPI'");
        }

        public static function getProveedor($idServicio)
        {
        $util=new util();
        return $util->selectWhere3("servicios", array("ID_PROVEEDOR"),
        "servicios.id=".$idServicio);
        }

        public static function getNombreServicio($idServicio)
        {
        $util=new util();
        return $util->selectWhere3("servicios", array("NOMBRE"),
        "servicios.id=".$idServicio);
        }

        //Nos devuelve los datos del servicio del cliente
        public static function getDetallesServicio($idServicio)
        {
        $util=new util();
        return $util->selectWhere3("servicios", array("PRECIO_PROVEEDOR","IMPUESTO","BENEFICIO","PVP","ID"),
        "servicios.id=".$idServicio." AND servicios.id_empresa=".$_SESSION['REVENDEDOR']);
        }

        // ruben corrales

        //Devuelve el grupo de recarga y el paquete destino de un servicio dado
        public static function getGrupoRecargayPaqueteDestino($idServicio)
        {

        $util = new util();
        //selectJoin($tabla, $campos, $join, $order, $where=null, $group=null)
        $result= $util->selectJoin( "servicios_atributos", array("servicios_atributos.VALOR"),
                                "join servicios on servicios_atributos.ID_SERVICIO = servicios.id " ,
                                ' servicios_atributos.ID_TIPO_ATRIBUTO ',' servicios.id = '. $idServicio. ' and servicios_atributos.ID_TIPO_ATRIBUTO IN (43,44)');
        $fieldNames=array();

        while ($row = mysqli_fetch_array($result))
        {
        array_push($fieldNames, $row[0]);
        }

        return $fieldNames;
        }

        /*
        * DEVUELVE LOS SERVICIOS DEL RESELLER CON AIRENETWORKS
        */
        public static function getServiciosAireNetworks($idEmpresa)
        {
        $util=new util();

        return $util->selectWhere3("servicios left join servicios_externos on servicios.id=servicios_externos.id_servicio", array("servicios.id,servicios.nombre,servicios_externos.id_externo"),
        "servicios.id_proveedor=15 AND servicios.id_empresa=$idEmpresa");
        }

        public static function setServicioExterno($idEmpresa,$idServicio,$idExterno)
        {
        $util = new util();

        $rs=$util->selectWhere3("servicios_externos",
        array("servicios_externos.id"),
        "servicios_externos.id_servicio AND servicios_externos.id_empresa=$idEmpresa AND servicios_externos.id_servicio=$idServicio");

        $values = array($idServicio,$idExterno,$idEmpresa);

        if($rs==null)
        {

        $tabla = array("ID_SERVICIO","ID_EXTERNO","ID_EMPRESA");

        $values = array($idServicio,$idExterno,$idEmpresa);

        return $util->insertInto('servicios_externos', $tabla, $values);
        }
        else
        {

        $campos = array("ID_SERVICIO","ID_EXTERNO","ID_EMPRESA");

        return $util->update('servicios_externos', $campos, $values, "id=".$rs[0][0]);
        }


        }

    }

