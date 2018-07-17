<?php
ini_set('display_errors',0);
error_reporting('E_ALL');
if (!isset($_SESSION)) {
    @session_start();
}

require_once '../../config/db_config.php';
require_once '../../app/clases/IptvAPI.php';
$iptvConector=new IptvAPI();


?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_USUARIOS; ?> almacen/Listados</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
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
    <!--    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />-->

    <!-- JQGRID TABLE -->
    <link href="../../assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

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
    <header id="header">
        <?php require_once ('../../menu-superior.php'); ?>
    </header>
    <section id="middle">
        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_USUARIOS; ?></a></li>
                <li class="active">Listar</li>
            </ol>
        </header>

        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>PRODUCTOS EN ALMACÉN</strong> <!-- panel title -->
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
                                    <?php

                                    $listadoIPTV=$iptvConector->obtenerIPTVSReseller(7);

                                    $n="30";

                                    var_dump($listadoIPTV->$n);


                                    ?>
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>FECHA DE COMPRA</th>
                                            <th>MAC</th>
                                            <th>MODELO</th>
                                            <th>NUMEROSERIE</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        for($i=0;$i<count($listadoIPTV);$i++)
                                        {
                                            $fecha=$listadoIPTV[$i]->fechacompra;
                                            $mac=$listadoIPTV[$i]->mac;
                                            $modelo=$listadoIPTV[$i]->tipo;
                                            $ssid=$listadoIPTV[$i]->numeroserie;
                                            echo "<tr>";
                                            echo "<td>$ssid</td><td>$modelo</td><td>$mac</td><td>$fecha</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <button type="button" rel="tooltip" class="btn btn-info btn-simple btn-icon btn-sm">
                                                    <i class="now-ui-icons users_single-02"></i>
                                                </button>
                                                <button type="button" rel="tooltip" class="btn btn-success btn-simple btn-icon btn-sm">
                                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                                </button>
                                                <button type="button" rel="tooltip" class="btn btn-danger btn-simple btn-icon btn-sm">
                                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                                </button>
                                            </td>
                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>

                                    </table>

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







</body>
</html>
<script>
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