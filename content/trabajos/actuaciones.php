<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar los datos de clientes ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(2);
require_once('../../clases/Orden.php');

$listado=Orden::getOrdenCerradasPendientesFacturacion($_SESSION['REVENDEDOR']);




?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_ORDENES; ?> / Altas</title>
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
    <script type="text/javascript" src="js/utiles.js"></script>

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

        <?php require_once ('../../menu-superior.php'); ?>

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
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Listado de orden</li>
            </ol>
        </header>
        <!-- /page title -->

        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-10">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_ORDENES); ?> CON INSTALACIÓN </strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-paquete.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>

                                <div class="panel-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th># ORDEN</th>
                                            <th>CLIENTE</th>
                                            <th>FECHA APERTURA</th>
                                            <th>FECHA CIERRE</th>
                                            <th>ESTADO COBRO</th>
                                            <th>FACTURABLE</th>
                                            <th>MODO COBRO</th>
                                            <th>ESTADO</th>
                                            <th>PVP</th>

                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i]['ID'];
                                            $numero=$listado[$i]['NUMERO'];
                                            $contrato=$listado[$i]['ID_CONTRATO'];
                                            $fechaAlta=$listado[$i]['FECHA_ALTA'];
                                            $fechaBaja=$listado[$i]['FECHA_FIN'];

                                            $tipoEstado=$listado[$i]['ESTADO_COBRO'];
                                            $tipoEstado=Orden::getEstadoCobro($tipoEstado);
                                            $tipoEstado=$tipoEstado[0][0];

                                            $facturar=$listado[$i]['FACTURAR'];
                                            $nombre=$listado[$i]['NOMBRE_CLIENTE'];
                                            $apellidos=$listado[$i]['APELLIDOS_CLIENTE'];

                                            if($facturar==0)
                                                $facturar="NO";
                                            else
                                                $facturar="SI";

                                            $modoCobro=$listado[$i]['MODO_COBRO'];
                                            $pvp=$listado[$i]['PVP'];
                                            $estado=$listado[$i]['NOMBRE_ESTADO'];


                                            echo "<tr>";
                                            echo "<td><input name='ordenes[ordenId][]' value='$id'  size='5px' class='form-control' readonly></td>
                                                    <td>$id</td>
                                                    <td>$nombre $apellidos </td>
                                                    <td>$fechaAlta</td>
                                                    <td>$fechaBaja</td>
                                                    <td>$tipoEstado</td>
                                                    <td>$facturar</td>
                                                    <td>$modoCobro</td>
                                                    <td>$estado</td>
                                                    <td>$pvp</td>
                                                  ";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-orden.php?idOrden=<?php echo $id; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <a target="_blank" href="facturar.php?idLinea=<?php echo $id;?>&idContrato=<?php echo $contrato;?>"
                                                        <i class="fa fa-print"></i> &nbsp;
                                                </a>
                                                <a href="ficha-orden.php?idOrden=<?php echo $id; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </a>
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


                            </form>

                        </div>

                    </div>
                    <!-- /----- -->

                </div>

                <div class="col-md-2">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Información</h4>
                            <p><em>Por favor, completa toda la información requerida y revísala antes de proceder a realizar cambios.</em></p>
                            <hr/>
                            <p>

                            </p>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <a href="javascript:;" onclick=""
                               class="btn btn-info btn-xs">Ayuda</a>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<!--<script type="text/javascript" src="assets/js/app.js"></script>-->
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

<script>


    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Estas seguro de eliminar el servicio del paquete? Si elimina el servicio del paquete se cobrarán como servicios independientes los demás servicios");


        if(respuesta)
        {

            jQuery.ajax({
                url: 'cancelar-baja.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'cancelar-baja',
                    id:id,
                    idPaquete:<?php echo $_GET['idPaquete']; ?>,
                    idContrato:<?php echo $_GET['idContrato']; ?>,
                    idLineaContrato:<?php echo $_GET['idLineaContrato']; ?>,
                    productos:productos
                },
                success: function (data) {

                    console.log(data);
                    //  location.reload();
                }
            });

        }
    }
    function confirmar(text){

        return confirm(text);

    }


</script>



</body>
</html>