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

						<div class="col-md-12">


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
