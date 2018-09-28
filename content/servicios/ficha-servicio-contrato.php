<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar la ficha de servicios ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(2);

if(!isset($_GET['idContrato']))
{
    $listado= $util->selectWhere3('servicios,servicios_tipos',
        array("servicios.id","servicios.nombre","servicios.pvp","servicios.precio_proveedor","servicios.impuesto","servicios.beneficio","servicios.id_servicio_tipo"),
        "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND servicios.id=".$_GET['idServicio']);
}
else
{
    $listado= $util->selectWhere3('servicios,servicios_tipos,contratos,contratos_lineas',
        array("servicios.id","servicios.nombre","contratos_lineas.pvp","contratos_lineas.precio_proveedor","contratos_lineas.impuesto","contratos_lineas.beneficio","servicios.id_servicio_tipo","contratos_lineas.permanencia","contratos_lineas.estado"),
        "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND contratos.id=".$_GET['idContrato']."
                                                      AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
                                                      AND contratos.id=contratos_lineas.id_contrato AND servicios.id=".$_GET['idServicio']." AND contratos_lineas.id=".$_GET['idLineaContrato']);
}

$id=$listado[0][0];
$nombre=$listado[0][1];
$pvp=$listado[0][2];
$coste=$listado[0][3];
$impuesto=$listado[0][4];
$beneficio=$listado[0][5];
$idTipoServicio=$listado[0][6];
$permanencia=$listado[0][7];
$idEstado=$listado[0][8];

$readonly="";

$actual = date ("Y-m-d");




?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_CLIENTES; ?> / Altas</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>
    <script type="text/javascript" src="js/utiles.js"></script>

</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('../../menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once ('../../menu-superior.php'); ?>

    </header>
    <!-- /HEADER -->


    <!--
        MIDDLE
    -->
    <section id="middle">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_SERVICIOS; ?></a></li>
                <li class="active">Ficha de Servicios de cliente</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_SERVICIOS)."--->".$estado; ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-servicio.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">

                                                <label>ID</label>
                                                <?php

                                                if(isset($_GET['idContrato']))
                                                {
                                                    echo '<input type="hidden" name="idContrato" value='.$_GET['idContrato'].' id="id" class="form-control">';
                                                    echo '<input type="hidden" name="idLinea" value='.$_GET['idLineaContrato'].' id="id" class="form-control">';
                                                }

                                                ?>
                                                <input type="text" name="id" value="<?php echo $id;?>"  class="form-control disabled" readonly>

                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Tipo de Servicio:</label>
                                                <select name="tipo" id="tipo" readonly
                                                        class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                    <option value="<?php echo $idTipoServicio;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios_tipos', 'id', 'nombre', 'nombre','','','',$idTipoServicio); ?>
                                                </select>
                                            </div>

                                            <?php
                                            if(isset($_GET['idContrato']))
                                            {?>

                                            <div class="col-md-4 col-sm-5">
                                                <label>Nombre:</label>
                                                <select name="servicio" id="servicio"
                                                        class="form-control pointer " name="nombre"  onchange="carga_datos_servicio(this.value)">
                                                    <option>--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios', 'id', 'nombre', 'nombre','servicios.id_servicio_tipo='.$idTipoServicio,'','',$id); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-5">
                                                <label>Permanencia:</label>
                                                <input type="date" name="permanencia" value="<?php echo $permanencia;?>"  class="form-control disabled" readonly>

                                            </div>
                                        </div>
                                        <?php
                                        }
                                        else
                                        {?>
                                            <div class="col-md-7 col-sm-5">
                                                <label>Nombre:</label>
                                                <input type="text" name="nombre" id="servicio" value="<?php echo $nombre; ?>"   class="form-control" >
                                            </div>


                                        <?php }
                                        ?>
                                    </div>



                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-4">
                                                <label>Precio Proveedor</label>
                                                <input type="text" name="coste" id="coste"
                                                       class="form-control " placeholder="0.00"  value=<?php echo $coste; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Margen</label>
                                                <input type="text" name="beneficio" id="beneficio"
                                                "  class="form-control  placeholder="0" value=<?php echo $beneficio; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>IMPUESTOS</label>
                                                <input type="text" name="impuesto" id="impuestos"
                                                       class="form-control " placeholder="0" value=<?php echo $impuesto; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>PVP</label>
                                                <input type="text" name="pvp" id="pvp"
                                                       class="form-control " placeholder="0" value=<?php echo $pvp; ?>>
                                            </div>
                                        </div>
                                    </div>

                        </div>


                        </fieldset>

                        <hr/>
                        <div class="panel-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ATRIBUTO</th>
                                    <th>VALOR</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($_GET['idContrato']))
                                {
                                    $atributos= $util->selectWhere3('servicios_atributos,servicios_tipos_atributos',
                                        array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                        "servicios_atributos.id_servicio=".$_GET['idServicio']." and servicios_atributos.id_tipo_atributo=servicios_tipos_atributos.id");
                                }
                                else
                                {

                                    $atributos= $util->selectWhere3('contratos_lineas,contratos_lineas_detalles,servicios_tipos_atributos',
                                        array("servicios_tipos_atributos.id,servicios_tipos_atributos.nombre,contratos_lineas_detalles.valor"),
                                        "servicios_tipos_atributos.id=contratos_lineas_detalles.id_atributo_servicio AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']
                                        ." AND contratos_lineas.id=contratos_lineas_detalles.id_linea");
                                }


                                for($i=0;$i<count($atributos);$i++)
                                {

                                    $id=$atributos[$i][0];
                                    $attr=$atributos[$i][1];

                                    if(isset($_GET['idContrato']))
                                        $valor=$atributos[$i][2];
                                    else
                                        $valor=$atributos[$i][3];




                                    echo "<tr>";

                                    echo "<tr>";
                                    echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$attr' class='form-control' disabled />
                                            </td><td><input name='atributo[valor][]' value='$valor' class='form-control' id='atributo-$id'/></td>";

                                    ?>

                                    </tr>



                                    </tr>

                                    <?php
                                }
                                ?>

                                </tbody>

                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-4">
                                <?php
                                if(!isset($_GET['idContrato'])) {
                                    echo '<input type="checkbox" name="cascada-precio"  placeholder="0" > ¿Realizar una actualización de precios a todos los clientes con dicho servicio contratado?   El precio no incluye cambios en los precios de los paquetes.';
                                    //   echo '<input type="checkbox" name="cascada-tecnico"  placeholder="0" > ¿Realizar una actualización de la velocidad para todos los clientes?Si no se selecciona se respetará la velocidad a todos los clientes con este servicio contratado.';
                                }
                                ?>

                            </div>
                        </div>
                        <?php
                        if($idEstado==1)//SI EL ESTADO DEL CONTRATO ES DE ALTA MOSTRAMOS EL CONTENIDO DE BAJA
                        {?>


                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit"
                                        class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30" >
                                    VALIDAR Y GUARDAR
                                    <span class="block font-lato">verifique que toda la información es correcta</span>
                                </button>
                            </div>
                        </div>
                        <?php  } ?>

                        </form>

                    </div>

                </div>
                <!-- /----- -->
                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Productos asociados al servicio</h4>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID PRODUCTO</th>
                                    <th>NÚMERO DE SERIE</th>
                                    <th>ESTADO</th>
                                    <th>OPCIONES</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($_GET['idContrato']))
                                {
                                    $atributos= $util->selectWhere3('contratos_lineas_productos,productos,contratos',
                                        array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                        "contratos_lineas_productos.id_producto=productos.id AND contratos.id=contratos_lineas.id_contrato AND contratos_lineas.id=".$_GET['idLineaContrato']);
                                }
                                else
                                {
                                    $atributos= $util->selectWhere3('contratos,contratos_lineas,contratos_lineas_detalles,contratos_lineas_productos,productos,productos_estados',
                                        array("productos.id,productos.numero_serie,productos_estados.nombre"),
                                        "contratos.id=contratos_lineas.id_contrato 
                                            AND productos.estado=productos_estados.id
                                                AND contratos_lineas.id=contratos_lineas_detalles.id_linea
                                                AND contratos_lineas_detalles.id=contratos_lineas_productos.id_linea
                                                AND contratos_lineas_productos.id_producto=productos.id 
                                                AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']." 
                                                AND contratos_lineas_detalles.id_tipo_servicio=".$_GET['tipo']);
                                }


                                for($i=0;$i<count($atributos);$i++)
                                {

                                    $id=$atributos[$i][0];
                                    $ssid=$atributos[$i][1];
                                    $estado=$atributos[$i][2];
                                    if(isset($_GET['idContrato']))
                                        $valor=$atributos[$i][2];
                                    else
                                        $valor=$atributos[$i][3];




                                    echo "<tr>";

                                    echo "<tr>";
                                    echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$ssid' class='form-control' disabled  />
                                            <td><input  value='$estado' class='form-control' disabled />
                                            </td>";

                                    ?>
                                    <td>
                                        <a href="../almacen/ficha-producto.php?idProducto=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </a>
                                        <a onclick="abrirModal()">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-recycle"></i>
                                            </button>
                                        </a>
                                        <a href="../almacen/ficha-producto.php?idProducto=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </a>
                                    </td>
                                    </tr>



                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>

                        </div>
                    </div>



                    <?php

                    if($idEstado==1)//SI EL ESTADO DEL CONTRATO ES DE ALTA MOSTRAMOS EL CONTENIDO DE BAJA
                    {?>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" onclick="bajaServicio()"
                                        class="btn btn-3d btn-red btn-xlg btn-block margin-top-30">
                                    SOLICITAR BAJA DE SERVICIO
                                </button>
                            </div>
                        </div>
                        <?php
                    }

                    if($idEstado==4 || $idEstado==7)
                    {?>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" onclick="bajaServicio()"
                                        class="btn btn-3d btn-yellow btn-xlg btn-block margin-top-30">
                                    CANCELAR SOLICITUD DE BAJA
                                </button>
                            </div>
                        </div>
                        <?php
                    }


                    ?>
                </div>

                <div class="col-md-4" id="row-mod-estado" style="display: none;">

                    <div class="panel panel-default">
                        <div class="panel-body"  >

                            <h4>Seleccione los productos asociados: </h4>

                            <table id="example2"  class="table tabla-productos table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID PRODUCTO</th>
                                    <th>NÚMERO DE SERIE</th>
                                    <th>ESTADO</th>
                                    <th>OPCIONES</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($_GET['idContrato']))
                                {
                                    $atributos= $util->selectWhere3('contratos_lineas_productos,productos,contratos',
                                        array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                        "contratos_lineas_productos.id_producto=productos.id AND contratos.id=contratos_lineas.id_contrato AND contratos_lineas.id=".$_GET['idLineaContrato']);
                                }
                                else
                                {
                                    $atributos= $util->selectWhere3('contratos,contratos_lineas,contratos_lineas_detalles,contratos_lineas_productos,productos,productos_estados',
                                        array("productos.id,productos.numero_serie,productos_estados.nombre"),
                                        "contratos.id=contratos_lineas.id_contrato 
                                            AND productos.estado=productos_estados.id
                                                AND contratos_lineas.id=contratos_lineas_detalles.id_linea
                                                AND contratos_lineas_detalles.id=contratos_lineas_productos.id_linea
                                                AND contratos_lineas_productos.id_producto=productos.id 
                                                AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']." 
                                                AND contratos_lineas_detalles.id_tipo_servicio=".$_GET['tipo']);
                                }


                                for($i=0;$i<count($atributos);$i++)
                                {

                                    $id=$atributos[$i][0];
                                    $ssid=$atributos[$i][1];
                                    $estado=$atributos[$i][2];
                                    if(isset($_GET['idContrato']))
                                        $valor=$atributos[$i][2];
                                    else
                                        $valor=$atributos[$i][3];

                                    echo "<tr>";

                                    echo "<tr>";
                                    echo "<td><input type='checkbox' value='$id' name='productos[]' class='check-productos'></td></td><td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$ssid' class='form-control' disabled />
                                            <td><input  value='$estado' class='form-control' disabled />
                                            </td>";

                                    ?>
                                    <td>
                                        <a href="../almacen/ficha-producto.php?idProducto=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </a>


                                    </td>
                                    </tr>



                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>
                            <?php
                            if($idEstado==1 || $idEstado==7)
                            {?>
                                <label>Seleccione los dispositivos que se tienen que retirar al cliente.</label>
                                <label>¿Qué día será efectiva la baja?: </label>
                                <input type="date" name="fecha-baja" id="fecha-baja" value="<?php echo date('Y-m-d'); ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" onclick="borrar(<?php echo $id; ?>)"
                                                class="btn btn-3d btn-red btn-xlg btn-block margin-top-30">
                                            BAJA DE SERVICIO
                                        </button>
                                    </div>
                                </div>
                            <?php }
                            if($idEstado==4 || $idEstado==7)
                            {?>
                                <label>Seleccione los productos que no se quieren retirar</label>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" onclick="cancelarBaja(<?php echo $id; ?>)"
                                                class="btn btn-3d btn-yellow btn-xlg btn-block margin-top-30">
                                            CANCELAR BAJA
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>



                </div>

            </div>


    </section>
    <!----------------------------------------------------------------------
------------------------------------------------------------------------
-------------------------- VENTANA MODAL -------------------------------
------------------------------------------------------------------------
----------------------------------------------------------------------->
    <div class="modal fade" id="modal" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="padding:5%">
                <div class="text-center">
                    <h3 class="text-aqua ">Cambio de producto asociado</h3>
                    <i class="fas fa-recycle"></i>
                    <img class="img-responsive" id="img-provision2">
                    <br><br>
                </div>
                <?php
                $prodcs=Producto::getProductosMovil($_SESSION['REVENDEDOR']);
                if($prodcs!=NULL)
                {


                    ?>
                    <form action="guardar-cambio-producto.php" method="post">
                    <div class="row">
                        <div class="col-lg-4 col-xs-5" id="text_cliente"><b>Nueva Tarjeta:</b></div>
                        <select name="servicio" id="servicio"
                                class="form-control pointer " name="nombre"  onchange="carga_detalles_servicio(this.value)">
                            <?php


                            for($i=0;$i<count($prodcs);$i++)
                            {
                                $id=$prodcs[$i][0];
                                $nombre=$prodcs[$i][1];
                                echo "<option value='$id'>$nombre</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-xs-5"><b>Motivo cambio:</b></div>
                        <select name="servicio" id="servicio"
                                class="form-control pointer " name="nombre"  onchange="carga_detalles_servicio(this.value)">
                            <option value="PER">Perdida de tarjeta</option>
                            <option value="ROT">Rotura o deterioro</option>
                            <option value="ROB">Robo</option>
                            <option value="R4G">Remplazo tarjeta a 4G</option>
                            <option value="OTH">Otros motivos</option>
                        </select>

                    </div>
                    <?php
                }
                else
                    echo "No se puede hacer el cambio de SIM a este terminal, porque no hay tarjetas SIM en Stock";
                ?>


                <div class="row">
                    <br><br>
                    <div class="col-lg-6 col-xs-12"></div>
                    <div class="col-lg-3 col-xs-6 text-right">
                        <a href="#" id="" data-dismiss="modal" style="margin-top:25px" class="btn btn-danger">
                            <span>Cancelar</span>

                        </a>
                    </div>
                    <?php
                    if($prodcs!=NULL) {
                        ?>
                        <div class="col-lg-3 col-xs-6 text-right">
                            <input type="submit" href="#" id="btn-enviar" onclick="enviar();" style="margin-top:25px" class="btn btn-success">
                                <span>Activar</span>
                            </input>
                        </div>
                        <?php
                    }
                    ?>
                    </form>
                </div>
                <div id="trabajando" style="display:none">
                <span id="texto_trabajando">Realizando operaciones en los servidores, esto puede tardar.<br>Por
                favor espera.<br><br></span>
                    <div class="progress skill-bar ">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="99"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            <span class="skill">Progreso: <i class="val"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <br>
                    <center>
                        <span style="font-size:1em; color:red" id="msg_error"></span>
                    </center>
                </div>
            </div>
        </div>

    </div>
    <!-- /MIDDLE -->

</div>


<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>

<script type="text/javascript" src="../../assets/js/app.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
<script>
    function abrirModal()
    {
        $("#modal").modal();
    }
    function enviar()
    {
        alert("WOO");
    }
    $(function () {
        $('#example1').DataTable()
        $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar : ",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        })
    });
</script>

<script>
    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_datos_servicio(id)
    {


        jQuery.ajax({
            url: 'carga_servicios.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {

                id=data[0].idservicio;
                tipo=data[0].id_tipo;
                coste=data[0].coste;
                impuesto=data[0].impuesto;
                beneficio=data[0].beneficio;
                pvp=data[0].pvp;
                tipo=data[0].tipo;

                $("#coste").val(coste);
                $("#margen").val(beneficio);
                $("#impuestos").val(impuesto);
                $("#pvp").val(pvp);



            },
            error:function (xhr,error)
            {
                console.log(error);
            }
        });

        jQuery.ajax({
            url: 'cargar_atributos_servicios.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {

                $.each(data, function(i)
                {
                    var id=data[i].id_tipo_atributo;

                    $('#atributo-'+id).val(data[i].valor);


                });
            }
        });
    }

    function cargarProductos()
    {

        var array=new Array(new Array());

        $(".check-productos").each(function(i)
        {

            var valor=$(this).prop('checked');
            array[i][0]=$(this).val();

            if (valor===true)
                array[i][1]='on';
            else
                array[i][1]='off';


        });

        return array;
    }
    function bajaServicio()
    {
        $("#row-mod-estado").css("display","initial");
    }
    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Estas seguro de dar de baja del servicio?");


        if(respuesta)
        {

            var productos=cargarProductos();
            console.log(productos);


            var fecha=$("#fecha-baja").val();

            jQuery.ajax({
                url: 'baja-servicio.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'baja_servicio',
                    id:id,
                    idContrato:<?php echo $_GET['idContrato']; ?>,
                    idLinea:<?php echo $_GET['idLineaContrato']; ?>,
                    idServicio:<?php echo $_GET['idServicio']; ?>,
                    fecha:fecha,
                    productos:productos
                },
                success: function (data) {

                    console.log(data);
                    //  location.reload();
                }
            });
        }
    }

    function cancelarBaja(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Estas seguro de cancelar la baja del servicio?");


        if(respuesta)
        {
            var productos=cargarProductos();


            var productos=cargarProductos();
            console.log(productos);


            var fecha=$("#fecha-baja").val();

            jQuery.ajax({
                url: 'cancelar-baja.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'cancelar-baja',
                    id:id,
                    idContrato:<?php echo $_GET['idContrato']; ?>,
                    idLinea:<?php echo $_GET['idLineaContrato']; ?>,
                    idServicio:<?php echo $_GET['idServicio']; ?>,
                    productos:productos
                },
                success: function (data) {

                    console.log(data);
                    //  location.reload();
                }
            });

        }
    }
    function confirmar(text){

        return confirm(text);

    }




</script>



</body>
</html>