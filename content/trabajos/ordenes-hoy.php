<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
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
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                            $listado= $util->selectWhere3('ordenes,ordenes_estados,contratos,clientes',
                                                array("ordenes.id,ordenes.fecha_alta,ordenes_estados.nombre,ordenes_estados.id,clientes.nombre,clientes.id,clientes.apellidos"),
                                                "ordenes.id_contrato=contratos.id 
                                            AND ordenes_estados.id=ordenes.id_tipo_estado 
                                            AND contratos.id_cliente=clientes.id
                                            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND ordenes.fecha_alta<=DATE(now()) AND ordenes.id_tipo_estado=1");





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
                                            echo "<td>$id</td><td>$fechaAlta</td><td>$estado</td><td>$cliente $apellidos <a href='/mul/ficha-cliente.php?idCliente=$idCliente' ><button type=\"button\" rel=\"tooltip\" ><i class=\"fa fa-eye\"></i></button></a></td>";

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


                                </div>
                                <!-- /panel footer -->

                            </div>
                        </div>

                    </div>
                </div>



            </div>
            <div class="row">


                <div class="col-lg-6 col-sm-4 col-md-3 col-xs-12">
                    <center>
                        <label><strong>ORDENES SIN ASIGNAR</strong></label>
                    </center>
                    <select id="orden" multiple="multiple" name="ordenes[orden]" style="height:260px" class="form-control">
                        <?php
                        for($i=0;$i<count($listado);$i++)
                        {

                            $id=$listado[$i][0];
                            $fechaAlta=$listado[$i][1];
                            $estado=$listado[$i][2];
                            $idEstado=$listado[$i][3];
                            $cliente=$listado[$i][4];
                            $idCliente=$listado[$i][5];
                            $apellidos=$listado[$i][6];
                            echo "<option data-id='".$id."' value='" .$id. "'>".$id."--".$cliente."</option>";
                        }


                        ?>
                    </select>
                    <a id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="resetearSelect('orden')"><i class="fa fa-recycle"></i> Deseleccionar </a>


                </div>
                <div class="col-lg-6 col-sm-4 col-md-3 col-xs-12">
                    <center>

                        <label><strong>EMPLEADOS</strong></label>
                        <select id="empleados" multiple="multiple" name="ordenes[orden]" style="height:260px" class="form-control">
                            <?php
                            $listado= $util->selectWhere3('usuarios',
                                array("usuarios.id,usuarios.nombre,usuarios.apellidos,usuarios.email"),
                                "usuarios.nivel=2 AND id_empresa=".$_SESSION['REVENDEDOR']);
                            for($i=0;$i<count($listado);$i++)
                            {

                                $id=$listado[$i][0];
                                $nombre=$listado[$i][1];
                                $apellidos=$listado[$i][2];

                                echo "<option data-id='".$id."' value='" .$id. "'>".$nombre."--".$apellidos."</option>";
                            }


                            ?>
                        </select>
                    </center>

                    <a  id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="resetearSelect('empleados')"><i class="fa fa-recycle"></i> Deseleccionar </a>


                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <center>

                            <button type="submit" class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">ASIGNAR TAREAS</button>
                        </center>
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
function filtrar(id)
{

}


</script>



</body>
</html>