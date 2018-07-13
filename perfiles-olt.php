<?php

if (!isset($_SESSION)) {
    @session_start();
}

/*
    ╔══════════════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite modificar los datos de las cabeceras  ║
    ║ Como los perfiles de velocidad y modelos de ont            ║
    ║ Tambien permite modificar los datos tecnicos               ║
    ║ como propietario, ip, usuario, etc                         ║
    ╚══════════════════════════════════════════════════════════════════════╝
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
    <title><?php echo OWNER; ?> - Cabeceras / Gesión</title>
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
                <li class="active">Gestionar</li>
            </ol>
        </header>
        <!-- /page title -->

        <div id="content" class="padding-20">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Administración de perfiles en OLT</strong>
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
                                    // carga el listado de cabeceras en el select
                                    $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
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

                        <div class="col-lg-4 col-sm-4 col-md-3 col-xs-12">
                            <center>
                                <label><strong>Perfil de velocidad</strong></label>
                            </center>
                            <div class="row" style="padding-left:20px;">
                                <div class="col-lg-6 fancy-form" style="padding:0px;padding-right:5px;">
                                    <i class="fa fa-server"></i>
                                    <input type="text" name="olt_velocidad" id="olt_velocidad" class="form-control" placeholder="Perfil OLT">
                                    <span class="fancy-tooltip top-left"><em>Nombre Perfil en OLT</em></span>
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
                    <br><br>
                    <div class="row ">
                            <div class="col-lg-12 col-xs-12">
                                <form class="validate" action="php/guardar-olts.php" method="post"
                                          enctype="multipart/form-data" id="formulario" >
                                        <fieldset>
                                            <!-- required [php action request] -->
                                            <input type="hidden" name="oper" value="edit"/>
                                            <input type="hidden" name="id" id="a" />

                                            <div class="row">
                                                <center><b>Datos Cabecera</b></center>
                                                <div class="form-group">
                                                    <div class="col-md-2 col-sm-4">
                                                        <?php
                                                        // carga los revendedores en el combo para poder cambiar el propietario de la cabecera
                                                        $campos=array('revendedores.id','empresa','municipios.municipio');
                                                        $result = $util->selectJoin("revendedores", $campos,"JOIN municipios ON municipios.id = revendedores.localidad ","empresa");
                                                        ?>
                                                        <label><?php echo DEF_REVENDEDOR; ?> </label>
                                                        <select id="h" name="revendedor" class="form-control required">
                                                            <option value="0" selected>Administrador</option>

                                                            <?php
                                                            $c = 0;
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                if ($c == 0) {
                                                                    $ultimo = $row;
                                                                    $c = 1;
                                                                }
                                                                echo "<option value='". $row['id']."'>" . $row['empresa'] . " / " . $row['municipio'] . "</option>";
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Ip *</label>
                                                        <input type="text" name="ip" id="d" class="form-control required" placeholder="10.200.50.2">
                                                    </div>

                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Descripcion *</label>
                                                        <input type="text" name="descripcion" id="g" value=""
                                                               class="form-control required" placeholder="Puerto Serrano">
                                                    </div>

                                                    <div class="col-md-3 col-sm-4">
                                                        <div class="col-md-6 col-sm-4">
                                                            <label>Marca </label>
                                                            <input type="text" name="marca" id="b" value=""
                                                                   class="form-control " placeholder="Huawei">
                                                        </div>
                                                        <div class="col-md-6 col-sm-4">
                                                            <label>Modelo </label>
                                                            <input type="text" name="modelo" id="c" value=""
                                                                   class="form-control " placeholder="MA5608T">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-4">
                                                        <div class="col-md-6 col-sm-4">
                                                            <label>Usuario *</label>
                                                            <input type="text" name="user" id="e" value="" placeholder=""
                                                                   class="form-control required">
                                                        </div>
                                                        <div class="col-md-6 col-sm-4">
                                                            <label>Clave *</label>
                                                            <input type="text" name="pass" id="f" value="" placeholder=""
                                                                   class="form-control required">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <center><b>Datos Físicos</b></center>
                                                <div class="form-group">
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Nº. Chasis *</label>
                                                        <input type="number" name="c" id="i" value="" placeholder=""
                                                               class="form-control required">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Nº. Tarjetas *</label>
                                                        <input type="number" name="t" id="j" value="" placeholder=""
                                                               class="form-control required">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Nº. Pons *</label>
                                                        <input type="number" name="p" id="k" value="" placeholder=""
                                                               class="form-control required">
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 text-center">
                                                        <b>Services port (indica el numero por el que comenzar)</b><br>
                                                        <div class="col-md-3 col-sm-4">
                                                            <input type="number" name="idini" id="vl100" value="" placeholder="vlan 100"
                                                                   class="form-control ">
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                            <input type="number" name="serviceport" id="vl200" value="" placeholder="vlan 200"
                                                                   class="form-control ">
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                            <input type="number" name="serviceport" id="vl300" value="" placeholder="vlan 300"
                                                                   class="form-control ">
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                            <input type="number" name="serviceport" id="vl500" value="" placeholder="vlan 500"
                                                                   class="form-control ">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <center><b>Datos CCR / Router</b></center>
                                                <div class="form-group">
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>IP*</label>
                                                        <input type="text" name="ipccr" id="n" value=""
                                                               class="form-control required" placeholder="10.200.50.1">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Usuario Api*</label>
                                                        <input type="text" name="userapi" id="o" value=""
                                                               class="form-control required" placeholder="usuarioapi">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Clave usuario*</label>
                                                        <input type="text" name="claveapi" id="p" value=""
                                                               class="form-control required" placeholder="claveapi">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Usuario ONT</label>
                                                        <input type="text" name="useront" id="q" value=""
                                                               class="form-control required" placeholder="Acceso web ont">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Clave ONT*</label>
                                                        <input type="text" name="passont" id="r" value=""
                                                               class="form-control required" placeholder="Acceso web ont">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>SSID</label>
                                                        <input type="text" name="ssid" id="s" value=""
                                                               class="form-control required" placeholder="Mi Wifi Fibra">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Vlan ACS</label>
                                                        <input type="text" name="vlanacs" id="t" value=""
                                                               class="form-control " placeholder="200">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Lan IP</label>
                                                        <input type="text" name="lanip" id="x" value=""
                                                               class="form-control " placeholder="192.168.100.1">
                                                    </div>

                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Dhcp Start</label>
                                                        <input type="text" name="dhcpini" id="u" value=""
                                                               class="form-control " placeholder="192.168.100.2">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Dhcp End</label>
                                                        <input type="text" name="dhcpfin" id="v" value=""
                                                               class="form-control " placeholder="192.168.100.250">
                                                    </div>
                                                    <div class="col-md-2 col-sm-4">
                                                        <label>Máscara</label>
                                                        <input type="text" name="mascara" id="w" value=""
                                                               class="form-control " placeholder="255.255.255.0">
                                                    </div>

                                                </div>
                                            </div>


                                        </fieldset>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" onclick="checkpass()"
                                                        class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                                    ACTUALIZAR LOS DATOS DE LA CABECERA
                                                    <span class="block font-lato">Esta acción modifica los datos de la cabecera</span>
                                                </button>
                                            </div>
                                        </div>

                                    </form>
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
    // cuando se pulsa eliminar una cabecera
    function borrar1(){
        var c = $("#cabecera :selected").val();
        var resp = confirm("¿Realmente desea borrar esa cabecera y todos los registros asociados?");
        if(c !='' && c !=null && resp)
        {
            trabajando(1);
            $.ajax({
                url: 'telnetcmd.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    olt: c,
                    command: 'deleteolt'
                },
                success: function (data) {
                    trabajando(0);
                    location.reload();
                }
            });
        }
    }

    // cuando se pulsa el boton de guardar los nuevos datos tecnicos de la cabecera
    function checkpass(){
       var resp = confirm("¿Estas seguro de querer modificar estos datos?");

        if(resp) {
            trabajando();
            trabajando(1);
            $("#formulario").submit();

            setTimeout(function () {
                location.reload();
            }, 5000);

        }
        else
            _toastr("Cancelado", "top-right","error",false);

    }
    // cuando se pulsa el boton borrar alguna velocidad
    function borrar2(){
        var c = $("#cabecera :selected").val();
        var p = $("#velocidades :selected").val();
        if(p !='' && p !=null)
        {
            trabajando(1);
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
                    trabajando(0);
                }
            });
        }
    }

    // cuando se pulsa el boton de borrar algun perfil de ont
    function borrar3(){
        var c = $("#cabecera :selected").val();
        var p = $("#modelos_ont :selected").val();
        if(p !='' && p !=null)
        {
            trabajando(1);
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
                    trabajando(0);
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

    // cuando se pulsa el boton de agregar una velocidad

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
        // lanza la modal con el gif animado girando
        trabajando(1);

        // envia los datos de velocidad a guardar-perfil.php
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
                trabajando(0);
            }
        });
    });

    // cuando se pulsa el boton de agregar un modelo de ont
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
        trabajando(1);
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
                trabajando(0);
            }
        });
    });

    // cada vez que se selecciona una cabecera nueva,
    // se cargan los datos de velocidades, modelos de ont y los datos tecnicos
    function select_cabecera(id) {

        $.ajax({
            url: 'carga_info_olt.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $("#a").val(data[0].a);
                $("#b").val(data[0].b);
                $("#c").val(data[0].c);
                $("#d").val(data[0].d);
                $("#e").val(data[0].e);
                $("#f").val(data[0].f);
                $("#g").val(data[0].g);
                $("#h").val(data[0].h);
                $("#i").val(data[0].i);
                $("#j").val(data[0].j);
                $("#k").val(data[0].k);
                $("#l").val(data[0].l);
                $("#m").val(data[0].m);
                $("#n").val(data[0].n);
                $("#o").val(data[0].o);
                $("#p").val(data[0].p);
                $("#q").val(data[0].q);
                $("#r").val(data[0].r);
                $("#s").val(data[0].s);
                $("#t").val(data[0].t);
                $("#u").val(data[0].u);
                $("#v").val(data[0].v);
                $("#w").val(data[0].w);
                $("#x").val(data[0].x);

            }
        });

        // se cargan los perfiles de velocidad obtenidos por ajax desde el php en el <select>
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
        // se cargan los modelos de ont obtenidos por ajax desde el php en el <select>
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
        trabajando();
    });

</script>
</body>
</html>