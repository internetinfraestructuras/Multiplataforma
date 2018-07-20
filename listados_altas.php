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
    <title><?php echo OWNER; ?> - <?php echo DEF_PROVISIONES; ?> / Listado</title>
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
        a{
            text-decoration: none;
            color: #676a6c;
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
                <li><a href="#"><?php echo DEF_PROVISIONES; ?></a></li>
                <li class="active">Listados</li>
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
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <label>Filtrar por Cabecera</label>
                                            <select id="cabecera" name="o" class="form-control" onchange="cargar_altas('','',this.value)">
                                                <option value='0' selected>Ver Todas</option>"

                                                <?php
                                                $c = 0;
                                                // carga lista de cabeceras en el combo para poder filtrar los datos
                                                while ($row = mysqli_fetch_array($cabeceras)) {
                                                    if ($c == 0) {
                                                        $ultimo = $row;
                                                        $c = 1;
                                                    }
                                                    echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-4">
                                            <label>Filtrar por Dni / Nombre / Apellidos / Pon </label><br>
                                            <input type="text" id="filtro" placeholder=:"teclear 4 caracteres o más" class="form-control">
                                        </div>
                                        <!--                                        <div class="col-xs-2">-->
                                        <!--                                            <label>Ordenar por...</label>-->
                                        <!--                                            <select id="ordenar" class="form-control" onchange="select_cabecera('','',this.value)">-->
                                        <!--                                                <option value='0' selected>Cliente</option>"-->
                                        <!--                                                <option value='1' selected>Ip</option>"-->
                                        <!--                                                <option value='2' selected>Pon</option>"-->
                                        <!--                                                <option value='3' selected>Potencia RX</option>"-->
                                        <!--                                            </select>-->
                                        <!--                                        </div>-->
                                        <div class="col-xs-3">

                                        </div>

                                        <br>
                                    </div>
                                    <br> <br>
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
    var short="";
    var modo="";

    $(document).ready(function(){
        cargar_altas('',getParameterByName('s'),getParameterByName('o'));

    });

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

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


    function cargar_altas(short,modo,cabecera,filtro){

        $("#tabla_altas").empty();
        $("#tabla_altas").append('<div class="row">');
        $("#tabla_altas").append('<div class="table-responsive">' +
            '<table class="table table-condensed nomargin">' +
                '<thead>' +
                    '<tr>' +
                        '<th>PON' +
                            '<a href="listados_altas.php?s=pon&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                            '<a href="listados_altas.php?s=pon&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Serial' +
                            '<a href="listados_altas.php?s=ser&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                            '<a href="listados_altas.php?s=ser&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Cliente' +
                            '<a href="listados_altas.php?s=cli&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                            '<a href="listados_altas.php?s=cli&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Teléfono</th>' +
                        '<th>Fecha Alta' +
                            '<a href="listados_altas.php?s=dat&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                            '<a href="listados_altas.php?s=dat&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Up/Down</th>' +
                        '<th>Ppoe</th>' +
                        '<th>Caja / Puerto</th>' +
                        '<th>C/T/P</th>' +
                        '<th></th>' +
                    '</tr>' +
                '</thead><tbody id="aqui_la_tabla"></tbody>');

        $.ajax({
            url: 'carga_provision.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                a: 'cargar_altas',
                orden:short,
                modo:modo,
                cabecera:cabecera,
                filtro:filtro
            },
            success: function (datos) {
                $.each(datos, function(i) {
                    $("#aqui_la_tabla").append('<tr><td>'+datos[i].num_pon+'</td><td>'+datos[i].serial+'</td><td>'+
                        datos[i].cli_apellidos+' ' +datos[i].cli_nombre+ '</td><td>'+ datos[i].cli_tel1+'</td><td>'+datos[i].fecha.substr(0,10)+'</td><td>'+datos[i].velocidad_up+ 'Mb / '+
                        datos[i].velocidad_dw + 'Mb</td><td>'+datos[i].ppoe_usuario+'</td><td>'+datos[i].caja + ' / '+
                        datos[i].puerto+'</td><td>'+datos[i].c+ '/' +datos[i].t+ '/'+datos[i].p+
                        '</td><td>' +
                        '<span class="fa fa-eye" style="font-size:1em; cursor: pointer;margin-left:.5em" onclick="ver(\''+datos[i].num_pon+'\',\''+datos[i].serial+'\');"></span>' +
                        '</td></tr>');
                });
            }
        });
    }
    function ver(pon,serial){
        alert(pon);
        alert(serial);
    }
</script>
</body>
</html>