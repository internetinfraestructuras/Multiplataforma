<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once ('../../clases/Factura.php');
require_once ('../../clases/Contrato.php');
require_once ('../../clases/Servicio.php');
require_once ('../../clases/Paquete.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_FACTURACION; ?> / Listado</title>
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
                <li><a href="#"><?php echo DEF_FACTURACION; ?></a></li>
                <li class="active">FACTURAS GENERADAS</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="panel panel-default">

                <div class="panel-body" id="listado">
                    <div id="panel-1" class="panel panel-default">
                        <div class="panel-heading">
							<span class="title elipsis">
								<strong>FACTURA NÚMERO:<?php echo $_GET['idFactura'];?></strong> <!-- panel title -->
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


                        $datosFactura=Factura::getDatosFactura($_GET['idFactura'],$_SESSION['REVENDEDOR']);

                        $numero=$datosFactura[0][0];
                        $fecha=$datosFactura[0][1];
                        $impuesto=$datosFactura[0][2];
                        $descuento=$datosFactura[0][3];
                        $total=$datosFactura[0][4];
                        $idContrato=$datosFactura[0][5];
                        $nombreCliente=$datosFactura[0][6];
                        $apellidosCliente=$datosFactura[0][7];
                        $dniCliente=$datosFactura[0][8];
                        $idCliente=$datosFactura[0][9];

                        $totalSinIva=($total*((100-$impuesto)/100));
                        $ivaTotal=$total-$totalSinIva;

                        ?>

                            <form class="validate" action="guardar-producto.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">
                                                <label>NÚMERO DE FACTURA</label>

                                                <input type="text" name="id" value="<?php echo $numero;?>" id="id" class="form-control disabled" disabled>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label>NÚMERO DE CONTRATO</label><a target="_blank" href="../ventas/ficha-contrato.php?idContrato=<?php echo $idContrato; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-eye"></i>
                                                    </button></a>

                                                <input type="text" name="id" value="<?php echo $idContrato;?>" id="id" class="form-control disabled" disabled>
                                            </div>
                                            <div class="col-md-4 col-sm-5">
                                                <label>Fecha:</label>
                                                <input type="text" name="numeroSerie" id="apellidos"
                                                       class="form-control " value="<?php echo $fecha; ?>">
                                            </div>
                                            <div class="col-md-4 col-sm-5">
                                                <label>Cliente:</label><a target="_blank" href="../../ficha-cliente.php?idCliente=<?php echo $idCliente; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </a>
                                                <input type="text" name="numeroSerie" id="apellidos"
                                                       class="form-control " value="<?php echo $nombreCliente." ".$apellidosCliente; ?>">
                                            </div>


                                        </div>


                                        </div>

                                    </div>
                                </fieldset>
                            </form>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CONCEPTO</th>
                                        <th>IMPUESTOS</th>
                                        <th>DESCUENTO</th>
                                        <th>IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $listado=Factura::getLineasFacturas($_GET['idFactura']);

                                $contadorLineas=1;
                                for($i=0;$i<count($listado);$i++)
                                {

                                    $idLinea=$listado[$i][0];
                                    $importe=$listado[$i][1];
                                    $dto=$listado[$i][2];
                                    $impuesto=$listado[$i][3];

                                    $lineaContrato=Contrato::getLineaContrato($idContrato,$idLinea);

                                   $tipoLinea=$lineaContrato[0][0];
                                   $asociado=$lineaContrato[0][1];

                                   if($tipoLinea==1)
                                       $concepto=Paquete::getNombrePaquete($asociado);
                                    if($tipoLinea==2)
                                        $concepto=Servicio::getDetallesServicio($asociado);

                                    $concepto=$concepto[0][0];


                                    echo "<tr>";
                                    echo "<td >$contadorLineas</td><td>$concepto</td><td>$impuesto</td><td>$dto</td><td>$total</td>";
                                    $contadorLineas++;

                                    ?>

                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2">
                                    <label>TOTAL(Exc.IVA):</label>

                                    <input type="text" name="id" value="<?php echo $totalSinIva;?> €" id="id" class="form-control disabled" disabled>
                                </div>
                                <div class="col-md-1 col-sm-5">
                                    <label>Impuestos:</label>
                                    <input type="text" name="numeroSerie" id="apellidos"
                                           class="form-control " value="<?php echo $impuesto; ?> %">
                                </div>
                                <div class="col-md-1 col-sm-5">
                                    <label>IVA:</label>
                                    <input type="text" name="numeroSerie" id="apellidos"
                                           class="form-control " value="<?php echo $ivaTotal; ?> €">
                                </div>
                                <div class="col-md-1 col-sm-5">
                                    <label>Descuento %:</label>
                                    </a>
                                    <input type="text" name="numeroSerie" id="apellidos"
                                           class="form-control " value="<?php echo $descuento; ?> %">
                                </div>


                                <div class="col-md-1 col-sm-5">
                                    <label>TOTAL(IVA incl.):</label>
                                    <input type="text" name="numeroSerie" id="apellidos"
                                           class="form-control " value="<?php echo $total; ?> €">
                                </div>
                            </div>


                        </div>
                        </div>
                        <!-- /panel content -->

                        <!-- panel footer -->
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                        IMPRIMIR FACTURA

                                    </button>
                                </div>
                            </div>

                        </div>
                        <!-- /panel footer -->

                    </div>


                </div>

            </div>
        </div>

    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>
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
        var respuesta = confirmar("¿Seguro/a de querer borrar este modelo de producto?");

        if(respuesta)
        {

            jQuery.ajax({
                url: 'borrar-modelos.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_modelos',
                    p:id
                },
                success: function (data)
                {
                    if(data!="" && data!=1)
                        alert(data);
                    else
                        location.reload();
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