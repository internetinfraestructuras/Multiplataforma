<?php
// todo: *********************************************************************************
// interfaz que muestra un listado de las altas realizadas y permite borrarlas
// se pueden eliminar solo de la base de datos o de la cabecera y base de datos
// todo: *********************************************************************************


if (!isset($_SESSION)) {
    @session_start();
}

require_once('../../config/util.php');
$util = new util();
check_session(2);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Productos / Internet</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

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

    <!-- JQGRID TABLE -->
    <link href="../../assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css"/>

    <style>
        .hover:hover {
            background-color: rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }
    </style>
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

        <?php require_once('../../menu-superior.php'); ?>

    </header>
    <?php

    // creo un objeto de la clase util para poder realizar operaciones de consultas a base de datos
    $util = new util();

    // si el usuario es root cargará siempre los datos de todos
    if ($_SESSION['USER_LEVEL'] == 0) {
        $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
    } else {    // si no es root cargara solo los datos de este usuario y todos los que pertenezcan al mismo revendedor
        $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
    }

    ?>
    <section id="middle">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#">Productos</a></li>
                <li class="active">Internet</li>
            </ol>
        </header>
        <!-- /page title -->


        <div class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
                                    <span class="title elipsis">
                                        <strong>LISTAR, AGREGAR Y EDITAR PAQUETES DE INTERNET</strong>
                                        <!-- panel title -->
                                    </span>
                                </div>

                                <!-- panel content -->
                                <div class="panel-body">
                                    <div class="row" style="">

                                        <div class="col-lg-5 fancy-form">
                                            <div class="col-lg-12">
                                                <label>Nombre comercial del paquete</label><br>
                                                <input type="text" name="olt_vel_descr" id="olt_vel_descr"
                                                       class="form-control" placeholder="Descripción">

                                                <span class="fancy-tooltip top-left">
                                        <em>Nombre del paquete que se mostrará en los listados</em>
                                    </span>
                                            </div>

                                            <div class="col-lg-6">
                                                <br>
                                                <label>Bytes de subida</label><br>
                                                <input type="number" min="0" name="bytes_dw" id="bytes_dw"
                                                       class="form-control"
                                                       placeholder="20000">
                                                <span class="fancy-tooltip top-left">
                                        <em>Bytes de bajada</em>
                                    </span>
                                            </div>
                                            <div class="col-lg-6">
                                                <br>

                                                <label>Bytes de Bajada</label><br>
                                                <input type="number" min="0" name="bytes_up" id="bytes_up"
                                                       class="form-control"
                                                       placeholder="10000">
                                                <span class="fancy-tooltip top-left">
                                        <em>Bytes de subida</em>
                                    </span>
                                            </div>
                                            <div class="col-lg-4">
                                                <br>
                                                <label>Precio Coste</label><br>
                                                <input type="number" min="0" name="coste" id="coste"
                                                       class="form-control"
                                                       placeholder="10">
                                                <span class="fancy-tooltip top-left">
                                        <em>Teclee el precio de coste del producto</em>
                                            </div>
                                            <div class="col-lg-4">
                                                <br>
                                                <label>Precio Venta</label><br>
                                                <input type="number" min="0" name="pvp" id="pvp"
                                                       class="form-control"
                                                       placeholder="10">
                                                <span class="fancy-tooltip top-left">
                                        <em>Teclee el precio de venta del producto</em>
                                            </div>
                                            <div class="col-lg-4">
                                                <br>
                                                <label>Impuesto</label>
                                                <select name="iva" id="iva"
                                                        class="form-control pointer ">
                                                    <?php $util->carga_select('impuestos', 'id', 'importe', 'importe','','','',' %'); ?>
                                                </select>
                                            </div>
                                            <br><br>
                                            <a href="#" id="btn_add_velocidad" class="btn btn-3d btn-teal"
                                               style="width:100%;margin-top:22px"><i
                                                        class="fa fa-save"></i>Agregar</a>

                                        </div>
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-6">
                                            <label>Listado de paquetes</label><br>

                                            <select id="velocidades" multiple="multiple" style="height:218px"
                                                    class="form-control">

                                            </select>
                                            <span class="btn btn-3d btn-red"
                                                  style="width: 98%;margin-left:5px; margin-top:15px;margin-bottom:15px"
                                                  onclick="borrar2();"><i class="fa fa-trash"></i>Borrar</span>
                                        </div>
                                    </div>

                                    <br> <br>



                                </div>
                                <!-- /panel content -->

                                <!-- /panel footer -->

                            </div>


                        </div>

                    </div>
                    <!-- /----- -->

                </div>


            </div>

        </div>
    </section>
    <!-- /MIDDLE -->

</div>


<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>
<script type="text/javascript" src="../../js/utiles.js"></script>


<!-- PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    var short = "";
    var modo = "";

    $(document).ready(function () {
        cargar_altas(getParameterByName('s'), getParameterByName('o'));
        trabajando();
    });

    jQuery(function ($) {
        var input = $('#filtro');

        // en cada pulsacion de tecla si el numero de caracteres supera a 3 cargo la informacion filtrada por el texto tecleado
        input.on('keydown', function () {
            var key = event.keyCode || event.charCode;
            var id = $("#cabecera").val();

            if (key == 8 || key == 46)
                cargar_altas(id, '');

            if ($('#filtro').val().length > 2) {
                var filtro = input.val();
                cargar_altas(short, modo, id, filtro);
            }
        });
    });

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }


</script>


</body>
</html>