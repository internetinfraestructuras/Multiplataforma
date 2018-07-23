<?php
// todo: *********************************************************************************
// interfaz que muestra un listado de las altas realizadas y permite borrarlas
// se pueden eliminar solo de la base de datos o de la cabecera y base de datos
// todo: *********************************************************************************


if (!isset($_SESSION)) {
    @session_start();
}

require_once('../config/util.php');
$util = new util();
check_session(2);

$root="";
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
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <!-- JQGRID TABLE -->
    <link href="../../assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

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

        <?php require_once('../menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once('../menu-superior.php'); ?>

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
                <li class="active">Bajas</li>
            </ol>
        </header>
        <!-- /page title -->


        <div  class="padding-20">

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
                                            <select id="cabecera" class="form-control" onchange="cargar_altas('','',this.value)">
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
                                            <input type="text" id="filtro" placeholder="teclear 4 caracteres o más" class="form-control">
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
                                    <div class="col-lg-4 col-xs-12">
                                        <i class="fa fa-trash" style="font-size:1.5em; color:green;"></i>
                                        Borrar solo de la base de datos
                                    </div>
                                    <div class="col-lg-4 col-xs-12">
                                        <i class="fa fa-trash" style="font-size:1.5em; color:red;"></i>
                                        Borrar de la base de datos y de la cabecera
                                    </div>

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
<script type="text/javascript">var plugin_path = '../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>
<script type="text/javascript" src="../../js/utiles.js"></script>


<!-- PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    var short="";
    var modo="";

    $(document).ready(function(){
        cargar_altas(getParameterByName('s'),getParameterByName('o'));
        trabajando();
    });

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

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    // recibe como parametros la columna por la que se ha de ordenar(Short) y el modo (Ascendente o descendente)

    function cargar_altas(short,modo,cabecera,filtro){

        $("#tabla_altas").empty();
        $("#tabla_altas").append('<div class="row">');
        $("#tabla_altas").append('<div class="table-responsive">' +
            '<table class="table table-condensed nomargin">' +
                '<thead>' +
                    '<tr>' +
                        '<th>PON' +
                        '<a href="bajas.php?s=pon&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                        '<a href="bajas.php?s=pon&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Serial' +
                        '<a href="bajas.php?s=ser&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                        '<a href="bajas.php?s=ser&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Cliente' +
                        '<a href="bajas.php?s=cli&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                        '<a href="bajas.php?s=cli&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Teléfono</th>' +
                        '<th>Fecha Alta' +
                        '<a href="bajas.php?s=dat&o=asc"><i class="fa fa-arrow-up" style="margin-left:10px;margin-right:10px;"></i></a>' +
                        '<a href="bajas.php?s=dat&o=desc"><i class="fa fa-arrow-down"></i></a></th>' +
                        '<th>Up/Down</th>' +
                        '<th>Ppoe</th>' +
                        '<th>Caja / Puerto</th>' +
                        '<th>C/T/P</th>' +
                        '<th><center>' +
                        '<span class="fa fa-desktop" style="font-size:1em;"></span>' +
                        '<span class="fa fa-server" style="margin-left:15px;font-size:1em;"></span>' +
                        '</center></th>' +
                    '</tr>' +
                '</thead><tbody id="aqui_la_tabla"></tbody>');

        /*
            ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
            ║ cargo por ajax el listado de las altas realizadas que se encuentran en la tabla "aprovisionados"   ║
            ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
        */

        $.ajax({
            url: '../carga_provision.php',
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
                    $("#aqui_la_tabla").append('<tr class="hover"><td>'+datos[i].num_pon+'</td><td>'+datos[i].serial+'</td><td>'+
                        datos[i].cli_apellidos+' ' +datos[i].cli_nombre+ '</td><td>'+ datos[i].cli_tel1+'</td><td>'+datos[i].fecha.substr(0,10)+'</td><td>'+datos[i].velocidad_up+ 'Mb / '+
                        datos[i].velocidad_dw + 'Mb</td><td>'+datos[i].ppoe_usuario+'</td><td>'+datos[i].caja + ' / '+
                        datos[i].puerto+'</td><td>'+datos[i].c+ '/' +datos[i].t+ '/'+datos[i].p+
                        '</td><td class="fancy-form"><center>' +
                        '<i class="fa fa-trash" style="font-size:1em; color:green; cursor: pointer" onclick="borrar1(\''+datos[i].prov_id+'\');"></i>' +
                        '<i class="fa fa-trash" style="margin-left:15px;font-size:1em; color:red; cursor: pointer" onclick="borrar2(\''+datos[i].prov_id+'\');"></i>' +
                        '</center></td></tr>');
                });
            }
        });
    }

    /*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra el registro de provision unicamente de la tabla aprovisionados, pero sigue en la cabecera    ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
*/

    function borrar1(id){
        var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de querer borrar esta ONT de la base de datos?");
        if(respuesta){
            trabajando(1);
            $.ajax({
                url: 'borra_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_solo_alta',
                    p:id,
                    hash: hash
                },
                success: function () {
                    location.reload();
                }
            });
        }
    }

    /*
    ╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ Borra el registro de la tabla y lanza el comando que lo borra de la cabecera, quedando la ont desaprovisionada   ║
    ╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝
    */
    function borrar2(id){
        var hash = md5(id);
            var respuesta = confirmar("¿Seguro/a de querer borrar esta ONT de la base de datos y de la cabecera?");
        if(respuesta){
            trabajando(1);
            // $('#content').append('<center><img src="img/procesando.gif" id="img_procesando"><br><br><h3>Trabajando... Espera</h3></center>');
            $.ajax({
                url: 'borra_provision.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_en_olt',
                    p: id,
                    hash: hash
                },
                success: function () {
                    location.reload();
                }
            });
        }
    }



</script>



</body>
</html>