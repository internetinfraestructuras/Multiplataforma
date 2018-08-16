<?php
ini_set('display_errors',0);
error_reporting('E_ALL');

if (!isset($_SESSION)) {@session_start();}
// ficheros necesarios
require_once(__DIR__ . '/config/define.php');   // definicion de variables
require_once(__DIR__ . '/config/util.php');     // herramientas y utilidades

// se encarga de comprobar que el usuario tiene nivel suficiente para visualizar esta pagina
// 0 admin, 1 revendedor, 2 operador, 3 instalador, 4 todos
check_session(4);
?>

<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo OWNER; ?> Admin</title>
		<meta name="description" content="" />
		<meta name="Author" content="<?php echo AUTOR; ?>" />

		<!-- mobile settings -->
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

        <!-- WEB FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
              rel="stylesheet" type="text/css"/>

        <!-- CORE CSS -->
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- THEME CSS -->
        <link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

        <!-- JQGRID TABLE -->
        <link href="assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

        <style>
            .blink {
                animation: blinker 1s linear infinite;
            }

            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }
        </style>
	</head>

	<body>


		<!-- WRAPPER -->
		<div id="wrapper" class="clearfix">

			<aside id="aside" style="position:fixed;left:0">
                <!-- cargo menu lateral izquierdo, está declarado una sola vez para mejorar su mantenimiento-->
				<?php require_once ('menu-izquierdo.php'); ?>
				<span id="asidebg"><!-- aside fixed background --></span>
			</aside>
                <!-- cargo menu superior, está declarado una sola vez para mejorar su mantenimiento-->
                <?php require_once ('menu-superior.php'); ?>

			<section id="middle" style="margin-top:60px;">
				<div id="content" class="dashboard padding-20">


                    <div class="row">

                        <div class="col-md-6">

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading">
									<span class="title elipsis">
										<strong>Portabilidades</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#ttab1_nobg" data-toggle="tab">En Curso</a>
                                        </li>
                                        <li class=""><!-- TAB 2 -->
                                            <a href="#ttab2_nobg" data-toggle="tab">Finalizadas</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="ttab1_nobg" class="tab-pane active"><!-- TAB 1 CONTENT -->

                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Sold</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><a href="#">Apple iPhone 5 - 32GB</a></td>
                                                        <td>$612.50</td>
                                                        <td>789</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Allview Ax4 Nano - Cortex A7 Dual-Core 1.30GHz, 7"</a></td>
                                                        <td>$215.50</td>
                                                        <td>3411</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Motorola Droid 4 XT894 - 16GB - Black </a></td>
                                                        <td>$878.50</td>
                                                        <td>784</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Intel Core i5-4460, 3.2GHz</a></td>
                                                        <td>$42.33</td>
                                                        <td>3556</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Samsung Galaxy Note 3 </a></td>
                                                        <td>$655.00</td>
                                                        <td>3987</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">HyperX FURY Blue 8GB, DDR3, 1600MHz</a></td>
                                                        <td>$19.50</td>
                                                        <td>2334</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Gigabyte NVIDIA GeForce GT 730</a></td>
                                                        <td>$122.00</td>
                                                        <td>3499</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    More Top Sales
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="ttab2_nobg" class="tab-pane"><!-- TAB 2 CONTENT -->

                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Sold</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><a href="#">Motorola Droid 4 XT894 - 16GB - Black </a></td>
                                                        <td>$878.50</td>
                                                        <td>784</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Gigabyte NVIDIA GeForce GT 730</a></td>
                                                        <td>$122.00</td>
                                                        <td>3499</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">HyperX FURY Blue 8GB, DDR3, 1600MHz</a></td>
                                                        <td>$19.50</td>
                                                        <td>2334</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Intel Core i5-4460, 3.2GHz</a></td>
                                                        <td>$42.33</td>
                                                        <td>3556</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Samsung Galaxy Note 3 </a></td>
                                                        <td>$655.00</td>
                                                        <td>3987</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Apple iPhone 5 - 32GB</a></td>
                                                        <td>$612.50</td>
                                                        <td>789</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="#">Allview Ax4 Nano - Cortex A7 Dual-Core 1.30GHz, 7"</a></td>
                                                        <td>$215.50</td>
                                                        <td>3411</td>
                                                        <td><a href="#" class="btn btn-default btn-xs btn-block">View</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    More Most Visited
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                    </div>
                                    <!-- /tabs content -->

                                </div>
                                <!-- /panel content -->

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div id="panel-3" class="panel panel-default">
                                <div class="panel-heading">
									<span class="title elipsis">
										<strong>RECENT ACTIVITIES</strong> <!-- panel title -->
									</span>
                                </div>

                                <!-- panel content -->
                                <div class="panel-body">

                                    <ul class="list-unstyled list-hover slimscroll height-300" data-slimscroll-visible="true">

                                        <li>
                                            <span class="label label-danger"><i class="fa fa-bell-o size-15"></i></span>
                                            Urgent task: add new theme to fastAdmin
                                        </li>

                                        <li>
                                            <span class="label label-success"><i class="fa fa-user size-15"></i></span>
                                            <a href="#">5 pending memership</a>
                                        </li>

                                        <li>
                                            <span class="label label-warning"><i class="fa fa-comment size-15"></i></span>
                                            <a href="#">24 New comments that needs your approval</a>
                                        </li>

                                        <li>
                                            <span class="label label-default"><i class="fa fa-briefcase size-15"></i></span>
                                            No work for tomorrow &ndash; everyone is free!
                                        </li>

                                        <li>
                                            <span class="label label-info"><i class="fa fa-shopping-cart size-15"></i></span>
                                            You have new 3 orders unprocessed
                                        </li>

                                        <li>
                                            <span class="label label-success"><i class="fa fa-bar-chart-o size-15"></i></span>
                                            Generate the finance report for the previous year
                                        </li>

                                        <li>
                                            <span class="label label-success bg-black"><i class="fa fa-cogs size-15"></i></span>
                                            CentOS server need a kernel update
                                        </li>

                                        <li>
                                            <span class="label label-warning"><i class="fa fa-file-excel-o size-15"></i></span>
                                            <a href="#">XCel finance report for 2014 released</a>
                                        </li>

                                        <li>
                                            <span class="label label-danger"><i class="fa fa-bell-o size-15"></i></span>
                                            Power grid is off. Moving to solar backup.
                                        </li>

                                        <li>
                                            <span class="label label-warning"><i class="fa fa-comment size-15"></i></span>
                                            <a href="#">24 New comments that need your approval</a>
                                        </li>

                                        <li>
                                            <span class="label label-default"><i class="fa fa-briefcase size-15"></i></span>
                                            No work for tomorrow &ndash; everyone is free!
                                        </li>

                                        <li>
                                            <span class="label label-info"><i class="fa fa-shopping-cart size-15"></i></span>
                                            You have new 3 orders unprocessed
                                        </li>

                                        <li>
                                            <span class="label label-success"><i class="fa fa-bar-chart-o size-15"></i></span>
                                            Generate the finance report for the previous year
                                        </li>

                                        <li>
                                            <span class="label label-success bg-black"><i class="fa fa-cogs size-15"></i></span>
                                            CentOS server need a kernel update
                                        </li>

                                        <li>
                                            <span class="label label-warning"><i class="fa fa-file-excel-o size-15"></i></span>
                                            <a href="#">XCel finance report for 2014 released</a>
                                        </li>

                                        <li>
                                            <span class="label label-danger"><i class="fa fa-bell-o size-15"></i></span>
                                            Power grid is off. Moving to solar backup.
                                        </li>
                                    </ul>

                                </div>
                                <!-- /panel content -->

                                <!-- panel footer -->
                                <div class="panel-footer">

                                    <a href="#"><i class="fa fa-arrow-right text-muted"></i> View Activities Archive</a>

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
        <!-- jQuery library -->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script
                src="https://code.jquery.com/jquery-2.2.4.min.js"
                integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
                crossorigin="anonymous"></script>
        <!-- Latest compiled JavaScript -->
<!--        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <script type="text/javascript" src="assets/js/app.js"></script>
		<script type="text/javascript" src="js/utiles.js"></script>

		<!-- PAGE LEVEL SCRIPT -->
		<script type="text/javascript">

            // todo: -------------------------------------------------------------------------------------------
            // cuando se selecciona una cabecera en el combo, cargo los datos que pertenecen a ella
            // esta funcion muestra los datos del estado de las ont de los clientes, esta informacion
            // la genera un fichero localizado en /cronjobs/ont_infos.php que se ejecuta por crontab cada hora
            // todo: -------------------------------------------------------------------------------------------



        </script>
    </body>
</html>
