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
require_once ('../../clases/Recibos.php');
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
								<strong>LISTADO DE <?php echo DEF_FACTURACION; ?> RECURRENTES MES ACTUAL</strong> <!-- panel title -->
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
                            <label>Filtro por meses:</label>
                            <select onchange="cargarFacturaMes(this.value)">
                                <option >MES ACTUAL</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <br>
                            <label>TIPO REMESA: </label>
                            <select id="remesa"  name="remesa[orden][]" class="form-control">
                            <?php
                            $recibos=Recibo::getTiposRecibos();
                                for($j=0;$j<count($recibos);$j++)
                                {

                                $idTipo=$recibos[$j][0];
                                $nombre=$recibos[$j][1];


                                echo "<option data-id='".$idTipo."' value='" .$idTipo. "'>".$nombre."</option>";
                                }

                                echo '</select>';
                            ?>
<br>
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#FACTURA</th>
                                    <th>FECHA</th>
                                    <th>IMPUESTO</th>
                                    <th>DESCUENTO</th>
                                    <th>TOTAL</th>
                                    <th>REMESAR</th>
                                    <th>OPCIONES</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(empty($_GET['mes']))
                                    $listado=Factura::getFacturasRecurrentesMesCurso($_SESSION['REVENDEDOR']);
                                else
                                    $listado=Factura::getFacturasMes($_SESSION['REVENDEDOR'],2);
                                   // $listado=Factura::getFacturas($_SESSION['REVENDEDOR']);//MES BUSCADO



                                for($i=0;$i<count($listado);$i++)
                                {

                                    $id=$listado[$i][0];
                                    $fecha=$listado[$i][1];
                                    $impuesto=$listado[$i][2];
                                    $dto=$listado[$i][3];
                                    $total=$listado[$i][4];

                                    echo "<tr>";
                                    echo "<td >$id</td><td>$fecha</td><td>$impuesto</td><td>$dto</td><td>$total</td>";
                                    echo'<td><select id="empleados"  name="ordenes[orden][]" class="form-control">';
                                    echo "<option data-id='".$id."' value='0'>No</option>";
                                    echo "<option data-id='".$id."' value='1'>Si</option>";



                                    ?>
                                    <td class="td-actions text-right">
                                        <a href="ficha-factura.php?idFactura=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </a>
                                        <a href="imprimirFactura.php?idFactura=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-print"></i>
                                            </button>
                                        </a>

                                        <a href="imprimir19.php?idModelo=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-file-code-o"></i>
                                            </button>
                                        </a>
                                        <a href="ficha-modelo.php?idModelo=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-file-archive-o"></i>
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
                        <!-- panel footer -->
                        <div class="panel-footer">
                            <div class="row">





                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>

                                            <button type="submit" class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">GENERAR REMESA</button>
                                        </center>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- /panel footer -->

                    </div>


                </div>
                <div class="panel-body" id="listado">
                    <div id="panel-1" class="panel panel-default">
                        <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_FACTURACION; ?> PENDIENTES DE GENERAR FACTURAS ESTE MES</strong> <!-- panel title -->
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
                                    <th>#CONTRATO</th>
                                    <th>CLIENTE</th>
                                    <th>FECHA_INICIO</th>

                                    <th>FECHA FIN</th>
                                    <th>ESTADO</th>
                                    <th>OPCIONES</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $listaContratos=Contrato::getContratosAltaEmpresa($_SESSION['REVENDEDOR']);

                                for($i=0;$i<count($listaContratos);$i++)
                                {

                                    $id=$listaContratos[$i][0];
                                    $numero=$listaContratos[$i][1];
                                    $idCliente=$listaContratos[$i][2];
                                    $inicio=$listaContratos[$i][3];
                                    $fin=$listaContratos[$i][4];
                                    $estado=$listaContratos[$i][5];


                                    if(!empty($campanas))
                                    {
                                        for($l=0;$l<count($campanas);$l++)
                                            $dto=$campanas[$l][1];

                                    }
                                    //OBTENEMOS LAS FACTURAS DEL MES EN CURSO DE DICHA EMPRESA,SI LA FACTURA YA ESTUVIESE GENERADA NO SE VUELVE A GENERAR!!!

                                      $facturasMes=Factura::getFacturasMesCurso($_SESSION['REVENDEDOR']);





                                         if($facturasMes[$o][5]!=$listaContratos[$i][0])
                                         {
                                             echo "<tr>";
                                             echo "<td >$numero</td>
                                                    <td>$idCliente<a target='_blank' href=\"../../ficha-cliente.php?idCliente=$idCliente\"><button type=\"button\" rel=\"tooltip\" ><i class=\"fa fa-eye\"></i></button></a>
                                                    </td><td>$inicio</td><td>$fin</td><td>$estado</td>";

                                         }





                                    ?>
                                    <td class="td-actions text-right">
                                        <a href="generar-factura.php?idFactura=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-eye"></i>
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

    function cargarFacturaMes(id)
    {
       // window.location.href=window.location.href+"?mes="+id);
        location.reload();

    }


</script>



</body>
</html>