<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}

require_once('../../config/util.php');
require_once ('../../clases/Contrato.php');
require_once ('../../clases/Paquete.php');
require_once ('../../clases/Servicio.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);
$root="../../";
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_ANEXOS; ?> / Altas</title>
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
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once ('../../menu-superior.php');


        ?>

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
                <li><a href="#"><?php echo DEF_ANEXOS;?></a></li>
                <li class="active">Listado de anexos</li>
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
								<strong>LISTADO DE <?php echo DEF_ANEXOS." CONTRATO: ".$_GET['idContrato']; ?></strong> <!-- panel title -->
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

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>SERVICIO</th>
                                            <th>TIPO</th>
                                            <th>FECHA</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $listado=Contrato::getAnexosContratos($_SESSION['REVENDEDOR'],$_GET['idContrato']);


                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i][0];
                                            $nombreTipo=$listado[$i][1];
                                            $fecha=$listado[$i][2];
                                            $tipo=$listado[$i][3];
                                            $asociado=$listado[$i][4];

                                            if($tipo==1)
                                                $nombre=Paquete::getNombrePaquete($asociado);
                                            else if($tipo==2)
                                                $nombre=Servicio::getNombreServicio($asociado);

                                            $nombre=$nombre[0][0];

                                            echo "<tr>";
                                            echo "<td>$id</td><td>$nombre</td><td>$nombreTipo</td><td>$fecha</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-anexo.php?idAnexo=<?php echo $id; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>
                                                <button type="button" rel="tooltip" class="">
                                                    <i class="fa  fa-trash" style="font-size:1em; color:green; cursor: pointer" onclick="borrar('<?php echo $id;?>');"></i>
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
</script>



</body>
</html>