<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite la configuracion de contratos ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION))
{
    @session_start();
}
require_once('../../config/util.php');
require_once('../../clases/Empresa.php');
$util = new util();
check_session(1);

$configuracion=Empresa::getConfiguracionEmpresaFacturacion($_SESSION['REVENDEDOR']);

$diaFacturacion=$configuracion[0][0];
$facturacionAutomatica=$configuracion[0][1];

if($facturacionAutomatica!=0)
    $checked="checked";
else
    $checked="";




?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_FACTURACION; ?> / Altas</title>
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
    <script type="text/javascript" src="../../js/utiles.js"></script>



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
                <li><a href="#"><?php echo DEF_FACTURACION; ?></a></li>
                <li class="active">Configuración facturación</li>
            </ol>
        </header>
        <!-- /page title -->



            <div class="row">
                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <form class="validate" action="guardar-configuracion-facturacion.php" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="action" value="configuracion"/>
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>CONFIGURACIÓN FACTURACIÓN</strong> <!-- panel title -->
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
                                    <div class="col-md-12">

                                    </div>

                                </div>
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-2 col-sm-2">
                                            <label>DIA DE FACTURACIÓN:</label>
                                            <input type="text" name="configuracion[dia]" value="<?php echo $diaFacturacion;?>" id="dia" class="form-control disabled" hidden>

                                        </div>
                                        <div class="col-md-6 col-sm-5">
                                            <label>FACTURACIÓN AUTOMÁTICA:</label>
                                            <input type="checkbox" name="configuracion[automatica]" id="automatica"
                                                   class="form-control " <?php echo $checked; ?>/>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                        VALIDAR Y GUARDAR
                                        <span class="block font-lato">verifique que toda la información es correcta</span>
                                    </button>
                                </div>
                            </form>
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

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>



</body>
</html>