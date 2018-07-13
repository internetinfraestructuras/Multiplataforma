<?php

//*******************************************************************************************************
//  Interfaz que permite a los instaladores aprovisionar uno o varios servicios a una ont
//  tras seleccionar un cliente o crear uno nuevo se activan los servicios que quiere asignar
//  Internet, voz, Tv, se busca la ont conectada o se teclea su numero pon, se selecciona la velocidad
//  y se pulsa el boton aprovisionar
//*******************************************************************************************************


if (!isset($_SESSION)) {
    @session_start();
}

require_once('config/util.php');

ini_set('display_errors', 0);
error_reporting('E_ALL');
$util = new util();
check_session(4);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_CLIENTES; ?> /Listados</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

    <!-- JQGRID TABLE -->
    <link href="assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css"/>
    <!--    light   box-->
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.7.12/release/featherlight.min.css" type="text/css"
          rel="stylesheet"/>
    <style>
        .progress {
            height: 35px;

        }

        .progress .skill {
            font: normal 12px "Open Sans Web";
            line-height: 35px;
            padding: 0;
            margin: 0 0 0 20px;
            text-transform: uppercase;
        }

        .progress .skill .val {
            float: right;
            font-style: normal;
            margin: 0 20px 0 0;
        }

        .progress-bar {
            text-align: left;
            transition-duration: 3s;
        }
    </style>
</head>

<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once('php/header-menu.php'); ?>

    </header>
    <!-- /HEADER -->


    <!--
        MIDDLE
    -->
			<section id="middle">

				<!-- page title -->
				<header id="page-header">
					<h1>Inline Charts</h1>
					<ol class="breadcrumb">
						<li><a href="#">Graphs</a></li>
						<li class="active">Inline Charts</li>
					</ol>
				</header>
				<!-- /page title -->

				<div id="content" class="padding-20">

				<div class="row">

					<div class="col-sm-12 col-md-12 col-lg-3">

						<div class="well well-sm" id="event-container">
							<h4>Draggable Events</h4>

							<ul id="external-events" class="list-unstyled">
								<li>
									<span class="bg-calendar bg-primary text-white" data-description="lite desc">Default</span>
								</li>
								<li>
									<span class="bg-calendar bg-info text-white" data-description="info desc">Info</span>
								</li>
								<li>
									<span class="bg-calendar bg-warning text-white" data-description="warning desc">Warning</span>
								</li>
								<li>
									<span class="bg-calendar bg-danger text-white" data-description="danger desc">Danger</span>
								</li>
							</ul>

							<div class="sky-form">
								<label class="checkbox">
									<input type="checkbox" name="checkbox" id="drop-remove" checked=""><i></i> remove after drop
								</label>
							</div>

						</div>

						<div class="alert alert-default">
							NOTE: Click a calendar day to create an event!
						</div>

					</div>

					<div class="col-sm-12 col-md-12 col-lg-9">

						<!-- Panel -->
						<div id="panel-calendar" class="panel panel-default">

							<div class="panel-heading">

								<span class="title elipsis">
									<strong>MY EVENTS</strong> <!-- panel title -->
								</span>


								<div class="panel-options pull-right"><!-- panel options -->
									<ul class="options list-unstyled">
										<li>
											<a href="#" class="opt dropdown-toggle" data-toggle="dropdown"><span class="label label-disabled"><span id="agenda_btn">Month</span> <span class="caret"></span></span></a>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a data-widget="calendar-view" href="#month"><i class="fa fa-calendar-o color-green"></i> <span>Month</span></a></li>
												<li><a data-widget="calendar-view" href="#agendaWeek"><i class="fa fa-calendar-o color-red"></i> <span>Agenda</span></a></li>
												<li><a data-widget="calendar-view" href="#agendaDay"><i class="fa fa-calendar-o color-yellow"></i> <span>Today</span></a></li>
												<li><a data-widget="calendar-view" href="#basicWeek"><i class="fa fa-calendar-o color-gray"></i> <span>Week</span></a></li>
											</ul>
										</li>
										<li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="Colapse" data-placement="bottom"></a></li>
									</ul>
								</div><!-- /panel options -->

							</div>

							<!-- panel content -->
							<div class="panel-body">

								<div id="calendar" data-modal-create="true"><!-- CALENDAR CONTAINER --></div>

							</div>
							<!-- /panel content -->

						</div>
						<!-- /Panel -->

					</div>

				</div>



				</div>
			</section>
			<!-- /MIDDLE -->

		</div>

	
		<!-- JAVASCRIPT FILES -->
		<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
		<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
		<script type="text/javascript" src="assets/js/app.js"></script>

		<!-- PAGE LEVEL SCRIPTS -->
		<script type="text/javascript">

			/* Calendar Data */
			var date 	= new Date();
			var d 		= date.getDate();
			var m 		= date.getMonth();
			var y 		= date.getFullYear();

			var _calendarEvents = [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					allDay: false,
					className: ["bg-primary"],
					description: 'some description...',
					icon: 'fa-clock-o'
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2),
					allDay: false,
					className: ["bg-primary"],
					description: '',
					icon: 'fa-check'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false,
					className: ["bg-warning"],
					description: '',
					icon: 'fa-clock-o'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false,
					className: ["bg-primary"],
					description: '',
					icon: 'fa-clock-o'
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					className: ["bg-primary"],
					description: '',
					icon: 'fa-lock'
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					className: ["bg-success"],
					description: '',
					icon: 'fa-clock-o'
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false,
					className: ["bg-danger"],
					description: '',
					icon: ''
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/',
					className: ["bg-info"],
					description: '',
					icon: 'fa-clock-o'
				}
			];

			loadScript(plugin_path + "jquery/jquery.cookie.js", function(){
				loadScript(plugin_path + "jquery/jquery-ui.min.js", function(){
					loadScript(plugin_path + "jquery/jquery.ui.touch-punch.min.js", function(){
						loadScript(plugin_path + "moment.js", function(){
							loadScript(plugin_path + "bootstrap.dialog/dist/js/bootstrap-dialog.min.js", function(){
								loadScript(plugin_path + "fullcalendar/fullcalendar.js", function(){
									loadScript(plugin_path + "fullcalendar/gcal.js", function(){

										// Load Calendar Demo Script
										loadScript("assets/js/view/demo.calendar.js");

									});
								});
							});
						});
					});
				});
			});

		</script>

	</body>
</html>