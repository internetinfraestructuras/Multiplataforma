<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../clases/Orden.php');
$util = new util();
$orden = new Orden();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);
$root="../../";
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> Instalaciones</title>
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
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

    <style>

    </style>
</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">


    <section id="">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Mostrar una orden</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">


            <div class="row">
            <?php
            $listado= $orden->getOrden($_GET['id']);
            foreach ($listado as $linea){
            ?>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body" id="listado">
                        <div id="panel-1" class="panel panel-default">
                            <div class="panel-heading">
                            <span class="title elipsis h2">
                                <strong><?php echo $linea['nombre']." ".$linea['apellidos']; ?></strong>
                            </span>
                                <ul class="options pull-right list-inline">
                                    <b>Orden ID:
                                        <?php echo $_GET['id']; ?>
                                    </b>
                                </ul>
                            </div>

                            <div class="panel-footer">
                                <div class="row">
                                    <?php
                                    $lineas= $orden->getLineasOrden($linea[0]);
                                    foreach ($lineas as $lineaD){
                                        echo "<div class='row'>";
                                        echo "<div class='col-xs-2 col-lg-1'>";
                                        echo    "<img src='../../img/serv".$lineaD['servicio'].".png'>";
                                        echo "</div>";
                                        echo "<div class='col-xs-4 col-lg-2'><b>Servicio:</b><br><span class='datos'>";
                                        echo   $lineaD['tipo'];
                                        echo "</span></div>";
                                        echo "<div class='col-xs-6 col-lg-2'><b>Modelo:</b><br><span class='datos'>";
                                        echo   $lineaD['modelo'];
                                        echo "</span></div>";
                                        echo "<div class='col-xs-10 col-lg-2'><b>Serial:</b><br><span class='datos'>";
                                        echo   $lineaD['serial'];
                                        echo "</span></div>";
                                        echo "<div class='col-xs-10 col-lg-2'><b>Configuración:</b><br><span class='datos'>";
                                            $config= $orden->getLineasOrdenDetalles($lineaD['ID_LINEA_DETALLE_CONTRATO']);
                                            foreach ($config as $valor){
                                                var_dump($valor);
                                            }
                                        echo "</span></div>";
                                        echo "<div class='col-xs-6 col-lg-2'>";
                                        echo    '
                                            <div class="btn-group">
                                                <button type="button" onclick="aprovisionar('.$lineaD['ID'].')" class="btn btn-primary">Aprovisionar</button>
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Opciones Disponibles</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#"><i class="fa fa-edit"></i> Verificar Estado</a></li>
                                                    <li><a href="#"><i class="fa fa-recycle"></i> Reaprovisionar</a></li>
                                                    <li><a href="#"><i class="fa fa-question-circle"></i> Abrir Incidencia</a></li>
                                                </ul>
                                            </div>
                                        ';
                                        echo "</div>";


                                        echo "</div><br><br>";
                                    }
                                    ?>
                                    <div class="col-xs-12 visible-xs">
                                        <br>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <?php } ?>
            </div>
        </div>

    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES-->


<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>



<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
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
function filtrar(id)
{

}


</script>



</body>
</html>