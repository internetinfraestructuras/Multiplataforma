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

        $idLineaDetalleNueva=Contrato::setNuevaLineaDetalles($idLineaNueva,$tipo,$idAsoc,$idAtrib,$valor,1);


        $productosLinea=Contrato::getProductosLinea($idLineaDetalle);


        for($j=0;$j<count($productosLinea);$j++)
        {
            Contrato::cambiarLineaProducto($idLineaDetalle,$idLineaDetalleNueva);
        }

    }

/*
    $values=array($precioProveedor,$impuesto,$beneficio,$pvp,$permanencia,1,);
    $campos=array("precio_proveedor","impuesto","beneficio","pvp");
    $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=".$idServicio. " AND id_contrato=".$_POST['idContrato']." AND id=".$_POST['idLinea']);
*/
    Contrato::generarAnexo($idContrato,$idServicio,3);








}
    public static function actualizarServicioPaqueteContrato($idContrato,$idLinea,$id,$tipo,$idServicio,$atributos,$fechaCambio,$idServicioOriginal)
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



        for($k=0;$k<count($lineaDetallesAll);$k++)
        {



            if($lineaDetallesAll[$k]['ID_SERVICIO']==$idServicioOriginal)
            {

                if($atributos!=null)
                {


                    for($i=0;$i<count($atributos['id']);$i++)
                    {
                        if($atributos['id'][$i]==$lineaDetallesAll[$k]['ID_ATRIBUTO_SERVICIO'])
                        {
                            echo "Attr:".$i;
                            $idAtrib=$atributos['id'][$i];
                            $valor=$atributos['valor'][$i];

                            $tipo=$lineaDetallesAll[$k]['ID_TIPO_SERVICIO'];
                            $ser=$lineaDetallesAll[$k]['ID_SERVICIO'];

                            $idLineaDetalleNueva=Contrato::setNuevaLineaDetallesPaquete($nuevaLinea,$tipo,$idAtrib,$valor,1,$idServicio,$fechaCambio);
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
            echo "<hr/>";

            $productosLinea=Contrato::getProductosLinea($lineaDetallesAll[$k]['ID']);

            for($j=0;$j<count($productosLinea);$j++)
            {
                echo "CAMBIAMOS LA LINEA DEL PRODUCTO";
                Contrato::cambiarLineaProducto($lineaDetallesAll[$k]['ID'],$idLineaDetalleNueva);
            }

        }








        Contrato::generarAnexo($idContrato,$idServicio,3);

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

    public static function romperPaquete($idContrato,$idLinea,$servicio,$fechaCambio)
    {
        //Sacamos las fechas para los estados

        //Si la fecha es hoy se pasa el anterior a BAJA(2) y el siguiente a 1(ALTA)
        //Si la fecha es superior a hoy se pasa el actual a PROCESO DE BAJA(4) y el nuevo a (3).
        $estadoLineaActual=self::obtenerEstadosPorFechas($fechaCambio);
        $estadoLineaNueva=0;
        $cambioHoy=false;
        if($estadoLineaActual==2)
        {
            $estadoLineaNueva=1;
            $cambioHoy=true;
        }

        else if($estadoLineaActual==4)
            $estadoLineaNueva=8;


        //1-Obtener líneas de detalles de esa línea de contrato
        //2-Obtener de cada linea de detalles los productos
        //3-Obtener los productos de cada linea de detalles
        //4-Generar nueva línea como servicios independientes
        //5-Generar la nueva linea de detalle
        //5-Asociad la linea de detalle a la nueva línea
        //6-Actualizar los productos a la nueva linea de detalle

        $lineasDetalles=Contrato::getLineaDetallesActivasAgrupadasServicio($idLinea);



        for($i=0;$i<count($lineasDetalles);$i++)
        {
            //Obtengo el paquete
            $rsPaquete=Contrato::getIdPaqueteLinea($idContrato,$idLinea);

            $idPaquete=$rsPaquete[0]['ID_ASOCIADO'];
            $productosLineas=Contrato::getProductosLinea($lineasDetalles[$i]['ID']);


            $tipoServicio=$lineasDetalles[$i]['ID_TIPO_SERVICIO'];
            $servicio=$lineasDetalles[$i]['ID_SERVICIO'];
            //Obtengo los precios del servicio estandar de esta empresa
            $datosServicio=self::getDetallesServicio($servicio);
            $precioProveedor=$datosServicio[0]['PRECIO_PROVEEDOR'];
            $beneficio=$datosServicio[0]['PRECIO_PROVEEDOR'];
            $impuesto=$precioProveedor=$datosServicio[0]['PRECIO_PROVEEDOR'];
            $pvp=$precioProveedor=$datosServicio[0]['PRECIO_PROVEEDOR'];


            //Genero las lineas nuevas de contrato
            $lineaNueva=Contrato::setNuevaLineaContrato(2,$servicio,$idContrato,$precioProveedor,$beneficio,$impuesto,$pvp,"",$estadoLineaNueva,$fechaCambio);

            //Pongo a baja la anterior linea
            //Contrato::setNuevaLineaContratoBaja();

            //Obtengo las lineas de detalles por el servicio.
            $detalles=Contrato::getLineaDetallesServicioActivas($idLinea,$servicio);

            for($j=0;$j<count($detalles);$j++)
            {
                $tipo=$detalles[$i]['ID_TIPO_SERVICIO'];
                $atributo=$detalles[$i]['ID_ATRIBUTO_SERVICIO'];
                $valor=$detalles[$i]['VALOR'];
                $lineaDetalle=$detalles[$i]['ID'];
                $estado=6;

                $idLineaDetalleNueva=Contrato::setNuevaLineaDetalles($lineaNueva,$tipo,$atributo,$valor,$estadoLineaNueva,$fechaCambio);

                echo "<hr>Linea detalle BAJA";
                Contrato::setLineaDetallesBaja($lineaDetalle,$fechaCambio);


            }
            //Cambiamos los productos de líneas si es el día actual, sino se cambiarían en el cron;
            if($cambioHoy)
                Contrato::cambiarLineaProducto($lineaDetalle,$idLineaDetalleNueva);
            //GENERAR ANEXO BAJA SERVICIO



        }



        //Dar de Baja todas las líneas de detalles antiguas;

        //Generar anexo de rotura de paquete y de alta de servicios nuevos
        Contrato::generarAnexo($idContrato,$servicio,8);
        if($cambioHoy)
            Contrato::setLineaContratoBaja($idContrato,$idLinea,$idPaquete,$fechaCambio);
        else
            Contrato::setLineaContratoBajaCambio($idContrato,$idLinea,$idPaquete,$fechaCambio);


    }

    //Nos devuelve los datos del servicio del cliente
    public static function getDetallesServicio($idServicio)
    {
        $util=new util();
        return $util->selectWhere3("servicios", array("PRECIO_PROVEEDOR","IMPUESTO","BENEFICIO","PVP","ID"),
            "servicios.id=".$idServicio." AND servicios.id_empresa=".$_SESSION['REVENDEDOR']);
    }

}

