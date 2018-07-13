<?php

if (!isset($_SESSION)) {
    @session_start();
}

require_once('config/util.php');

ini_set('display_errors', 0);
error_reporting('E_ALL');
$util = new util();
check_session(2);

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
    <!--    light   box-->
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.7.12/release/featherlight.min.css" type="text/css"
          rel="stylesheet"/>
    <style>
        .progress {
            height: 35px;

        }

        .progress .skill {
            font: normal 12px "Open Sans Web";
            line-height: 35px;
            padding: 0;
            margin: 0 0 0 20px;
            text-transform: uppercase;
        }

        .progress .skill .val {
            float: right;
            font-style: normal;
            margin: 0 20px 0 0;
        }

        .progress-bar {
            text-align: left;
            transition-duration: 3s;
        }
    </style>
</head>

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
                <li><a href="#"><?php echo DEF_PROVISIONES; ?></a></li>
                <li class="active">Dar Altas</li>
            </ol>
        </header>
        <!-- /page title -->

        <?php
        if ($_SESSION['USER_LEVEL'] == 0) {
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
        } else {
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
        }

        ?>
        <div id="content" class="padding-20">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>SELECCIÓN DE CABECERA Y CLIENTE</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-md-6 col-xs-12">
                            <label>Seleccionar Cabecera</label>
                            <select id="cabecera" class="form-control" onchange="select_cabecera(this.value)">
                                <?php
                                $c = 0;

                                while ($row = mysqli_fetch_array($cabeceras)) {
                                    if ($c == 0) {
                                        $ultimo = $row;
                                        $c = 1;
                                    }
                                    if ($row['id'] == $_COOKIE['cabecera'])
                                        echo "<option value='" . $row['id'] . "' selected>" . $row['descripcion'] . "</option>";
                                    else
                                        echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>

                        <div class="col-lg-1 col-sm-6 col-md-6 col-xs-12"></div>

                        <div class="col-lg-5 col-sm-6 col-md-6 col-xs-12">
                            <label>Seleccionar Ont / Cliente</label>
                            <select id="cliente" class="form-control select2" onchange="cambiarcliente(this.value)">

                            </select>
                        </div>

                    </div>

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>SELECCION DEL SERVICIO</strong>
                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-3">
                            <br>
                            <a href="#panel_internet" id="internet_btn" class="btn btn-featured btn-success">
                                <span>Reaprovisionar Internet</span>
                                <i class="fa fa-internet-explorer"></i>
                            </a>
                            <br>
                        </div>
                        <div class="col-lg-3">
                            <br>
                            <a href="#panel_iptv" id="iptv_btn" class="btn btn-featured btn-info">
                                <span>Reaprovisionar IpTv</span>
                                <i class="fa fa-tv"></i>
                            </a>
                            <br>
                        </div>
                        <div class="col-lg-3">
                            <br>
                            <a href="#panel_voip" id="voip_btn" class="btn btn-featured btn-default">
                                <span>Reaprovisionar Voz Ip</span>
                                <i class="fa fa-phone"></i>
                            </a>
                            <br>
                        </div>
                        <div class="col-lg-3">
                            <br>
                            <a href="#panel_internet" id="vpn_btn" class="btn btn-featured btn-warning">
                                <span>Reaprovisionar Vpn</span>
                                <i class="fa fa-wifi"></i>
                            </a>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" id="panel_internet" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos Internet</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Modelo Ont</label>
                            <select name="ont-profile" id="ont-profile"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>


                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Subida</label>
                            <select name="velocidad_up" id="velocidad_up"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Bajada</label>
                            <select name="velocidad_dw" id="velocidad_dw"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Número PON ONT</label>
                            <input type="text" name="serial_pon" id="serial_pon" class="required form-control masked" data-format="****************" data-placeholder="_" placeholder="16 Caracteres (puede buscar auto o usar lector)">
                        </div>



                        <div class="col-lg-3 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Número de Serie ONT</label>
                            <label class="hidden-xs">Número de Serie ONT</label>
                            <input type="text" name="serial" id="serial" class="required form-control" placeholder="010118IPTV12123456">
                        </div>

                    </div>
                    <div class="row">
                        <br>
                        <div class="col-lg-1 col-xs-4">
                            <label>Chasis</label>
                            <select name="c" id="c" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-1 col-xs-4">
                            <label>Tarjeta</label>
                            <select name="t" id="t" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-1 col-xs-4">
                            <label>Pon</label>
                            <select name="p" id="p" class="form-control pointer required">

                            </select>
                        </div>

                        <div class="col-lg-2 col-xs-6">
                            <br class="visible-xs"><br class="visible-xs">
                            <label>Nº Caja</label>
                            <input type="text" name="caja" id="caja" placeholder="0.0"
                                   value="<?php echo $_COOKIE['caja']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <label>Puerto</label>
                            <input type="number" name="puerto" id="puerto" min="0" max="999"
                                   value="<?php echo $_COOKIE['puerto']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <br class="visible-xs"><br class="visible-xs">
                            <label>Fecha Alta</label>
                            <input type="text" name="fechalta" id="fechalta" placeholder="01/01/2000"
                                   value="" class="form-control pointer" disabled>
                        </div>

                        <div class="col-lg-2 col-xs-12 text-right">
                            <a href="#" id="activar-internet" style="width:100%;margin-top:25px" class="btn btn-danger">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-internet-explorer"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="panel panel-default" id="panel_iptv" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos Iptv</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                            <label>Modelo Ont</label>
                            <select name="ont" id="ont"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                                <?php $util->carga_select('modelos_ont', 'id', 'perfil', 'id'); ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-md-2 col-xs-12">
                            <label>Velocidad Gestionada Por</label>
                            <select name="gestionada" id="gestionada"
                                    class="form-control pointer required" onchange="gestionada(this.value);">
                                <option value="" selected>Selecciona una opción</option>
                                <option value="0">OLT</option>
                                <option value="1">ROUTER EXTERNO</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                            <label>Velocidad</label>
                            <select name="velocidad" id="velocidad"
                                    class="form-control pointer required" disabled="disabled" >
                                <option value="">Seleccionar una</option>
                                <?php $util->carga_select('perfil_internet', 'perfil_olt', 'nombre_perfil', 'id', 'nivel_usuario>=' . $_SESSION['USER_LEVEL']); ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                            <label>Tipo Ip</label>
                            <select name="tipoip" id="tipoip"
                                    class="form-control pointer required" disabled="disabled" >
                                <option value="0">Dinámica</option>
                                <option value="1">Fija</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                            <label>Asignación Ip</label>
                            <select name="asignaip" id="asignaip"
                                    class="form-control pointer required" disabled="disabled" >
                                <option value="0">DHCP</option>
                                <option value="1">PPPOE</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-3">
                            <a href="#" class="btn btn-danger" style="margin-top:25px">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-tv"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default" id="panel_voip" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos Voip</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Modelo Ont</label>
                            <select name="ont-profile" id="ont-profile"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Gestionada Por</label>
                            <select name="gestionada" id="gestionada"
                                    class="form-control pointer required" onchange="gestionada(this.value);">
                                <option value="0" selected>OLT</option>
                                <?php if($_COOKIE['asignada']==1)
                                    echo '<option value="1" selected>ROUTER EXTERNO</option>';
                                else
                                    echo '<option value="1">ROUTER EXTERNO</option>';
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Subida</label>
                            <select name="velocidad_up_voip" id="velocidad_up_voip"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Bajada</label>
                            <select name="velocidad_dw" id="velocidad_dw"
                                    class="form-control pointer required" >
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Tipo Ip</label>
                            <select name="tipoip" id="tipoip"
                                    class="form-control pointer required" disabled="disabled" >
                                <option value="0">Dinámica</option>
                                <option value="1">Fija</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Asignación Ip</label>
                            <select name="asignaip" id="asignaip"
                                    class="form-control pointer required" disabled="disabled" >
                                <option value="0">DHCP</option>
                                <option value="1">PPPOE</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-2 col-xs-12">
                            <label>Número PON ONT</label>
                            <input type="text" name="serial_pon" id="serial_pon" class="required form-control masked" data-format="****************" data-placeholder="_" placeholder="16 Caracteres (puede buscar auto o usar lector)">
                        </div>

                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center" id="btn-autofind">
                            <span class="btn btn-info" style="margin-top:25px; width:100%; z-index:400000">
                                <i class="fa fa-2x fa-search"></i>

                            </span>
                        </div>

                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center">
                            <span class="btn btn-success" id="btn-barcode2" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>
                        </div>


                        <div class="col-lg-2 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Número de Serie ONT</label>
                            <label class="hidden-xs">Número de Serie ONT</label>
                            <input type="text" name="serial" id="serial" class="required form-control" placeholder="010118IPTV12123456">
                        </div>

                        <div class="col-lg-1 col-xs-12 col-md-6 col-sm-6 text-center" id="btn-barcode2">
                            <span class="btn btn-success" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>
                        </div>
                        <div class="col-lg-1 col-xs-12"></div>

                        <div class="col-lg-4 col-xs-12" id="aqui_vpn">

                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <div class="col-lg-2 col-xs-4">
                            <label>Chasis</label>
                            <select name="c" id="c" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Tarjeta</label>
                            <select name="t" id="t" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Pon</label>
                            <select name="p" id="p" class="form-control pointer required">

                            </select>
                        </div>

                        <div class="col-lg-2 col-xs-6">
                            <br class="visible-xs"><br class="visible-xs">
                            <label>Nº Caja</label>
                            <input type="text" name="caja" id="caja" placeholder="0.0"
                                   value="<?php echo $_COOKIE['caja']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <label>Puerto</label>
                            <input type="number" name="puerto" id="puerto" min="0" max="999"
                                   value="<?php echo $_COOKIE['puerto']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-12 text-right">
                            <a href="#" id="activar-internet" style="width:100%;margin-top:25px" class="btn btn-danger">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-internet-explorer"></i>
                            </a>
                        </div>
                    </div>


                </div>

            </div>


        </div>
    </section>
</div>

<div class="modal fade modal-lg" id="encontradas" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="encontradas_content" style="padding:5%">
            <center>
                <img src="img/procesando.gif" id="img_procesando">
                <br><br>
                <h3>Esto puede tardar hasta un minuto</h3>
            </center>
        </div>
    </div>
</div>


<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="padding:5%">
            <div class="text-center">
                <h3 class="text-danger text-red">Por favor revisa los datos</h3>
                <img src="img/ont.png" class="img-responsive" id="img-provision">
                <br><br>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5" id="text_cliente"><b>Cliente:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_cliente"></div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Nº. PON Ont:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_pon"></div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Vel. Subida</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_up"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Vel. Bajada</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_dw"></div>
            </div>

            <div class="row">
                <br><br>
                <div class="col-lg-6 col-xs-12"></div>
                <div class="col-lg-3 col-xs-6 text-right">
                    <a href="#" id="activar-internet" data-dismiss="modal" style="margin-top:25px" class="btn btn-danger">
                        <span>Cancelar</span>

                    </a>
                </div>
                <div class="col-lg-3 col-xs-6 text-right">
                    <a href="#" id="activar-internet" onclick="enviar();" style="margin-top:25px" class="btn btn-success">
                        <span>Activar</span>
                    </a>
                </div>
            </div>
            <div id="trabajando" style="display:none">
                <span id="texto_trabajando">Realizando operaciones en los servidores, esto puede tardar.<br>Por
                favor espera.<br><br></span>
                <div class="progress skill-bar ">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="99" aria-valuemin="0"
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

    <!-- JAVASCRIPT FILES -->
    <script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
    <script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="assets/js/app.js"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.7.12/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>


    <script>
        var id_ini=67;
        var sp_ini=100;
        var encontradas=false;
        var servicio=0;

        function gestionada(value) {
            if (value == 1) {
                // $("#velocidad").attr("disabled", false);
                $("#tipoip").attr("disabled", false);
                $("#asignaip").attr("disabled", false);
            } else {
                // $("#velocidad").attr("disabled", true);
                $("#tipoip").attr("disabled", true);
                $("#asignaip").attr("disabled", true);
            }
        }

        $("#internet_btn").bind('click', function () {
            var clienteselected = $("#cliente").val();
            if(clienteselected==null){
                alert("Debes seleccionar un cliente");
                return;
            }

            $("#panel_internet").css('display', 'block');
            $("#panel_iptv").css('display', 'none');
            $("#panel_voip").css('display', 'none');


            servicio = 1;
            $("#img-provision").attr('src','img/ont.png');
            $("#aqui_vpn").empty();
            $("#activar-internet").empty();
            $("#activar-internet").append('<span>Revisar y Activar </span>\n' +
                '                                <i class="fa fa-internet-explorer"></i>');
        });

        $("#iptv_btn").bind('click', function () {
            $("#panel_internet").css('display', 'none');
            $("#panel_iptv").css('display', 'block');
            $("#panel_voip").css('display', 'none');
            servicio = 2;
        });

        $("#voip_btn").bind('click', function () {
            $("#panel_internet").css('display', 'none');
            $("#panel_iptv").css('display', 'none');
            $("#panel_voip").css('display', 'block');
            servicio = 3;
        });

        $("#vpn_btn").bind('click', function () {
            $("#panel_internet").css('display', 'block');
            $("#panel_iptv").css('display', 'none');
            $("#panel_voip").css('display', 'none');
            servicio = 4;

            $("#velocidad_dw option:last").attr("selected", "selected");
            $("#velocidad_up option:last").attr("selected", "selected");
            $("#aqui_vpn").empty();
            $("#img-provision").attr('src','img/antena.png');
            $("#aqui_vpn").append('<label class="visible-xs" style="margin-top:20px;">Descripción de la VPN</label>\n' +
                '                            <label class="hidden-xs">Descripción de la VPN</label>\n' +
                '                            <input type="text" name="desc_vpn" id="desc_vpn" class="required form-control" placeholder="Estación Wifi">');
            $("#activar-internet").empty();
            $("#activar-internet").append('<span>Revisar y Activar </span>\n' +
                '                                <i class="fa fa-wifi"></i>');

        });


        $("#activar-internet").bind('click', function () {

            var serie = $("#serial").val();
            var lprof = $("#ont-profile").val();
            var olt = $("#cabecera").val();
            var up = $("#velocidad_up option:selected").text();
            var dw = $("#velocidad_dw option:selected").text();
            var idcliente = $("#cliente").val();
            var caja = $("#caja").val();
            var c = $("#c").val();
            var t = $("#t").val();
            var p = $("#p").val();
            var puerto = $("#puerto").val();
            var npon = $("#serial_pon").val();



            $("#prev_serial").html(serie);
            $("#prev_pon").html(npon);
            $("#prev_mod").html(lprof);
            $("#prev_up").html(up);
            $("#prev_dw").html(dw);
            $("#prev_caja").html(caja);
            $("#prev_c").html(c);
            $("#prev_t").html(t);
            $("#prev_p").html(p);
            $("#prev_puerto").html(puerto);



            var mensaje = "";

            if (olt == '') {
                mensaje = mensaje + "Debes seleccionar una cabecera\n";
            }

            if(servicio==4) {
                $("#prev_cliente").html($("#desc_vpn").val());
                $("#text_cliente").html("<b>Descripcion de la Vpn:<b>");

                var desc_vpn = $("#desc_vpn").val();
                if (desc_vpn == '' || desc_vpn == null) {
                    mensaje = mensaje + "Debes teclear la descripción de la VPN\n";
                }
            } else {
                $("#prev_cliente").html($("#cliente option:selected").text());
                $("#text_cliente").html("<b>Cliente:<b>");
                if (idcliente == '' || idcliente == null) {
                    mensaje = mensaje + "Debes seleccionar un cliente\n";
                }
            }

            if (lprof == '' || lprof==null) {
                mensaje = mensaje + "Debes seleccionar el modelo de la ONT\n";
            }


            if (serie == '') {
                mensaje = mensaje + "Debes teclear el número de PON de la ONT\n";
            }
            if (npon == '') {
                mensaje = mensaje + "Debes teclear el número de serie de la ONT\n";
            }

            if (npon.length != 16) {
                mensaje = mensaje + "Número de serie de la ONT incorrecto\n";
            }

            if (up == '' || up==null) {
                mensaje = mensaje + "Debes seleccionar la velocidad de subida\n";
            }
            if (dw == '' || dw==null) {
                mensaje = mensaje + "Debes seleccionar la velocidad de bajada\n";
            }
            if (caja == '') {
                mensaje = mensaje + "Falta el número de caja\n";
            }
            if (c == '' || c==null) {
                mensaje = mensaje + "Falta el número de chasis\n";
            }
            if (t == '' || t==null) {
                mensaje = mensaje + "Falta el número de Tarjeta\n";
            }
            if (p == '' || p==null) {
                mensaje = mensaje + "Falta el número de pon\n";
            }
            if (puerto == '') {
                mensaje = mensaje + "Falta el número de puerto\n";
            }

            if (mensaje != '') {
                alert(mensaje);
                return;
            } else {
                $("#ajax").modal();
            }
            //enviar();

        });

        function cerrar_modal(){
            $("#ajax").hide(1000);
            $(".modal-backdrop").css("visible","none");
        }

        function enviar(){

            $("#msg_error").text('');

            var serie = $("#serial").val();
            var lprof = $("#ont-profile").val();
            var olt = $("#cabecera").val();
            var up = $("#velocidad_up").val();
            var dw = $("#velocidad_dw").val();
            var idcliente = $("#cliente").val();
            var caja = $("#caja").val();
            var c = $("#c").val();
            var t = $("#t").val();
            var p = $("#p").val();
            var puerto = $("#puerto").val();
            var npon = $("#serial_pon").val();

            $("#trabajando").css('display', 'block');
            $("#texto_trabajando").html('Realizando operaciones en los servidores, esto puede tardar.<br>Por favor espera.<br><br><br>');
            $.featherlight("#trabajando").defaults;

            $('.progress .progress-bar').css("width",
                function () {
                    return "0%";
                }
            );

            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "10%";
                    }
                );
            }, 1300);

            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "20%";
                    }
                );
            }, 2500);

            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "40%";
                    }
                );
            }, 5000);
            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "60%";
                    }
                );
            }, 10000);
            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "80%";
                    }
                );
            }, 13500);
            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "90%";
                    }
                );
            }, 16000);
            setTimeout(function () {
                $('.progress .progress-bar').css("width",
                    function () {
                        return "100%";
                    }
                );
            }, 20000);

            var comando = "alta";

            if(servicio==4) {
                var cliente = $("#desc_vpn").val();
            }else{
                var cliente = $("#nomcli").val() + "_" + $("#apecli").val() + "_" + $("#telcli").val();
            }

            console.log('enviando');
            $.ajax({
                url: 'telnet_.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    olt: olt,
                    command: comando,
                    serial: serie,
                    lineprofile: lprof,
                    serverprofile: lprof,
                    descrp: cliente,
                    servicio: '100',
                    up: up,
                    dw: dw,
                    caja: caja,
                    c: c,
                    t: t,
                    p: p,
                    lat: '',
                    lon: '',
                    puerto: puerto,
                    idcliente: idcliente,
                    id_ini: id_ini,
                    sp_ini: sp_ini,
                    num_pon: npon,
                    vpn: servicio
                },
                success: function (datos) {
                    // ----------------------------------------------------------------------------------------------------
                    $.featherlight.close();
                    $("#trabajando").css('display', 'none');
                    if(datos == 0) {
                        _toastr("Operación completada correctamente", "top-right", "success", false);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    } else {
                        _toastr(datos, "top-right", "warning", false);
                        $("#encontradas").empty();
                        var error_content="<center>";
                        error_content += "<img src='img/error.png'><br><br>";

                        if(datos==1) {
                            error_content += "<span style='font-size:2em;color:red'>ERROR: 301:</span><br>";
                            error_content += "<span style='font-size:1.4em;color:#000'>Ese numero PON se encuentra dado de alta</span><br><br>";
                            error_content+="<img src='img/solucion.png'><br>";
                            error_content += "<span style='font-size:1.4em;color:orangered'>Intenta darlo de baja o usar otra ONT</span><br><br>";
                        }
                        if(datos==2) {
                            error_content += "<span style='font-size:2em;color:red'>ERROR: 302:</span><br>";
                            error_content += "<span style='font-size:1.4em;color:#000'>Faltan datos de configuración en la cabecera</span><br><br>";
                            error_content+="<img src='img/solucion.png'><br>";
                            error_content += "<span style='font-size:1.4em;color:orangered'>Indica al administrador el error 302</span><br><br>";
                        }
                        if(datos==3) {
                            error_content += "<span style='font-size:2em;color:red'>ERROR: 303:</span><br>";
                            error_content += "<span style='font-size:1.4em;color:#000'>Alguna de las velocidades seleccionadas no están disponibles en esta cabecera</span><br><br>";
                            error_content+="<img src='img/solucion.png'><br>";
                            error_content += "<span style='font-size:1.4em;color:orangered'>Selecciona otra velocidad o solicita al administrador el alta de dicha velocidad</span><br><br>";
                        }


                        error_content+="</center>";
                        $("#encontradas").append(error_content);
                    }
                    //
                    // ----------------------------------------------------------------------------------------------------
                }
            });
            
        }
        function cambiarcliente(pon) {

            $.ajax({
                url: 'carga_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    pon: pon
                },
                success: function (datos) {
                    var valor=datos[x].velocidad_up;

                    $('#velocidad_up').val(valor);
                    // $('#velocidad_up option[value=valor]').attr('selected','selected');
                }
            });
        }

        function select_cabecera(id) {

            $('#velocidad_up').empty();
            $('#velocidad_dw').empty();
            var up = <?php echo isset($_COOKIE["up"]) ? $_COOKIE["up"] : 0; ?>;
            var dw = <?php echo isset($_COOKIE["dw"]) ? $_COOKIE["dw"] : 0; ?>;

            $.ajax({
                url: 'carga_perfiles.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    cabecera: id
                },
                success: function (data) {
                    for (var x = 0; x < data.length; x++) {
                        if(data[x].perfil_olt==up)
                            $('#velocidad_up')
                                .append($("<option selected></option>")
                                    .attr("value",data[x].perfil_olt)
                                    .text(data[x].nombre_perfil));
                        else
                            $('#velocidad_up')
                                .append($("<option></option>")
                                    .attr("value",data[x].perfil_olt)
                                    .text(data[x].nombre_perfil));

                        if(data[x].perfil_olt==dw)
                            $('#velocidad_dw')
                                .append($("<option selected></option>")
                                    .attr("value",data[x].perfil_olt)
                                    .text(data[x].nombre_perfil));
                        else
                            $('#velocidad_dw')
                                .append($("<option></option>")
                                    .attr("value",data[x].perfil_olt)
                                    .text(data[x].nombre_perfil));
                    }
                }
            });

            $.ajax({
                url: 'carga_perfiles_ont.php',
                type: 'POST',
                cache: false,
                cache: false,
                async: true,
                data: {
                    cabecera: id
                },
                success: function (data) {
                    $('#ont-profile').empty();
                    for (var x = 0; x < data.length; x++) {
                        $('#ont-profile')
                            .append($("<option></option>")
                                .attr("value",data[x].nombre_perfil)
                                .text(data[x].nombre_perfil));
                    }

                }
            });

            $.ajax({
                url: 'carga_datos_olt.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    cabecera: id
                },
                success: function (data) {
                    $('#c').empty();
                    for (var x = 0; x < data[0].c; x++) {
                        $('#c')
                            .append($("<option></option>")
                                .attr("value",x)
                                .text(x));
                    }
                    $('#t').empty();
                    for (var x = 0; x < data[0].t; x++) {
                        $('#t')
                            .append($("<option></option>")
                                .attr("value",x)
                                .text(x));
                    }
                    $('#p').empty();
                    for (var x = 0; x < data[0].p; x++) {
                        $('#p')
                            .append($("<option></option>")
                                .attr("value",x)
                                .text(x));
                    }

                    id_ini= data[0].id;
                    sp_ini= data[0].sp;
                }
            });


        }

        function cargar_clientes() {
            $('#cliente').empty();
            $('#cliente').append("<option value='0' selected disabled>Seleccione uno</option>");

            $.ajax({
                url: 'carga_cli.php',
                type: 'POST',
                cache: false,
                async: true,
                success: function (datos) {
                    if(datos.length>0) {
                        for (var x = 0; x < datos.length; x++) {
                            $('#cliente').append("<option value='"+datos[x].id+"'>"+ datos[x].apellidos +" " + datos[x].nombre +"</option>");
                        }
                    }
                }
            });
        }

        $(document).ready(function () {
            select_cabecera($("#cabecera").val());
            cargar_clientes();
        });

        function btnautofind(){
            $("#encontradas").modal();

            $('#encontradas_content').empty();
            $('#encontradas_content').append('<center><img src="img/procesando.gif" id="img_procesando"><br><br><h3>Esto puede tardar hasta un minuto</h3></center>');
            $('#encontradas_content')
                .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

            var olt = $("#cabecera").val();
            if (olt == '') {
                alert("Debes seleccionar una cabecera");
            } else {
                setTimeout(function(){no_se_encuentra_nada(); }, 60000);

                $.ajax({
                    url: 'autofind.php',
                    type: 'POST',
                    cache: false,
                    async: true,
                    data: {
                        olt: olt
                    },
                    success: function (datos) {


                        $('#encontradas_content').empty();

                        $('#encontradas_content').append($("<b><div class='row'>" +
                            "<div class='col-lg-2 col-xs-3'>C/T/P</div>" +
                            "<div class='col-lg-8 col-xs-6'>Número PON</div>" +
                            "<div class='col-lg-2 col-xs-3'></div></div></b>"
                        ));
                        if(datos.length>0) {
                            for (var x = 0; x < datos.length; x++) {
                                $('#encontradas_content')
                                    .append($("<div class='row'>" +
                                        "<div class='col-lg-2 col-xs-3'>" + datos[x].f[0] + "/" + datos[x].f[1] + "/" + datos[x].f[2] + "</div>" +
                                        "<div class='col-lg-8 col-xs-6'>" + datos[x].s + "</div>" +
                                        "<div class='col-lg-2 col-xs-3'><h2>" +
                                        "<button class='btn btn-info btn-xs' style='margin-top:-26px' data-dismiss='modal' onclick='asignar_este(\"" + datos[x].f[0].replace(/\s/g, '') + "\",\"" + datos[x].f[1].replace(/\s/g, '') + "\",\"" + datos[x].f[2].replace(/\s/g, '') + "\",\"" + datos[x].s.replace(/\s/g, '') + "\");'> <i class='fa fa-2x fa-copy'></i> </button>" +
                                        "</h2></div></div>"
                                    ));
                            }
                            encontradas=true;
                        }
                        else {
                            encontradas=true;
                            $('#encontradas_content').empty();
                            $('#encontradas_content')
                                .append($("<div class='row'>" +
                                    "<div class='col-lg-12 text-center'><span style='color:red;font-weight: 700'>No se ha encontrado ninguna ONT</span></div>" +
                                    "<div class='col-lg-12 text-center'>" +
                                    "<button class='btn btn-warning' style='margin-top:30px' data-dismiss='modal' onclick='btnautofind();'> <i class='fa fa-2x fa-refresh'></i>Reintentar </button>" +
                                    "</div></div>"
                                ));
                        }
                        $('#encontradas_content')
                            .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

                    }
                });
            }
        }

        $("#btn-autofind").bind('click', function () {
            btnautofind();
        });

        $("#btn-barcode2").bind('click', function () {
            btnautofind();
        });

        function no_se_encuentra_nada(){
            if(encontradas==true)
                return;

            $('#encontradas_content').empty();
            $('#encontradas_content')
                .append('<center><span style="font-size:1.2em; color:red">Tiempo de espera agotado y no se ha encontrado una ONT</span></center>');

            $('#encontradas_content')
                .append($("<div class='row'>" +
                    "<div class='col-lg-12 text-center'>" +
                    "<button class='btn btn-warning' style='margin-top:30px' data-dismiss='modal' onclick='btnautofind();'> <i class='fa fa-2x fa-refresh'></i>Reintentar </button>" +
                    "</div></div>"
                ));

            $('#encontradas_content')
                .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

        }

        function asignar_este(c,t,p,serial){
            $("#serial_pon").val('');


            $("#serial_pon").val(serial);

            $("#c").val(parseInt(c)).change();
            $("#t").val(parseInt(t)).change();
            $("#p").val(parseInt(p)).change();
            $("#encontradas").hide(1000);
        }
    </script>

</body>
</html>