<?php
//require_once('../config/util.php');
//require_once('../config/def_tablas.php');
require_once('Orden.php');

class Contrato
{

    //Crea la cabecera de un nuevo contrato
    public static function setNuevoContrato($cliente, $fechaFin, $estado)
    {
        $util = new util();
        $t_contratos = array("ID_EMPRESA", "ID_CLIENTE", "FECHA_INICIO", "FECHA_FIN", "ESTADO", "NUMERO");
        $nuevoContrato = intval($util->selectMax('contratos', 'NUMERO','ID_EMPRESA ='.$_SESSION['REVENDEDOR']))+1;
        $values = array($_SESSION['REVENDEDOR'], $cliente, date("Y-m-d", time()), $fechaFin, $estado, $nuevoContrato);

        return $util->insertInto('contratos', $t_contratos, $values);
    }

    //Devuelve la linea contratos_lineas de un contrato de una dicha linea con un idServicio especifico.
    public static function getLineaContratoServicio($idContrato, $idLinea, $idServicio)
    {

        $util = new util();

        return $util->selectWhere3("contratos_lineas", array("ID_TIPO", "ID_ASOCIADO", "ID_CONTRATO", "PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO", "PVP", "PERMANENCIA"),
            "contratos_lineas.id_asociado=" . $idServicio . " AND contratos_lineas.id_contrato=" . $idContrato . " AND id=" . $idLinea);
    }

    // Agrega documentos tipo firma y escaneados al contrato (Rubén)
    public static function setNuevoDocumento($contrato, $tipo, $url, $svg)
    {
        // tipos: 1= firma, 2:pdf, 3=jpg
        $util = new util();
        $campos = array("ID_CONTRATO","TIPO","FICHERO","SVG");
        $values = array($contrato, $tipo, $url, $svg);

        return $util->insertInto('contratos_documentos', $campos, $values);
    }


    // Agrega productos a contratos_lineas_productos (Rubén)
    public static function setNuevoProductoContrato($idlinea, $idproducto, $estado, $fecha=null)
    {
        if ($fecha == null)
            $fecha = date('Y-m-d');

        $util = new util();
        $campos = array("ID_LINEA","ID_PRODUCTO","ESTADO", 'FECHA_ALTA');
        $values = array($idlinea, $idproducto, $estado, $fecha);

        return $util->insertInto('contratos_lineas_productos', $campos, $values, true);
    }



    /*
     * SELECT ID_TIPO,ID_ASOCIADO,ID_CONTRATO,PRECIO_PROVEEDOR,BENEFICIO,IMPUESTO,PVP,PERMANENCIA
FROM contratos_lineas,contratos_lineas_detalles
WHERE contratos_lineas.id=contratos_lineas_detalles.ID_LINEA AND
contratos_lineas_detalles.ID_SERVICIO=25 AND contratos_lineas.id_contrato=2 AND contratos_lineas.id=251 AND contratos_lineas_detalles.ESTADO=1
     */
    public static function getLineaContratoPaquete($idContrato, $idLinea, $idServicio)
    {

        $util = new util();

        return $util->selectWhere3("contratos_lineas,contratos_lineas_detalles",
            array("ID_TIPO", "ID_ASOCIADO", "ID_CONTRATO", "PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO", "PVP", "PERMANENCIA"),
            "contratos_lineas.id=contratos_lineas_detalles.id_linea AND contratos_lineas_detalles.id_servicio=" . $idServicio . " 
            AND contratos_lineas.id_contrato=" . $idContrato . " AND contratos_lineas.id=" . $idLinea);
    }

    public static function getLineaContrato($idContrato, $idLinea)
    {

        $util = new util();

        return $util->selectWhere3("contratos_lineas", array("ID_TIPO", "ID_ASOCIADO", "ID_CONTRATO", "PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO", "PVP", "PERMANENCIA"),
            "contratos_lineas.id_contrato=" . $idContrato . " AND id=" . $idLinea);
    }

    //obtiene las linea de detalle de un contrato
    public static function getLineaDetalles($idLinea)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR", "ID"),
            "contratos_lineas_detalles.id_linea=" . $_POST['idLinea']);
    }

    //obtiene las linea de detalle de un contrato
    public static function getLineaDetallesActivas($idLinea)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR", "ID"),
            "contratos_lineas_detalles.id_linea=" . $idLinea . " AND contratos_lineas_detalles.estado=1");
    }

    //obtiene las linea de detalle de un contrato agrupadas por un servicio, no sirve para dar de alta al romper el paquete
    public static function getLineaDetallesActivasAgrupadasServicio($idLinea)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR", "ID", "ID_SERVICIO"),
            "contratos_lineas_detalles.id_linea=" . $idLinea . " AND contratos_lineas_detalles.estado=1 GROUP BY contratos_lineas_detalles.id_servicio");
    }

    //obtiene las linea de detalle de un contrato
    public static function getLineaDetallesServicio($idLinea, $idServicio)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR"),
            "contratos_lineas_detalles.id_linea=" . $idLinea . " AND contratos_lineas_detalles.id_servicio=" . $idServicio);
    }


    //obtiene las linea de detalle de un contrato
    public static function getLineaDetallesServicioActivas($idLinea, $idServicio)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_detalles", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR"),
            "contratos_lineas_detalles.id_linea=" . $idLinea . " AND contratos_lineas_detalles.id_servicio=" . $idServicio . " AND contratos_lineas_detalles.estado=1");
    }


    //Nos devuelve el id del paquete al que apunta una línea de contrato
    public static function getIdPaqueteLinea($idContrato, $idLinea)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas,contratos", array("ID_ASOCIADO"),
            "contratos_lineas.id=" . $idLinea . " AND contratos_lineas.id_contrato=contratos.id AND contratos.id_empresa=" . $_SESSION['REVENDEDOR'] . " AND contratos_lineas.id_contrato=" . $idContrato);
    }

    //Establece una línea de un contrato de baja
    public static function setLineaContratoBaja($idContrato, $idLinea, $idServicio, $fecha = null)
    {
        $util = new util();

        $campos = array('ESTADO', 'FECHA_BAJA');

        $fecha_actual = strtotime(date("y-m-d", time()));
        $fechaBaja = strtotime(date("y-m-d", strtotime($fecha)));

        if ($fecha_actual < $fechaBaja)
            $estado = 4;
        else
            $estado = 2;


        if ($fecha == null)
            $values = array($estado, date('Y-m-d'));
        else
            $values = array($estado, $fecha);

        $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=" . $idServicio . " AND id_contrato=" . $idContrato . " AND id=" . $idLinea);
    }

    //Establece una línea de un contrato de baja
    public static function setLineaContratoBajaCambio($idContrato, $idLinea, $idServicio, $fecha = null)
    {
        $util = new util();

        $campos = array('ESTADO', 'FECHA_BAJA');

        $tipo = 7;

        if ($fecha == null)
            $values = array($tipo, date('Y-m-d'));
        else
            $values = array($tipo, $fecha);

        $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=" . $idServicio . " AND id_contrato=" . $idContrato . " AND id=" . $idLinea);
    }

    //Establece una línea de un contrato de baja

    public static function setLineaContratoAlta($idContrato, $idLinea, $idServicio)
    {
        $util = new util();

        $campos = array('ESTADO', 'FECHA_BAJA');
        $values = array(1, null);


        $result = $util->update('contratos_lineas', $campos, $values, "id_asociado=" . $idServicio . " AND id_contrato=" . $idContrato . " AND id=" . $idLinea);
    }


    public static function comprobarFechas($fecha)
    {
        $fecha_actual = strtotime(date("y-m-d", time()));
        $fechaBaja = strtotime(date("y-m-d", strtotime($fecha)));

        if ($fecha_actual < $fechaBaja)
            $tipoEstado = 8;
        else
            $tipoEstado = 2;


        return $tipoEstado;
    }

    //Establece una línea detalle a baja
    public static function setLineaDetallesBaja($idLineaContrato, $fechaBaja)
    {
        $util = new util();
        $campos = array('ESTADO', 'FECHA_BAJA');
        $fecha_actual = strtotime(date("y-m-d", time()));
        $fechaBaja = strtotime(date("y-m-d", strtotime($fechaBaja)));
        $tipo=0;
        if ($fecha_actual < $fechaBaja)
            $tipo = 4;
        else
            $tipo = 2;

        if ($fechaBaja == null)
            $values = array($tipo, date('Y-m-d'));
        else
            $values = array($tipo, $fechaBaja);

        return $util->update('contratos_lineas_detalles', $campos, $values, "id_linea=" . $idLineaContrato);
    }

    //Establece una línea detalle a baja
    public static function setLineaDetallesAlta($idLineaContrato)
    {
        $util = new util();
        $campos = array('ESTADO', 'FECHA_BAJA');

        $values = array(1, null);


        $result = $util->update('contratos_lineas_detalles', $campos, $values, "id_linea=" . $idLineaContrato);
    }


    //Establece una línea detalle a baja con un idServicio
    public static function setLineaDetallesBajaServicio($idLineaContrato, $idServicio, $fechaBaja = null)
    {
        $util = new util();

        $campos = array('ESTADO', 'FECHA_BAJA');

        $fecha_actual = strtotime(date("y-m-d", time()));
        $fechaBaja = strtotime(date("y-m-d", strtotime($fechaBaja)));

        if ($fecha_actual < $fechaBaja)
            $estado = 7;
        else
            $estado = 2;


        if ($fechaBaja == null)
            $values = array($estado, date('Y-m-d'));
        else
            $values = array($estado, $fechaBaja);


        $result = $util->update('contratos_lineas_detalles', $campos, $values, "id_linea=" . $idLineaContrato . " AND id_servicio=" . $idServicio);
    }


    //Genera una nueva línea en un contrato
    public static function setNuevaLineaContrato($tipo, $servicio, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado, $fechaAlta = null)
    {
        $util = new util();
        $t_contratos_lineas = array("ID_TIPO", "ID_ASOCIADO", "ID_CONTRATO", "PRECIO_PROVEEDOR", "BENEFICIO", "IMPUESTO", "PVP", "PERMANENCIA", "ESTADO", "FECHA_ALTA", "FECHA_BAJA");

        if ($fechaAlta != null) {
            $fecha_actual = strtotime(date("y-m-d", time()));
            $fechaBaja = strtotime(date("y-m-d", strtotime($fechaAlta)));

            if ($fecha_actual < $fechaBaja)
                $estado = 8;
            else
                $estado = 1;
        }

        //SE SETEA LA NUEVA LÍNEA DE CONTRATO EN ALTA
        if ($fechaAlta == null)
            $values = array($tipo, $servicio, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado, date('Y-m-d '), "");
        else
            $values = array($tipo, $servicio, $idContrato, $precioProveedor, $beneficio, $impuesto, $pvp, $permanencia, $estado, $fechaAlta, "");
        return $util->insertInto('contratos_lineas', $t_contratos_lineas, $values);
    }

    //Establece una nueva línea de detalle
    public static function setNuevaLineaDetalles($idLinea, $tipo, $idservicio, $atributo, $valor, $estado, $fechaAlta = null)
    {
        $util = new util();
        $t_contratos_lineas_detalles = array("ID_LINEA", "ID_TIPO_SERVICIO", "ID_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR", "FECHA_ALTA", "FECHA_BAJA", "ESTADO");
        if ($fechaAlta == null)
            $values = array($idLinea, $tipo, $idservicio, $atributo, $valor, date('Y-m-d '), '', $estado);
        else
            $values = array($idLinea, $tipo, $idservicio, $atributo, $valor, $fechaAlta, '', $estado);

        return $util->insertInto('contratos_lineas_detalles', $t_contratos_lineas_detalles, $values);
    }

    public static function setNuevaLineaDetallesPaquete($idLinea, $tipo, $atributo, $valor, $estado, $idServicio, $fecha = null)
    {
        $util = new util();


        if ($fecha != null)
            $estado = self::comprobarFechas($fecha);

        if ($estado == 2)
            $estado = 1;
        if ($estado == 7)
            $estado = 8;

        if ($atributo == 'null' || $valor == 'null') {
            $t_contratos_lineas_detalles = array("ID_LINEA", "ID_TIPO_SERVICIO", "FECHA_ALTA", "FECHA_BAJA", "ESTADO", "ID_SERVICIO");
            if (fecha == null)
                $values = array($idLinea, $tipo, date('Y-m-d '), '', $estado, $idServicio);
            else
                $values = array($idLinea, $tipo, $fecha, '', $estado, $idServicio);
        } else {
            $t_contratos_lineas_detalles = array("ID_LINEA", "ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR", "FECHA_ALTA", "FECHA_BAJA", "ESTADO", "ID_SERVICIO");
            if ($fecha == null)
                $values = array($idLinea, $tipo, $atributo, $valor, date('Y-m-d '), '', $estado, $idServicio);
            else
                $values = array($idLinea, $tipo, $atributo, $valor, $fecha, '', $estado, $idServicio);
        }

        $util->insertInto('contratos_lineas_detalles', $t_contratos_lineas_detalles, $values);
    }

    //obtener productos asociados a una línea de contrato
    public static function getProductosLinea($idLinea)
    {
        $util = new util();
        return $util->selectWhere3("contratos_lineas_productos", array("ID_PRODUCTO", "ESTADO"),
            "contratos_lineas_productos.id_linea=" . $idLinea);
    }

    //Cambiar el producto a otra línea
    public static function cambiarLineaProducto($idLinea, $idLineaDestino)
    {
        $util = new util();
        $campos = array("ID_LINEA");
        $values = array($idLineaDestino);
        $result = $util->update('contratos_lineas_productos', $campos, $values, "id_linea=" . $idLinea);
    }

    //Generar un anexo de contrato
    public static function generarAnexo($idContrato, $idServicio, $tipoAnexo)
    {
        $util = new util();
        $t_contratos_anexos = array("ID_CONTRATO", "ID_SERVICIO", "ID_TIPO_TRAMITE");
        $values = array($idContrato, $idServicio, $tipoAnexo);
        $resAnexo = $util->insertInto('contratos_anexos', $t_contratos_anexos, $values);
    }

    public static function setProductoRMA($idContrato, $productos, $idLineaContrato, $fechaBaja = null)
    {
        for ($i = 0; $i < count($productos); $i++) {

            $idProducto = $productos[$i][0];
            $estado = $productos[$i][1];

            if ($estado == 'off')//Si el estado es off el producto es una baja
            {
                self::setProductoBaja($idProducto, 6, $fechaBaja);
            } else {
                self::setProductoBaja($idProducto, 5, $fechaBaja);
                Orden::crearOrdenTrabajo($idContrato, $idLineaContrato, $fechaBaja, 1, $idProducto);
                //Orden::cancelarOrdenTrabajo($idProducto,$idLineaContrato,4);
            }

        }


    }

    //Establece un producto en instalado.
    public static function setProductoInstalado($productos, $idLineaContrato)
    {
        for ($i = 0; $i < count($productos); $i++) {

            $idProducto = $productos[$i][0];
            $estado = $productos[$i][1];

            if ($estado == 'off')//Si el estado del producto se sigue solicitando la recogida no se elimina la orden de trabajo
            {
                self::setProductoAlta($idProducto, 4);
            } else {
                self::setProductoAlta($idProducto, 3);

                $lineaDetalle = self::getLineaDetalles($idLineaContrato);


                Orden::cancelarOrdenTrabajo($idProducto, $lineaDetalle, 6);
            }

        }


    }

    //Obtiene los contratos que tienen líneas de contrato que entran de baja a día de hoy
    /*
     * SELECT *
        FROM contratos,contratos_lineas
        WHERE contratos.ID=contratos_lineas.ID_CONTRATO
        AND contratos_lineas.FECHA_BAJA=DATE(now())
     */
    public static function getLineasContratoBajaHoy()
    {
        $util = new util();
        return $util->selectWhere3("contratos,contratos_lineas", array("ID_TIPO_SERVICIO", "ID_ATRIBUTO_SERVICIO", "VALOR"),
            "contratos.id=contratos_lineas.id_contrato AND contratos_lineas.fecha_baja=DATE(NOW())");

    }

    public static function setProductoAlta($idProducto, $estado)
    {
        $util = new util();

        $values = array($estado, null);

        $campos = array("ESTADO", "FECHA_BAJA");

        $result = $util->update('contratos_lineas_productos', $campos, $values, "id_producto=" . $idProducto);
        //ACTUALIZAMOS LA TABLA DE PRODUCTOS
        $campos = array("ESTADO");
        $values = array($estado);

        $result = $util->update('productos,almacenes', $campos, $values, "productos.id_almacen=almacenes.id AND productos.id=" . $idProducto . " AND almacenes.id_empresa=" . $_SESSION['REVENDEDOR']);
    }



    public static function setProductoBaja($idProducto, $estado, $fechaBaja = null)
    {
        $util = new util();
        if ($fechaBaja == null)
            $values = array($estado, date('Y-m-d'));
        else
            $values = array($estado, $fechaBaja);

        $campos = array("ESTADO", "FECHA_BAJA");

        $result = $util->update('contratos_lineas_productos', $campos, $values, "id_producto=" . $idProducto);
        //ACTUALIZAMOS LA TABLA DE PRODUCTOS
        $campos = array("ESTADO");
        $values = array($estado);

        $result = $util->update('productos,almacenes', $campos, $values, "productos.id_almacen=almacenes.id AND productos.id=" . $idProducto . " AND almacenes.id_empresa=" . $_SESSION['REVENDEDOR']);
    }

}