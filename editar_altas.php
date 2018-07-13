<?php

if (!isset($_SESSION)) {
    @session_start();
}

/*
    ╔═══════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar las altas aprovisionadas ║
    ╚═══════════════════════════════════════════════════════════════╝
*/

require_once('config/util.php');
$util = new util();
check_session(2);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_PROVISIONES; ?> / Editar</title>
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


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_PROVISIONES; ?></a></li>
                <li class="active">Editar</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_PROVISIONES; ?></strong> <!-- panel title -->
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

                                    <table id="jqgrid"></table>
                                    <div id="tabla_altas"></div>

                                    <br />

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

    $(document).ready(function(){
        cargar_altas();
    });


    // cargo listado de altas desde la tabla aprovisionados
    // si se pasa como parametro olt y un valor id de una cabecera
    // solo se cargan los correspondientes a esa cabecera

    function cargar_altas(){

        $("#tabla_altas").empty();
        $("#tabla_altas").append('<div class="row">');
        $("#tabla_altas").append('<div class="table-responsive">' +
            '<table class="table table-condensed nomargin">' +
                '<thead>' +
                    '<tr>' +
                        '<th>PON</th>' +
                        '<th>Serial</th>' +
                        '<th>Cliente</th>' +
                        '<th>Teléfono</th>' +
                        '<th>Fecha Alta</th>' +
                        '<th>Up/Down</th>' +
                        '<th>Ppoe</th>' +
                        '<th>Caja / Puerto</th>' +
                        '<th>C/T/P</th>' +
                        '<th></th>' +
                    '</tr>' +
                '</thead><tbody id="aqui_la_tabla"></tbody>');

        $.ajax({
            url: 'carga_provision_olt.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                a: 'provision_olt',
                olt: ''
            },
            success: function (datos) {
                $.each(datos, function(i) {
                    var fecha = new Date(datos[i].fecha);
                    $("#aqui_la_tabla").append('<tr><td>'+datos[i].num_pon+'</td><td>'+datos[i].serial+'</td><td>'+
                        datos[i].cli_apellidos+' ' +datos[i].cli_nombre+ '</td><td>'+ datos[i].cli_tel1+'</td><td>'+fecha.toLocaleDateString("es-ES")+'</td><td>'+datos[i].velocidad_up+ 'Mb / '+
                        datos[i].velocidad_dw + 'Mb</td><td>'+datos[i].ppoe_usuario+'</td><td>'+datos[i].caja + ' / '+
                        datos[i].puerto+'</td><td>'+datos[i].c+ '/' +datos[i].t+ '/'+datos[i].p+
                        '</td><td>' +
                        '<span class="fa fa-pencil" style="font-size:1em; cursor: pointer;margin-left:.5em" onclick="editar(\''+datos[i].num_pon+'\',\''+datos[i].serial+'\');"></span>' +
                        '</td></tr>');
                });
            }
        });
    }




    function editar(pon,serial){
        alert(pon);
        alert(serial);
    }



</script>



</body>
</html>