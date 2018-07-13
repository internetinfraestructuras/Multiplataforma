<?php

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
    <title><?php echo OWNER; ?> - <?php echo DEF_PROVISIONES; ?> / Bajas</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <!-- JQGRID TABLE -->
    <link href="assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

    <style>
        .hover:hover{
            background-color: rgba(0,0,0,0.05);
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

        <?php require_once('menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once ('menu-superior.php'); ?>

    </header>
    <!-- /HEADER -->


    <!--
        MIDDLE
    -->
    <section id="middle">

        <?php
            $_POST['id_provision']=$_GET['id'];
            include "carga_provision.php";
        ?>

        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#">Clientes</a></li>
                <li class="active">Servicios Contratados</li>
            </ol>
        </header>
        <!-- /page title -->


        <div class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default" id="content" >

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>Gestionar servicios del cliente</strong> <!-- panel title -->
							</span>

                                    <!-- right options -->
                                    <ul class="options pull-right list-inline">
                                        <li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="Colapse" data-placement="bottom"></a></li>
                                        <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="Fullscreen" data-placement="bottom"><i class="fa fa-expand"></i></a></li>
                                        <li><a href="#" class="opt panel_close" data-confirm-title="Confirm" data-confirm-message="¿Deseas eleminar este panel?" data-toggle="tooltip" title="Close" data-placement="bottom"><i class="fa fa-times"></i></a></li>
                                    </ul>
                                    <!-- /right options -->

                                </div>

                                <!-- panel content -->
                                <div class="panel-body">
                                    <div class="row">
                                        <div class='col-xs-5'><b>Cliente:</b></div><div class='col-xs-7'><?php echo $aItems['cli_nombre'] . " " . $aItems['cli_apellidos']; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='col-xs-5'><b>Dirección:</b></div><div class='col-xs-7'><?php echo $aItems[5]; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='col-xs-5'><b>Teléfono:</b></div><div class='col-xs-7'><?php echo $aItems[6]; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='col-xs-5'><b>Fecha de alta:</b></div><div class='col-xs-7'><?php echo $aItems[6]; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='col-xs-5'><b>PON:</b></div><div class='col-xs-7'><?php echo $aItems[6]; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='col-xs-5'><b>C/T/P   Caja/Puerto:</b></div><div class='col-xs-7'><?php echo $aItems[6]; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class='row text-center center-block' style='margin-top:9em'><div class='col-xs-4 text-center center-block'><i class='fa fa-internet-explorer fa-4x'></i></div><div class='col-xs-4 text-center center-block'> <i class='fa fa-phone fa-4x'></i></div><div class='col-xs-4 text-center center-block'><i class='fa fa-tv fa-4x'></i></div>
                                    </div>
                                        <div class="row">
                                    <div class='row text-center'><div class='col-xs-4 center-block text-center'>

                                            <label class="switch switch-success"><br>' +
                                                <input type='checkbox' id='act1'  class='apagar' checked='checked' onchange='acti_internet(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_internet+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked,\""+datos[0].velocidad_up+"\",\""+datos[0].velocidad_dw+"\")';>" +
                                                <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                </label>';
                                            <label class="switch switch-success text-center center-block"><br>' +
                                                           <input type='checkbox' id='act1' class='apagar' onchange='acti_internet(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_internet+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked,\""+datos[0].velocidad_up+"\",\""+datos[0].velocidad_dw+"\")';>" +
                                                           <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                       </label>';
                                            

                                        <div class='row text-center'><div class='col-xs-4 center-block text-center'>";

                                                if(parseInt(datos[0].tel_sino)>0)
                                                content += '<label class="switch switch-primary"><br>' +
                                                    "           <input type='checkbox' id='act2'  class='apagar' checked='checked' onchange='acti_voz(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_vozip+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked)';>" +
                                                    '              <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                    '       </label>';
                                                else
                                                content += '<label class="switch switch-primary"><br>' +
                                                    "           <input type='checkbox' id='act2'  class='apagar' onchange='acti_voz(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_vozip+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked)';>" +
                                                    '           <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                    '       </label>';
                                                

                                            <div class='row text-center'><div class='col-xs-4 center-block text-center'>";

                                                    if(parseInt(datos[0].tv_sino)>0)
                                                    content += '<label class="switch switch-info"><br>' +
                                                        "           <input type='checkbox' id='act3'  class='apagar' checked='checked' onchange='acti_iptv(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_iptv+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked)';>" +
                                                        '              <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                        '       </label>';
                                                    else
                                                    content += '<label class="switch switch-info"><br>' +
                                                        "           <input type='checkbox' id='act3'  class='apagar' onchange='acti_iptv(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_iptv+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\",this.checked)';>" +
                                                        '           <span class="switch-label" data-on="SI" data-off="NO"></span>' +
                                                        '       </label>';
                                                    

                                                <div class='row text-center'><div class='col-xs-12 center-block text-center'><center><img src='img/procesando.gif' id='procesando' style='display:none'></center><br><br><b>Modificar Velocidades</b><br><br>";


                                                        <div class='row text-center'>" +
                                                            "<div class='col-xs-1 col-sm-3'></div><div class='col-xs-5 col-sm-3 center-block text-center'>" +
                                                                ' <label>Velocidad Subida</label>' +
                                                                '                            <select name="velocidad_up" id="velocidad_up"' +
                                                                '                                    class="form-control pointer required" >' +
                                                                '                                <option value="">Seleccionar una</option>' +
                                                                '                            </select>'+
                                                                "</div>"+
                                                            "<div class='col-xs-5 col-sm-3 center-block text-center'>" +
                                                                '<label>Velocidad Bajada</label>' +
                                                                '                            <select name="velocidad_dw" id="velocidad_dw"' +
                                                                '                                    class="form-control pointer required" >' +
                                                                '                                <option value="">Seleccionar una</option>' +
                                                                '                            </select>'+
                                                                "</div>"+
                                                            "

                                                        <div class='row text-center'>" +
                                                            "<div class='col-xs-1 col-sm-3'></div>" +
                                                            "<div class='col-xs-5 col-sm-3 center-block text-left'><br><br>" +
                                                                "<span class=\"btn btn-danger apagar\" data-dismiss='modal'>" +
                                                                "<i class=\"fa fa-close\"></i> Cerrar " +
                                                                "</span>" +
                                                                "</div>" +
                                                            "<div class='col-xs-5 col-sm-3 center-block text-right'><br><br>";

                                                                content +=  "<span class='btn btn-success apagar ' onclick='guardarcambios(\""+datos[0].num_pon+"\",\""+datos[0].id_cabecera+"\",\""+datos[0].id_internet+"\",\""+datos[0].id_en_olt+"\",\""+gpon+"\")'>" +
                                        "<i class=\"fa fa-save\"></i> Aplicar " +
                                    "</span>" +
                                                                "</div>" +
                                                            "
                                </div>
                                <!-- /panel content -->

                                <!-- panel footer -->
                                <div class="panel-footer">

                                </div>
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
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>


<!-- PAGE LEVEL SCRIPTS -->
<script type="text/javascript">

    var short="";
    var modo="";





    jQuery(function($) {
        var input = $('#filtro');

        // en cada pulsacion de tecla si el numero de caracteres supera a 3 cargo la informacion filtrada por el texto tecleado
        input.on('keydown', function() {
            var key = event.keyCode || event.charCode;
            var id = $("#cabecera").val();

            if( key == 8 || key == 46 )
                cargar_altas(id,'');

            if($('#filtro').val().length>2){
                var filtro = input.val();
                cargar_altas(short,modo,id,filtro);
            }
        });
    });




    function editar(id){

        $.ajax({
            url: 'carga_provision.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                id_provision: id
            },
            success: function (datos) {
                var gpon = datos[0].c+"/"+datos[0].t+"/"+datos[0].p;
                var content = "";

               
                $("#contenido_modal").empty();
                $("#contenido_modal").append(content);

                carga_vel(datos[0].id_cabecera,datos[0].velocidad_up,datos[0].velocidad_dw);
            }
        });
    }



    function guardarcambios(p,c,i,ont,gpon){
        var up = $("#velocidad_up option:selected").val();
        var dw = $("#velocidad_dw option:selected").val();

        $(".apagar").attr("disabled",true);
        $("#procesando").css('display','block');
        $.ajax({
            url: 'cambia_velocidad.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                c: c,
                i:i,
                up:up,
                dw:dw,
                ont:ont,
                gpon:gpon,
                p:p
            },
            success: function () {
                $(".apagar").attr("disabled",false);
                $("#procesando").css('display','none');
            }
        });
    }


    function acti_internet(p,a,b,c,d,e,up,dw){
        var respuesta = confirm("¿Seguro/a de querer realizar esta operación?");
        if(respuesta==true){
            $(".apagar").attr("disabled",true);
            $("#procesando").css('display','block');
            $.ajax({
                url: 'editar_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a:a,    // idcabecera
                    b:b,    // serviceport
                    c:c,    // idont
                    d:d,    // ctp
                    e:e,    // si_no
                    s:'100',
                    up:up,
                    dw:dw,
                    p:p

                },
                success: function () {
                    $(".apagar").attr("disabled",false);
                    $("#procesando").css('display','none');
                }
            });
        }
    }

    function acti_voz(p,a,b,c,d,e){
        var respuesta = confirm("¿Seguro/a de querer realizar esta operación?");
        if(respuesta==true){
            $(".apagar").attr("disabled",true);
            $("#procesando").css('display','block');
            $.ajax({
                url: 'editar_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a:a,    // idcabecera
                    b:b,    // serviceport
                    c:c,    // idont
                    d:d,    // ctp
                    e:e,    // si_no
                    s:'300',
                    p:p
                },
                success: function () {
                    $(".apagar").attr("disabled",false);
                    $("#procesando").css('display','none');
                }
            });
        }
    }

    function acti_iptv(p,a,b,c,d,e){
        var respuesta = confirm("¿Seguro/a de querer realizar esta operación?");
        if(respuesta==true){
            $(".apagar").attr("disabled",true);
            $("#procesando").css('display','block');
            $.ajax({
                url: 'editar_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a:a,    // idcabecera
                    b:b,    // serviceport
                    c:c,    // idont
                    d:d,    // ctp
                    e:e,    // si_no
                    s:'400',
                    p:p
                },
                success: function () {
                    $(".apagar").attr("disabled",false);
                    $("#procesando").css('display','none');
                }
            });
        }
    }


    function carga_vel(id,up,dw) {
        $('#velocidad_up').empty();
        $('#velocidad_dw').empty();
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

                    if (data[x].perfil_olt == up)
                        $('#velocidad_up')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#velocidad_up')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));


                    if (data[x].perfil_olt == dw)
                        $('#velocidad_dw')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#velocidad_dw')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));

                }
            }
        });
    }

</script>



</body>
</html>