<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 02/08/2018
 * Time: 13:15
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ('Contrato.php');
require_once ('masmovil/MasMovilAPI.php');

/*

require_once('../config/def_tablas.php');
*/


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
public static function actualizarServicioContrato($idContrato,$idLinea,$idServicio,$idServicioNuevo,$precioProveedor,$beneficio,$pvp,$impuesto,$atributos)
{
    echo "Obtenemos las líneas<br><br><br>";
    $lineaContrato=Contrato::getLineaContratoServicio($idContrato,$idLinea,$idServicio);


    echo "Obtenemos las líneas de detalles<br><br><br>";
    $lineaDetalles=Contrato::getLineaDetalles($idLinea);


    //Obtenemos los datos necesarios para volcarlos en la nueva tupla
    $tipo=$lineaContrato[0][0];
    $idAsoc=$lineaContrato[0][1];
    $idContrato=$lineaContrato[0][2];
    $permanencia=$lineaContrato[0][7];

    echo "el tipo de servicio es".$tipo."<br>";

    Contrato::setLineaDetallesBaja($idLinea,null);//Seteamos la linea actual a baja
    Contrato::setLineaContratoBaja($idContrato,$idLinea,$idServicio);

    $idLineaNueva=Contrato::setNuevaLineaContrato($tipo,$idServicioNuevo,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1);

    //Seteamos los detalles de una línea de contrato

    echo "<hr>ATRIBUTOS";
    echo "Tenemos que volcar ".count($atributos);
    var_dump($idLineaNueva);
    echo "<hr>";

    for($i=0;$i<count($lineaDetalles);$i++)
    {

        $idAtrib=$atributos['id'][$i];
        $valor=$atributos['valor'][$i];

        $tipo=$lineaDetalles[$i][0];
        $atributo=$lineaDetalles[$i][1];

        echo "El nuevo atributo es".$atributo." con un valor de".$valor." y con el ID".$idAtrib."<br>";

        $idLineaDetalle=Contrato::getLineaDetalles($idLinea);

        $idLineaDetalle=$idLineaDetalle[0]['ID'];
        echo "<br>";
        echo "La linea a buscar es".$idLineaDetalle;
        echo "<br>";

        if($tipo==1)
        {
            echo "Aqui va cambio técnico ONT";
        }
        else if($tipo==2)
        {
            echo "Aqui va el cambio de telefonia";
        }
        else if($tipo==3 && $idAtrib==ID_NUMERO_MOVIL)
        {
            $externo=self::getIdExternoApi($idServicio);

            $idExterno=$externo[0][0];
            $idProveedor=self::getProveedor($idServicio);
            $idProveedor=$idProveedor[0][0];

            if($idProveedor==ID_PROVEEDOR_MASMOVIL)
            {
                echo "El proveedor es MASMOVIL<HR>";
                $apiMasMovil=new MasMovilAPI();
                $resultado=$apiMasMovil->getListadoClientes("",$valor);
                $refClienteAPI=$resultado->Client[0]->refCustomerId;
                echo "Es el numero".$valor;
                $res= $apiMasMovil->cambioProducto($refClienteAPI,"",$valor,$idExterno);
                Contrato::generarAnexo($idContrato,$idServicio,3,$idLineaDetalle);
                $apiMasMovil->setLogApi($valor,$res,$_SESSION['REVENDEDOR'],1);
            }
            else if($idProveedor==ID_PROVEEDOR_AIRENETWORKS)
            {
                echo "El proveedor es AIRENETWORK<HR>";
            }
        }
        else if($tipo==4)
        {
            echo "aqui va el cambio de TV";
        }

        $idLineaDetalleNueva=Contrato::setNuevaLineaDetalles($idLineaNueva,$tipo,$idAsoc,$idAtrib,$valor,1);



        $productosLinea=Contrato::getProductosLinea($idLineaDetalle);


        for($j=0;$j<count($productosLinea);$j++)
        {
            Contrato::cambiarLineaProducto($idLineaDetalle,$idLineaDetalleNueva);
        }

    }

}
    public static function actualizarServicioPaqueteContrato($idContrato,$idLinea,$id,$tipo,$idServicio,$atributos,$fechaCambio,$idServicioOriginal,$idLineaDetalle)
    {

        $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);


        $precioProveedor=$lineaContrato[0]['PRECIO_PROVEEDOR'];
        $beneficio=$lineaContrato[0]['BENEFICIO'];
        $impuesto=$lineaContrato[0]['IMPUESTO'];
        $pvp=$lineaContrato[0]['PVP'];
        $permanencia=$lineaContrato[0]['PERMANENCIA'];

        $lineaDetallesAll=Contrato::getLineaDetalles($idLinea);


        $idPaquete=Contrato::getIdPaqueteLinea($idContrato,$idLinea);



        $idPaquete=$idPaquete[0]['ID_ASOCIADO'];



        Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete,$fechaCambio);

        $nuevaLinea=Contrato::setNuevaLineaContrato(1,$idPaquete,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1,$fechaCambio);


        $util=new util();
        $numero= $util->selectWhere3('servicios_tipos_atributos',
            array("count(id)"),
            "servicios_tipos_atributos.id_servicio=$tipo AND servicios_tipos_atributos.id_tipo=2");

        $numero=$numero[0][0];
        $numeroMax=$idLineaDetalle+($numero-1);



        for($k=0;$k<count($lineaDetallesAll);$k++)
        {
            if($lineaDetallesAll[$k]['ID_SERVICIO']==$idServicioOriginal && $lineaDetallesAll[$k]['ID']<=$numeroMax )
            {

                if($atributos!=null)
                {


                    for($i=0;$i<count($atributos['id']);$i++)
                    {


                        if($atributos['id'][$i]==$lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'] )
                        {

                          $idAtrib=$atributos['id'][$i];
                            $valor=$atributos['valor'][$i];

                            $tipo=$lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                            $ser=$lineaDetallesAll[$k]['ID_SERVICIO'];

                            $idLineaDetalleNueva=Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$idServicio,$fechaCambio);



                            if($tipo==1)
                            {
                                echo "es un servicio de internet aqui va el cambio técnico";
                            }
                            else if($tipo==2)
                            {
                                echo "Es un telefono fijo aquie va el cambio tecnico";
                            }

                            else if($tipo==3 && $idAtrib==ID_NUMERO_MOVIL)
                            {
                                $externo=self::getIdExternoApi($idServicio);

                                $idExterno=$externo[0][0];
                                $idProveedor=self::getProveedor($idServicio);
                                $idProveedor=$idProveedor[0][0];

                                if($idProveedor==ID_PROVEEDOR_MASMOVIL)
                                {
                                    echo "El proveedor es MASMOVIL<HR>";
                                    $apiMasMovil=new MasMovilAPI();
                                    $resultado=$apiMasMovil->getListadoClientes("",$valor);
                                    $refClienteAPI=$resultado->Client[0]->refCustomerId;
                                    echo "Es el numero".$valor;
                                   $res= $apiMasMovil->cambioProducto($refClienteAPI,"",$valor,$idExterno);

                                   $apiMasMovil->setLogApi($valor,$res,$_SESSION['REVENDEDOR'],1);
                                }
                                else if($idProveedor==ID_PROVEEDOR_AIRENETWORKS)
                                {
                                    echo "El proveedor es AIRENETWORK<HR>";
                                }

                            }
                            else if($tipo==4)
                            {
                                echo "Es una televisión aqui va el cambio técnico";
                            }



                        }

                    }

                }


            }
            else
            {

                $idAtrib=$lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'];
                $valor=$lineaDetallesAll[$k]['VALOR'];

                $tipo=$lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                $ser=$lineaDetallesAll[$k]['ID_SERVICIO'];

                 $idLineaDetalleNueva=Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$ser,$fechaCambio);
            }

            Contrato::setLineaDetallesBajaServicio($idLinea,$ser,$fechaCambio);//Seteamos la linea actual a baja


            $productosLinea=Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

            for($j=0;$j<count($productosLinea);$j++)
            {

              Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'],$idLineaDetalleNueva);
            }

        }
        Contrato::generarAnexo($idContrato,1,$idPaquete,$idLinea,10);









    }


    public static function darBajaServicio($idContrato,$idLineaContrato,$idServicio,$fechaBaja,$productos)
    {

        Contrato::setLineaContratoBaja($idContrato,$idLineaContrato,$idServicio,$fechaBaja);
        Contrato::setLineaDetallesBaja($idContrato,$fechaBaja);
        $ld=Contrato::getLineaDetalles($idLineaContrato);

        $lineaDetalle=$ld[0]['ID'];


        if($productos!='[]' || $productos!=null)
            Contrato::setProductoRMA($idContrato,$productos,$lineaDetalle,$fechaBaja);

        Contrato::generarAnexo($idContrato,$idServicio,5);



    }

public static function darBajaServicioPaquete($idContrato,$idLinea,$idPaquete,$idServicio)
{
/*
 * 1. Obtener la línea de contrato
 * 2. Obtener la línea de detalle
 * 3. Obtener la linea de productos
 * 4. Comparar con el servicio que queremos eliminar
 */
    //1
    $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);


    $precioProveedor=$lineaContrato[0]['PRECIO_PROVEEDOR'];
    $beneficio=$lineaContrato[0]['BENEFICIO'];
    $impuesto=$lineaContrato[0]['IMPUESTO'];
    $pvp=$lineaContrato[0]['PVP'];
    $permanencia=$lineaContrato[0]['PERMANENCIA'];

    $lineaDetalles=Contrato::getLineaDetallesServicio($idLinea,$idServicio);

    echo "<br>";
    echo "<br>";
    echo "<hr>";
    Contrato::setLineaDetallesBajaServicio($idLinea,$idServicio);//Seteamos la linea actual a baja
    echo "<br>";
    echo "<br>";
    echo "<hr>";
    Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

    echo "El id del paquete es".$idPaquete;
    $nuevaLinea=Contrato::setNuevaLineaContrato(1,$idPaquete,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1);

    echo "<hr>La nueva linea es".$nuevaLinea."<br>";
    //Seteamos los detalles de una línea de contrato

    if($atributos==null)
    {

        Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,'null','null',1,$idServicio);
        Contrato::setLineaDetallesBajaServicio($nuevaLinea,$idServicio);
    }
    else
    {
        echo "<br>";
        echo "<br>".count($atributos);
        echo "<hr>";
        for($i=0;$i<count($atributos);$i++)
        {

            $idAtrib=$atributos['id'][$i];
            $valor=$atributos['valor'][$i];

            $tipo=$lineaDetalles[$i][0];
            $atributo=$lineaDetalles[$i][1];


            Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$idServicio,$fechaCambio);



        }
    }

    $productosLinea=Contrato::getProductosLinea($idLinea);

    for($j=0;$j<count($productosLinea);$j++)
    {
        Contrato::cambiarLineaProducto($idLinea,$nuevaLinea);
    }





    Contrato::generarAnexo($idContrato,$idServicio,3);

}
public static function cancelarBajaServicio($idContrato,$idLineaContrato,$idServicio,$productos)
    {

        Contrato::setLineaContratoAlta($idContrato,$idLineaContrato,$idServicio);
        Contrato::setLineaDetallesAlta($idLineaContrato);

        $ld=Contrato::getLineaDetalles($idLineaContrato);
        $lineaDetalle=$ld[0]['ID'];

        if($productos!='[]' || $productos!=null)
            Contrato::setProductoInstalado($idContrato,$productos,$idLineaContrato);





        Contrato::generarAnexo($idContrato,$idServicio,6);



    }

    //Obtiene el estado al que pasará la línea anterior y la nueva según las fechas
    public static function obtenerEstadosPorFechas($fecha)
    {
        $fecha_actual = strtotime(date("y-m-d",time()));
        $fechaBaja= strtotime(date("y-m-d",strtotime($fecha)));

        if($fecha_actual < $fechaBaja)
            $tipoEstado=4;
        else
            $tipoEstado=2;


        return $tipoEstado;
    }

    public static function  romperPaquete($idContrato,$idLinea,$servicio,$fechaCambio,$idLineaDetalleActual)
    {


        $lineaDetalles=Contrato::getLineaDetalles($idLinea);

        $idPaquete=Contrato::getIdPaqueteLinea($idContrato,$idLinea);
        $idPaquete=$idPaquete[0][0];
        Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete);

        $numeroMax=0;
        $util=new util();
        $flag=false;
        $idLineaContratoNueva=0;
        $idServicio=0;


        for($i=0;$i<count($lineaDetalles);$i++)
        {

            $tipoServicio=$lineaDetalles[$i]['ID_TIPO_SERVICIO'];
            $idLineaDetalle=$lineaDetalles[$i]['ID'];
            $idAtributo=$lineaDetalles[$i]['ID_ATRIBUTO_SERVICIO'];
            $valor=$lineaDetalles[$i]['VALOR'];



            if($idAtributo==ID_NUMERO_MOVIL && $lineaDetalles[$i]['ID_SERVICIO']==$servicio)
            {

                    echo "<hr>Entramos en el servicio";


                    $externo=self::getIdExternoApi($servicio);

                    $idExterno=$externo[0][0];
                    $idProveedor=self::getProveedor($servicio);
                    $idProveedor=$idProveedor[0][0];


                    if($idProveedor==ID_PROVEEDOR_MASMOVIL)
                    {
                        echo "<br>El proveedor es MASMOVIL<HR>";
                        $apiMasMovil=new MasMovilAPI();
                        $resultado=$apiMasMovil->getListadoClientes("",$valor);

                        $refClienteAPI=$resultado->Client[0]->refCustomerId;

                        $res= $apiMasMovil->cambioProducto($refClienteAPI,"",$valor,$idExterno);


                        $apiMasMovil->setLogApi($valor,$res,$_SESSION['REVENDEDOR'],1);
                    }
                    else if($idProveedor==ID_PROVEEDOR_AIRENETWORKS)
                    {
                        echo "El proveedor es AIRENETWORK<HR>";
                    }
            }





            if($idLineaDetalle==$idLineaDetalleActual)
                $idServicio=$servicio;
            else
                $idServicio=$lineaDetalles[$i]['ID_SERVICIO'];

            if($numeroMax==$idLineaDetalle || $flag==false)
            {

                $numero = $util->selectWhere3('servicios_tipos_atributos',
                    array("count(id)"),
                    "servicios_tipos_atributos.id_servicio=$tipoServicio AND servicios_tipos_atributos.id_tipo=2");

                $numero = $numero[0][0];
                $numeroMax = $idLineaDetalle + ($numero);

                $detallesServicio=Servicio::getDetallesServicio($idServicio);

                $precioProveedor=$detallesServicio[0]['PRECIO_PROVEEDOR'];
                $impuesto=$detallesServicio[0]['IMPUESTO'];
                $beneficio=$detallesServicio[0]['BENEFICIO'];
                $pvp=$detallesServicio[0]['PVP'];

                $permanencia=null;

                $idLineaContratoNueva=Contrato::setNuevaLineaContrato(2,$idServicio,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,$permanencia,1);

                $flag=true;

            }

            $nuevaLineaDetalles=Contrato::setNuevaLineaDetalles($idLineaContratoNueva,$tipoServicio,$idServicio,$idAtributo,$valor,1);



            Contrato::setLineaDetallesBaja($idLineaDetalle);
            $productos=Contrato::getProductosLinea($idLineaDetalle);


            for($k=0;$k<count($productos);$k++)
            {
                Contrato::cambiarLineaProducto($idLineaDetalle,$nuevaLineaDetalles);
            }

        }

        Contrato::generarAnexo($idContrato,1,$idPaquete,$idLinea,8);



    }

    public static function getIdExternoApi($idServicio)
    {
        $util=new util();
        return $util->selectWhere3("servicios,servicios_externos", array("ID_EXTERNO"),
            "servicios.id=".$idServicio." AND servicios.id=servicios_externos.id_servicio");
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

}

