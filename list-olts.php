<?php

if (!isset($_SESSION)) {
    @session_start();
}

/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que muestra el listado de cabeceras      ║
    ╚════════════════════════════════════════════════════════════╝
*/

require_once('config/util.php');
require_once('php/snmp/snmp.php');

ini_set('display_errors', 0);
error_reporting('E_ALL');
$util = new util();
check_session(0);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_CLIENTES; ?> /Listados</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

    <!-- JQGRID TABLE -->
    <link href="assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css"/>

</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once('php/header-menu.php'); ?>

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
                <li><a href="#"><?php echo MNU_ITEM_6; ?></a></li>
                <li class="active">Listar</li>
            </ol>
        </header>
        <!-- /page title -->

        <div id="content" class="padding-20">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Listado de olts disponibles por </strong>
                </div>

                <div class="panel-body">
                    <div class="row">


<!--                        Perfiles de velocidad-->
                        <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                            <center>
                                <label><strong>Seleccionar Cabecera</strong></label>
                            </center>
                            <a href="add-olts.php" id="" class="btn btn-3d btn-teal" style="margin-top:100px;width:100%;margin-bottom:15px"><i class="fa fa-pencil"></i> Nueva </a>

                            <select id="cabecera" multiple="multiple" style="height:260px" class="form-control" onchange="select_cabecera(this.value)">
                                    <?php
                                    // si el usuario es root se muestran todas las cabeceras
                                    if ($_SESSION['USER_LEVEL'] == 0) {
                                        $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
                                    } else { // si no se muestran solo las del revendedor activo
                                        $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
                                    }

                                    $c = 0;

                                    while ($row = mysqli_fetch_array($cabeceras)) {
                                        if ($c == 0) {
                                            $ultimo = $row;
                                            $c = 1;
                                        }
                                        echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                    }
                                    ?>
                                </select>
                            <center>
                                <span class="btn btn-3d btn-red" style="width:100%; margin-top:15px;margin-bottom:15px" onclick="borrar1();"><i class="fa fa-trash"></i>Borrar</span>
                            </center>
                        </div>
                        <div class="col-lg-1"></div>

                        <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                            <center>
                                <label><strong>Perfil de velocidad</strong></label>
                            </center>
                            <div class="row" style="padding-left:20px;">
                                <div class="col-lg-6 fancy-form" style="padding:0px;padding-right:5px;">
                                    <i class="fa fa-server"></i>
                                    <input type="text" name="olt_velocidad" id="olt_velocidad" class="form-control" placeholder="Perfil OLT">
                                    <span class="fancy-tooltip top-left">
                                        <em>Nombre Perfil en OLT</em>
                                    </span>
                                </div>
                                <div class="col-lg-6 fancy-form"  style="padding:0px;padding-left:5px;">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="olt_vel_descr" id="olt_vel_descr" class="form-control" placeholder="Descripción">
                                    <span class="fancy-tooltip top-left">
                                        <em>Texto Visible al usuario</em>
                                    </span>
                                </div>
                                <div class="col-lg-6 fancy-form" style="padding:0px;padding-right:5px;">
                                    <i class="fa fa-download"></i>
                                    <input type="text" name="bytes_dw" id="bytes_dw" class="form-control" placeholder="Bytes Bajada">
                                    <span class="fancy-tooltip top-left">
                                        <em>Número entero bytes de bajada</em>
                                    </span>
                                </div>
                                <div class="col-lg-6 fancy-form"  style="padding:0px;padding-left:5px;">
                                    <i class="fa fa-upload"></i>
                                    <input type="text" name="bytes_up" id="bytes_up" class="form-control" placeholder="Bytes Subida">
                                    <span class="fancy-tooltip top-left">
                                        <em>Número entero bytes de subida</em>
                                    </span>
                                </div>

                                <a href="#" id="btn_add_velocidad" class="btn btn-3d btn-teal" style="width:100%; margin-top:15px;margin-bottom:15px"><i class="fa fa-save"></i>Agregar</a>

                                <select id="velocidades" multiple="multiple" style="height:260px" class="form-control">

                                </select>
                            </div>

                            <center>
                                <span class="btn btn-3d btn-red" style="width:105%;margin-left:5px; margin-top:15px;margin-bottom:15px" onclick="borrar2();"><i class="fa fa-trash"></i>Borrar</span>
                            </center>
                        </div>
                        <div class="col-lg-1"></div>

<!--                        Perfiles ONT-->
                        <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                            <center>
                                <label><strong>Perfil de ONT´s</strong></label>
                            </center>
                            <div class="row" style="padding-left:20px;">
                                <div class="col-lg-12 fancy-form"  style="padding:0px;padding-left:5px;margin-top:44px;">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="perfil_ont" id="perfil_ont" class="form-control" placeholder="Descripción">
                                    <span class="fancy-tooltip top-left">
                                        <em>Nombre del perfil del modelo de ONT</em>
                                    </span>
                                </div>

                                <a href="#" id="btn_add_ont" class="btn btn-3d btn-teal" style="width:100%; margin-top:15px;margin-bottom:15px"><i class="fa fa-save"></i>Agregar</a>

                                <select id="modelos_ont" multiple="multiple" style="height:260px" class="form-control">

                                </select>
                            </div>
                            <center>
                                <span class="btn btn-3d btn-red" style="width:105%;margin-left:5px; margin-top:15px;margin-bottom:15px" onclick="borrar3();"><i class="fa fa-trash"></i>Borrar</span>
                            </center>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
</section>
<!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>

<script>
    function borrar1(){
        var c = $("#cabecera :selected").val();
    }

    function borrar2(){
        var c = $("#cabecera :selected").val();
        var p = $("#velocidades :selected").val();
        if(p !='' && p !=null)
        {
            // llama a la clase telnetcmd con el parametro command:deletespeedprofile, olt= id de la cabecera, id del perfil a borrar
            $.ajax({
                url: 'telnetcmd.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    olt: c,
                    perfil: p,
                    command: 'deletespeedprofile'
                },
                success: function (data) {
                    if(data!=1)
                        alert(data);
                    select_cabecera(c);
                }
            });
        }
    }

    function borrar3(){
        var c = $("#cabecera :selected").val();
        var p = $("#modelos_ont :selected").val();
        if(p !='' && p !=null)
        {
            $.ajax({
                url: 'telnetcmd.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    olt: c,
                    perfil: p,
                    command: 'deleteontprofile'
                },
                success: function (data) {
                    select_cabecera(c);
                }
            });
        }
    }

    function gestionada(value){
        if(value==1){
            $("#velocidad").attr("disabled", false);
            $("#tipoip").attr("disabled", false);
            $("#asignaip").attr("disabled", false);
        } else {
            $("#velocidad").attr("disabled", true);
            $("#tipoip").attr("disabled", true);
            $("#asignaip").attr("disabled", true);
        }
    }

    $("#btn_add_velocidad").bind('click', function () {
        var a = $("#olt_velocidad").val();
        var b = $("#olt_vel_descr").val();
        var up = $("#bytes_up").val();
        var dw = $("#bytes_dw").val();
        var c = $("#cabecera :selected").val();
        if(c=='' || c==null){
            alert("Debes seleccionar una cabecera");
            return
        }

        if(a=='' || b==''){
            alert("Debes completar los dos campos");
            return
        }

        $.ajax({
            url: 'php/guardar-perfil.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                a: a,
                b: b,
                c: c,
                up:up,
                dw:dw,
                action:'velocidades'
            },
            success: function (data) {
                var c = $("#cabecera :selected").val();
                select_cabecera(c);
            }
        });
    });

    $("#btn_add_ont").bind('click', function () {
        var a = $("#perfil_ont").val();
        var b = $("#cabecera :selected").val();

        if(b=='' || b==null){
            alert("Debes seleccionar una cabecera");
            return
        }

        if(a==''){
            alert("Debes completar el nombre del perfil");
            return
        }

        $.ajax({
            url: 'php/guardar-perfil.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                a: a,
                b: b,
                action:'ont'
            },
            success: function (data) {
                var c = $("#cabecera :selected").val();
                select_cabecera(c);
                $("#perfil_ont").val('');
            }
        });
    });

    function select_cabecera(id) {
        $.ajax({
            url: 'carga_perfiles.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $('#velocidades').empty();
                for (var x = 0; x < data.length; x++) {
                    $('#velocidades')
                        .append($("<option></option>")
                            .attr("value",data[x].perfil_olt)
                            .text('OLT: ' + data[x].perfil_olt + ' / Descripción: ' +data[x].nombre_perfil));

                }
            }
        });

        $.ajax({
            url: 'carga_perfiles_ont.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $('#modelos_ont').empty();
                for (var x = 0; x < data.length; x++) {
                    $('#modelos_ont')
                        .append($("<option></option>")
                            .attr("value",data[x].nombre_perfil)
                            .text(data[x].nombre_perfil));
                }

            }
        });

    }

    $(document).ready(function () {
    });

</script>
</body>
</html>