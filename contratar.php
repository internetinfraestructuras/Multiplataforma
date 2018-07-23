<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ PERMITE REALIZAR CONTRATOS DE TODOS LOS SERVICIOS ║
    ║ El procedimiento es seleccionar un cliente e ir   ║
    ║ Agregando servicios. Despues se generan las ordenes
    ║ de trabajos
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(2);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Contratación de servicios</title>
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
    <script type="text/javascript" src="js/utiles.js"></script>

    <style>


        /*form styles*/
        #msform {
            text-align: center;
            position: relative;
            margin-top: 10px;
        }

        #msform fieldset {
            background: white;
            border: 1px solid #c9c9c9;
            border-radius: 2px;
            box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
            padding: 5px 5px;
            box-sizing: border-box;
            width: 96%;
            margin: 0 2%;

            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*inputs*/
        #msform input, #msform textarea {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 3px;
            margin-bottom: 7px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            font-size: 13px;
        }

        #msform input:focus, #msform textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 2px solid #1D9FC1;
            outline-width: 0;
            transition: All 0.5s ease-in;
            -webkit-transition: All 0.5s ease-in;
            -moz-transition: All 0.5s ease-in;
            -o-transition: All 0.5s ease-in;
        }

        /*buttons*/
        #msform .action-button {
            width: auto;
            min-width: 120px;
            background: #1D9FC1;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px 5px;
            /*margin: 10px 5px;*/
            bottom:10px;
            position: absolute;
            right:10px;
        }

        #msform .action-button:hover, #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #1D9FC1;
        }

        #msform .action-button-previous {
            width: auto;
            min-width: 120px;
            background: #bec3c9;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px 5px;
            /*margin: 10px 5px;*/
            bottom:10px;
            position: absolute;
            left:10px;

        }

        #msform .action-button-previous:hover, #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;
        }

        /*headings*/
        .fs-title {
            font-size: 18px;
            text-transform: uppercase;
            color: #2C3E50;
            margin-bottom: 10px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .fs-subtitle {
            font-weight: normal;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
        }

        #progressbar li {
            list-style-type: none;
            color: #0F0F5E;
            text-transform: uppercase;
            font-size: 9px;
            width: 20%;
            float: left;
            position: relative;
            letter-spacing: 1px;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 24px;
            height: 24px;
            line-height: 26px;
            display: block;
            font-size: 12px;
            color: #333;
            background: white;
            border-radius: 25px;
            margin: 0 auto 10px auto;
        }

        /*progressbar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: white;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1; /*put it behind the numbers*/
        }

        #progressbar li:first-child:after {
            /*connector not needed before the first step*/
            content: none;
        }

        /*marking active/completed steps green*/
        /*The number of the step and the connector before it = green*/
        #progressbar li.active:before, #progressbar li.active:after {
            background: #0f0f88;
            color: white;
        }

        #progressbar li.completed:before, #progressbar li.completed:after {
            background: #1D9FC1;
            color: white;
        }

        /* Not relevant to this form */
        .dme_link {
            margin-top: 30px;
            text-align: center;
        }

        .dme_link a {
            background: #000;
            font-weight: bold;
            color: #ee0979;
            border: 0 none;
            border-radius: 25px;
            cursor: pointer;
            padding: 5px 25px;
            font-size: 12px;
        }

        .dme_link a:hover, .dme_link a:focus {
            background: #C5C5F1;
            text-decoration: none;
        }

        .ocultar {
            display: none;
        }
        .caja{
            min-height:490px;

        }

        form .row{
            margin-top:5px;
            margin-bottom:5px;
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

        <?php require_once('menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once('menu-superior.php'); ?>

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
                <li><a href="#">Ventas</a></li>
                <li class="active">Alta contrato</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="">

            <form id="msform" class="validate">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Cliente</li>
                    <li>Servicios</li>
                    <li>Promociones</li>
                    <li>Facturación</li>
                    <li>Finalizar</li>
                </ul>
                <!-- fieldsets -->
                    <fieldset  class="caja">

                        <div class="row">
                            <div class="col-md-8 col-sm-9">
                                <label>Seleccione un cliente o cree uno nuevo</label>
                                <select id="id" name="id" onchange="seleccionado(this.value)"
                                        class="form-control select2">
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-3 text-right">

                                <span class="btn btn-primary" style="margin-top:25px;padding-top:9px;" onclick="nuevo_cliente();">Crear uno nuevo</span>

                                <br><br>
                            </div>
                        </div>

                        <div class="row ocultar">
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" value="" id="nombre"
                                           class="form-control required datoscli">
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <label>Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidos"
                                           class="form-control datoscli">
                                </div>
                                <div class="col-md-2 col-sm-3">
                                    <label>Dni</label>
                                    <input type="text" name="dni" id="dni"
                                           class="form-control datoscli " placeholder="99999999A">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label>Dirección</label>
                                    <input type="text" name="direccion" id="direccion"
                                           class="form-control datoscli ">
                                </div>
                            </div>
                        </div>

                        <div class="row ocultar">
                            <div class="form-group">
                                <div class="col-md-3 col-sm-4">
                                    <label>Región</label>
                                    <select name="region" id="regiones"
                                            class="form-control pointer " onchange="carga_provincias(this.value)">
                                        <option value="-1">--- Seleccionar una ---</option>
                                        <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad'); ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <label>Provincia </label>
                                    <select name="provincia" id="provincias"
                                            class="form-control pointer " onchange="carga_poblaciones(this.value)">
                                        <option value="-1">--- Seleccionar una ---</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <label>Localidad </label>
                                    <select name="localidad" id="localidades"
                                            class="form-control pointer ">
                                        <option value="-1">--- Seleccionar una ---</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2">
                                    <label>CP </label>
                                    <input type="number" min="0" max="99999" name="cp" id="cp"
                                           class="form-control datoscli required" >
                                </div>
                            </div>
                        </div>

                        <div class="row ocultar">
                            <div class="form-group">
                                <div class="col-md-4 col-sm-6">
                                    <label>Email </label>
                                    <input type="email" name="email" id="email"
                                           class="form-control datoscli required">
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <label>Tel Fijo</label>
                                    <input type="tel" name="tel1" id="tel1"
                                           class="form-control datoscli required">
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <label>Tel Móvil</label>
                                    <input type="tel" name="tel2" id="tel2" class="form-control datoscli">
                                </div>
                            </div>
                        </div>
                        <div class="row ocultar">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Notas </label>
                                    <input type="text" name="notas" id="notas"
                                           class="form-control datoscli required">
                                </div>
                            </div>
                        </div>

                        <div id="error"></div>
                        <input type="button" name="" id="atras1" onclick="cancelar();" class="ocultar action-button-previous"
                               value="Paso Anterior"/>
                        <input type="button" name="next" id="next1" class="next action-button" value="Continuar"/>

                    </fieldset>
                    <fieldset class="caja">
                        <label>Seleccione el paquete que desea contratar</label>
                        <select name="paquete" id="paquete"
                                class="form-control pointer " onchange="paquete_seleccionado(this.value)">
                            <option value="">--- Seleccionar uno ---</option>
                            <?php $util->carga_select('paquetes', 'id', 'NOMBRE, PVP', 'NOMBRE', '', 2,array('',' €')); ?>
                        </select>

                        <!-- aqui se cargarán los servicios que contiene el paquete seleccionado-->
                        <div id="aqui_los_servicios"></div>

                        <input type="button" name="previous" class="previous action-button-previous"
                               value="Paso Anterior"/>
                        <input type="button" name="next" class="next action-button" value="Continuar"/>
                    </fieldset >
                    <fieldset class="caja">
                        <input type="button" name="previous" class="previous action-button-previous"
                               value="Paso Anterior"/>
                        <input type="button" name="next" class="next action-button" value="Continuar"/>
                    </fieldset>
                    <fieldset class="caja">
                        <input type="button" name="previous" class="previous action-button-previous"
                               value="Paso Anterior"/>
                        <input type="button" name="next" class="next action-button" value="Continuar"/>
                    </fieldset>

                    <fieldset class="caja">
                        <input type="button" name="previous" class="previous action-button-previous"
                               value="Paso Anterior"/>
                        <input type="button" name="next" class="next action-button" value="Finalizar y Contratar"/>
                    </fieldset>
            </form>
        </div>
</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>


<script>
    var nuevo=0;
    var id_cliente_seleccionado=0;
    var id_paquete_seleccionado=0;

    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_provincias(id, sel = 0) {
        var select = $("#provincias");
        select.empty();
        select.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_prov.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id)
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].provincia + '</option>');
                    else
                        select.append('<option value="' + data[i].id + '">' + data[i].provincia + '</option>');

                });
            }
        });
    }

    // cargo las localidades por Ajax cada vez que se cambia de provincia
    function carga_poblaciones(id, sel = 0) {
        var select = $("#localidades");
        select.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_pobla.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id)
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].municipio + '</option>');
                    else
                        select.append('<option value="' + data[i].id + '">' + data[i].municipio + '</option>');
                });
            }
        });
    }

    // cargo los clientes para que pueda seleccionarse y editarlo
    function carga_clientes() {
        var select = $("#id");
        select.empty();
        select.append('<option value="-1">--- Seleccionar uno ---</option>');
        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            async: true,
            success: function (data) {
                $.each(data, function (i) {

                    select.append('<option value="' + data[i].id + '">' + data[i].apellidos + " " + data[i].nombre + '</option>');
                });
            }
        });
    }


    // cuando se pulsa el boton de cliente nuevo, muestro los campos, pongo nuevo a 1
    function nuevo_cliente() {
        nuevo=1;
        $(".ocultar").css('display','block');
        $("#atras1").css('display','block');
        $("#next1").css('display','block');
        $("#atras1").css('position','absolute');
        $("#atras1").val('Cancelar');
        $("#next1").val('Guardar Cliente');
        $("#id").val(0);
        $(".datoscli").val('');
        $('[name=id]').val( '' );
        $('[name=region]').val( '' );
        $('[name=provincia]').val( '' );
        $('[name=localidad]').val( '' );
    }

    // cuando se pulsa el boton cancelar despues de haber pulsado el de nuevo, oculto los campos y pongo nuevo a 0
    function cancelar(){
        nuevo=0;
        $(".ocultar").css('display','none');
        $("#atras1").css('display','none');
        $("#atras1").css('position','absolute');
        $("#atras1").val('Cancelar');
        $("#next1").val('Continuar');
    }


    $(document).ready(function () {
        carga_clientes(false);
        $("#atras1").css('display','none');
        $("#next1").css('display','none');
    });


    // cuando se selecciona un cliente, recibo el id y lo cargo por ajax desde carga_cli que al pasarle una id
    // solo devuelve ese registro

    function seleccionado(id) {

        nuevo=0;

        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            cache: false,
            async: true,
            data: {
                idcliente: id
            },
            success: function (data) {
                $("#regiones").val(parseInt(data[0].region)).change();
                $("#provincias").empty();

                carga_provincias(parseInt(data[0].region), parseInt(data[0].provincia));
                $("#dni").val(data[0].dni);
                $("#nombre").val(data[0].nombre);
                $("#apellidos").val(data[0].apellidos);
                $("#direccion").val(data[0].direccion);
                $("#cp").val(data[0].cp);

                carga_poblaciones(parseInt(data[0].provincia), parseInt(data[0].municipio));


                $("#tel1").val(data[0].tel1);
                $("#tel2").val(data[0].tel2);
                $("#email").val(data[0].email);
                $("#notas").val(data[0].notas);
                $("#fechalta").val(data[0].alta);
                // $("#fechalta").attr('disabled','disabled');

                $("#hash").val(md5(id));

                $(".ocultar").css('display','block');
            }
        });

        id_cliente_seleccionado = id;
        $("#next1").css('display','block');

    }

    function paquete_seleccionado(id) {

        $("#aqui_los_servicios").empty();
        $("#aqui_los_servicios").append('<div class="row">');
        $("#aqui_los_servicios").append('<div class="table-responsive">' +
            '<table class="table table-condensed nomargin">' +
            '<thead>' +
            '<tr>' +

            '<th></th>' +
            '</tr>' +
            '</thead><tbody id="aqui_la_tabla"></tbody>');



        $.ajax({
            url: 'content/servicios/carga_paquetes.php',
            type: 'POST',
            cache: false,
            cache: false,
            async: true,
            data: {
                idpaquete: id
            },
            success: function (datos) {

                $.each(datos, function(i) {
                    $("#aqui_la_tabla").append('<tr><td>'+datos[i].idservicio+'</td><td>'+datos[i].tipo+'</td><td>'+
                        datos[i].comercial+'</td><td>'+ datos[i].coste+'</td><td>'+datos[i].impuesto+'</td><td>'+
                        datos[i].dad_up+ 'Mb / '+
                        datos[i].velocidad_dw + 'Mb</td><td>'+datos[i].ppoe_usuario+'</td><td>'+datos[i].caja + ' / '+
                        datos[i].puerto+'</td><td>'+datos[i].c+ '/' +datos[i].t+ '/'+datos[i].p+
                        '</td><td>' +
                        '<span class="fa fa-eye" style="font-size:1em; cursor: pointer;margin-left:.5em" onclick="ver(\''+datos[i].num_pon+'\',\''+datos[i].serial+'\');"></span>' +
                        '</td></tr>');
                });

                $(".ocultar").css('display','block');
            }
        });

        id_paquete_seleccionado = id;
        $("#next2").css('display','block');

    }


    /*
        -------------------------------------------------
        Efectos y Control del formulario step by step
        -------------------------------------------------
     */
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function () {

        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        var avanzar=true;

        if (this.id=='next1' && nuevo==0){
            if(parseInt($("#id")[0].selectedIndex)==-1){
                alert("Debe seleccionar un cliente o crear uno nuevo");
                avanzar=false;
                return;
            } else if ( nuevo==1){
                $("#next1").css('display','none');

                var nom = $("#nombre").val();
                var ape = $("#apellidos").val();
                var dni = $("#dni").val();
                var dir = $("#direccion").val();
                var reg = $("#regiones").val();
                var pro = $("#provincias").val();
                var loc = $("#localidades").val();
                var cp = $("#cp").val();
                var mail = $("#email").val();
                var tl1 = $("#tel1").val();
                var tl2 = $("#tel2").val();

                if(nom==''){
                    alert('Debe teclear el nombre del cliente');
                    $("#nombre").focus();
                    return;
                }
                if(ape==''){
                    alert('Debe teclear los apellidos del cliente');
                    $("#apellidos").focus();
                    return;
                }
                if(dni==''){
                    alert('Debe teclear el DNI del cliente');
                    $("#dni").focus();
                    return;
                }
                if(dir==''){
                    alert('Debe teclear la dirección del cliente');
                    $("#direccion").focus();
                    return;
                }
                if(reg==-1){
                    alert('Debe seleccionar la región del cliente');
                    $("#regiones").focus();
                    return;
                }
                if(pro==-1){
                    alert('Debe seleccionar la provincia del cliente');
                    $("#provincias").focus();
                    return;
                }
                if(loc==-1){
                    alert('Debe seleccionar la localidad del cliente');
                    $("#localidades").focus();
                    return;
                }
                if(cp==''){
                    alert('Debe teclear el código postal del cliente');
                    $("#cp").focus();
                    return;
                }
                if(mail=='' || !validateEmail(mail)){
                    alert('Debe teclear el email válido');
                    $("#email").focus();
                    return;
                }
                if(tl1=='' && tl2==''){
                    alert('Debe teclear al menos un número de teléfono');
                    $("#tel1").focus();
                    return;
                }

                if(guardar_cliente()) {
                    avanzar=true;
                }

            }
        }

            if(avanzar){
                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                $("#progressbar li").eq($("fieldset").index(next_fs) - 1).addClass("completed");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function (now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale current_fs down to 80%
                        scale = 1 - (1 - now) * 0.2;
                        //2. bring next_fs from the right(50%)
                        left = (now * 50) + "%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        next_fs.css({'left': left, 'opacity': opacity});
                    },
                    duration: 800,
                    complete: function () {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            }
    });


    function guardar_cliente(){

        var nom = $("#nombre").val();
        var ape = $("#apellidos").val();
        var dni = $("#dni").val();
        var dir = $("#direccion").val();
        var reg = $("#regiones").val();
        var pro = $("#provincias").val();
        var loc = $("#localidades").val();
        var cp = $("#cp").val();
        var mail = $("#email").val();
        var tl1 = $("#tel1").val();
        var tl2 = $("#tel2").val();
        var notas = $("#notas").val();

        var clientes = {nombre: nom,apellidos:ape,dni:dni,dir:dir,cp:cp,region:reg,provincia:pro,
                        localidad:loc,email:mail,tel1:tl1,tel2:tl2,notas:notas,alta:Date.now()};

        var ok=false;
        $.ajax({
            url: 'php/guardar-cli.php',
            type: 'POST',
            cache: false,
            async: false,
            data: {
                action: 'clientes',
                clientes:clientes,
                is_ajax: true
            },
            success: function (data) {
                if(parseInt(data)>0) {
                    ok = true;
                    id_cliente_seleccionado=data;
                }
                else {
                    alert("ERROR: Posible datos duplicados, revise los clientes actuales.");
                    $("#next1").css('display', 'block');
                    }
                }
        });
        return ok;
    }

    $(".previous").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    // $(".submit").click(function () {
    //     return false;
    // });

    function validateEmail(email)
    {
        var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
        return re.test(email);
    }

</script>


</body>
</html>