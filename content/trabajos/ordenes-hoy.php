<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once ('../../clases/Orden.php');
require_once ('../../clases/Usuarios.php');
require_once ('../../clases/Servicio.php');
require_once ('../../clases/Cobros.php');
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
    <title><?php echo OWNER; ?> <?php echo DEF_CLIENTES; ?> / Altas</title>
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

        <?php require_once('../../menu-superior.php');


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
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Listado de ordenes no tramitadas</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">



            <div class="row">
                <form class="validate" action="guardar-asignar-orden.php" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="action" value="ordenes"/>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							    <span class="title elipsis">
								    <strong>LISTADO DE <?php echo DEF_ORDENES; ?></strong>
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
                                            <th>FECHA APERTURA</th>
                                            <th>ESTADO</th>
                                            <th>CLIENTE</th>
                                            <th>ASIGNACION</th>
                                            <th>COSTE SERVICIO</th>
                                            <th>MODO COBRO</th>
                                            <th>FACTURABLE</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $listado=Orden::getOrdenesPendientes();
                                        $empleados=Usuarios::getInstaladores();
                                        $actuaciones=Servicio::getServiciosActuacion($_SESSION['REVENDEDOR']);
                                        $modosCobro=Cobros::getModosCobro();

                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i][0];
                                            $fechaAlta=$listado[$i][1];
                                            $estado=$listado[$i][2];
                                            $idEstado=$listado[$i][3];
                                            $cliente=$listado[$i][4];
                                            $idCliente=$listado[$i][5];
                                            $apellidos=$listado[$i][6];

                                            echo "<tr>";
                                            echo "<td><input name='ordenes[ordenId][]' value='$id'  class='form-control' readonly></td><td>$fechaAlta</td><td>$estado</td><td>$cliente $apellidos<a href='/mul/ficha-cliente.php?idCliente=$idCliente' >&nbsp;<button type=\"button\" rel=\"tooltip\" ><i class=\"fa fa-eye\"></i></button></a></td>";
                                            echo'<td><select id="empleados"  name="ordenes[orden][]" class="form-control">';


                                            echo "<option data-id='".$id."' value='0'>Sin Asignar</option>";
                                            for($j=0;$j<count($empleados);$j++)
                                            {

                                                $idEmpleado=$empleados[$j][0];
                                                $nombre=$empleados[$j][1];
                                                $apellidos=$empleados[$j][2];

                                                echo "<option data-id='".$idEmpleado."' value='" .$idEmpleado. "'>".$nombre."--".$apellidos."</option>";
                                            }
                                            echo '</select></td>';

                                            echo'<td><select id="instalacion"  name="ordenes[instalacion][]" class="form-control">';


                                            echo "<option data-id='".$id."' value='0'>Sin coste</option>";

                                            for($j=0;$j<count($actuaciones);$j++)
                                            {

                                                $idServicio=$actuaciones[$j][0];
                                                $nombre=$actuaciones[$j][1];

                                                echo "<option data-id='".$idServicio."' value='" .$idServicio. "'>".$nombre."</option>";
                                            }
                                            echo '</select></td>';

                                            echo'<td><select id="cobro"  name="ordenes[cobro][]" class="form-control">';


                                            echo "<option data-id='".$id."' value='0'>No cobrar</option>";

                                            for($j=0;$j<count($modosCobro);$j++)
                                            {

                                                $idModo=$modosCobro[$j][0];
                                                $nombre=$modosCobro[$j][1];

                                                echo "<option data-id='".$idModo."' value='" .$idModo. "'>".$nombre."</option>";
                                            }
                                            echo '</select></td>';
                                            echo'<td><select id="factura"  name="ordenes[factura][]" class="form-control">';


                                            echo "<option data-id='0' value='1'>Facturar</option>";
                                            echo "<option data-id='1' value='0'>No facturar</option>";
                                            echo '</select></td>';


                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-orden.php?idOrden=<?php echo $id; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <a target="_blank" href="imprimirOrden.php?idOrden=<?php echo $id;?>"
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
                                <!-- /panel content -->

                                <!-- panel footer -->
                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <center>

                                                    <button type="submit" class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">ASIGNAR TAREAS</button>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                </div>
                                <!-- /panel footer -->

                            </div>
                        </div>

                    </div>

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