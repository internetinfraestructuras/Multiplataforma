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

</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">
    <?php require_once ($root.'menu-superior.php');
        if(isset($_GET['estado']))
            $estado = $_GET['estado'];
        else
            $estado = 2;
    ?>
    <br><br><br>

    <section id="">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Listado de ordenes</li>
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
							    <span class="title">
								    <strong>LISTADO DE <?php echo DEF_ORDENES; ?></strong>
							    </span>

                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <label>Filtrar por estados:</label>
                                    <select name="producto[proveedor]" id="estados_ordenes" onchange="filtrar(this.value)"
                                            class="form-control pointer ">
                                        <option value="">--- Seleccionar una ---</option>
                                        <?php
                                        $util->carga_select('ordenes_estados', 'id', 'nombre', 'id',null,null,null,$estado); ?>
                                    </select>
                                </div>


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
            <?php


            $listado= $orden->obtenerOrdenesAsignadas($_SESSION['REVENDEDOR'], $_SESSION['USER_ID'], $estado);

            foreach ($listado as $linea){

            ?>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-body" id="listado">
                        <div id="panel-1" class="panel panel-default">
                            <div class="panel-heading">
                            <span class="title">
                                <strong><?php echo $linea['NOMBRE']." ".$linea['APELLIDOS']; ?></strong>
                            </span>
                                <ul class="options pull-right list-inline">
                                    <b>
                                        <?php echo "<span style='font-size:1.5em'>Nº: " . $linea['ID'] . " </span>"; ?>
                                    </b>
                                </ul>
                            </div>



                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-xs-12 col-lg-6">
                                        <div class="col-xs-12">
                                            <b><?php echo $linea['DIRECCION']?></b><br><br>
                                            <b>Télefono:</b> <?php echo $linea['MOVIL']; ?>
                                            <br>
                                            <b>Fecha:</b> <span style='color:red'><?php echo $util->fecha_eur($linea['FECHA_ALTA']);?></span><br>
                                            <b>Distancia:</b>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 visible-xs">
                                        <br>
                                    </div>
                                    <div class="col-xs-12 col-lg-6 text-center">
                                        <a href="tel:<?php echo $linea['MOVIL'];?>">
                                            <img src="../../img/call.png" style="display:inline; margin-right:5%">
                                        </a>

                                        <a href="https://maps.google.com?q=<?php echo $linea['DIRECCION'];?>" target="_blank">
                                           <img src="../../img/map.png"  style="display:inline; margin-right:5%">
                                        </a>

                                        <a href="ver_orden_trabajo.php?id=<?php echo $linea['ID'];?>">
                                            <img src="../../img/ver.png"  style="display:inline">
                                        </a>
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


function filtrar(id) {
    location.href="instalador.php?estado="+id;

}


</script>



</body>
</html>