<?php
ini_set('display_errors',0);
error_reporting('E_ALL');

/*
    ╔══════════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite mostrar la informacion de una ont  ║
    ╚══════════════════════════════════════════════════════════════════╝
*/

if (!isset($_SESSION)) {@session_start();}
require_once(__DIR__ . '/config/define.php');
require_once(__DIR__ . '/config/util.php');
check_session(1);
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
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />

		<!-- CORE CSS -->
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<!-- THEME CSS -->
		<link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

	</head>

	<body>


		<!-- WRAPPER -->
		<div id="wrapper" class="clearfix">

			<aside id="aside" style="position:fixed;left:0">

				<?php require_once ('menu-izquierdo.php'); ?>

				<span id="asidebg"><!-- aside fixed background --></span>
			</aside>
			<!-- /ASIDE -->


			<!-- HEADER -->
                <?php require_once ('menu-superior.php'); ?>

            <br><br><br>
			<section id="middle">
				<div id="content" class="dashboard padding-20">
                    <div class="row">

                        <div class="col-md-12">

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading">
									<span class="title elipsis">
										<strong>COMPROBAR UNA ONT</strong> <!-- panel title -->
									</span>


                                </div>

                                <!-- panel content -->
                                <div class="panel-body">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">
                                        <div id="ttab1_nobg" class="tab-pane active"><!-- TAB 1 CONTENT -->
                                            <div class="col-lg-7 col-sm-6 col-md-6 col-xs-12">
                                                <label>Seleccionar Ont / Cliente</label>
                                                <select id="cliente" class="form-control select2">

                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-md-6 col-xs-6">
                                                <span class="btn btn-success" id="consultaruna" style="margin-top:25px;padding-top:9px;">Consultar Estado</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-6 col-md-6 col-xs-6 text-right">
                                                <span class="btn btn-default" id="consultartodas" style="margin-top:25px;padding-top:9px;">Mostrar Todas</span>
                                            </div>



                                        </div><!-- /TAB 1 CONTENT -->
                                    </div>
                                    <!-- /tabs content -->

                                </div>
                                <!-- /panel content -->

                            </div>
                            <!-- /PANEL -->

                        </div>

                    </div>

					<div class="row">

						<div class="col-md-12">

							<div id="panel-2" class="panel panel-default">
								<div class="panel-heading">
									<span class="title elipsis">
										<strong>ESTADO DE LA RED</strong> <!-- panel title -->
									</span>

									<!-- tabs nav -->
									<ul class="nav nav-tabs pull-right">
										<li class="active"><!-- TAB 1 -->
											<a href="#ttab1_nobg" data-toggle="tab">Onts</a>
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
                                                            <th>Pon / Cliente</th>
                                                            <th>Marca</th>
															<th>Tmpº</th>
															<th>Rx dBm</th>
															<th>Tx dBm</th>
															<th>Rx Cabecera dBm</th>
															<th>Tensión V</th>
														</tr>
													</thead>
													<tbody id="aqui_las_ont">

													</tbody>
												</table>

                                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-right">
                                                    <span class="btn btn-default" onclick="limpiar();" style="margin-top:25px;padding-top:9px;">Limpiar</span>
                                                </div>


											</div>

										</div><!-- /TAB 1 CONTENT -->

									</div>
									<!-- /tabs content -->

								</div>
								<!-- /panel content -->

							</div>
							<!-- /PANEL -->
					
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
		
		<!-- PAGE LEVEL SCRIPT -->
		<script type="text/javascript">
            // la informacion de las ont consultadas se van cargando en un grid
            // esta funcion limpia la lista
            function limpiar(){
                $('#aqui_las_ont').empty();
            }

            // carga el listado de ont y clientes que se encuentran en la tabla aprovisionados
            // cuando se selecciona una se puede consultar el estado de la misma
            function cargar_info() {
                $('#cliente').empty();
                $('#cliente').append("<option value='0' selected disabled>Seleccione uno</option>");

                $.ajax({
                    url: 'carga_provision.php',
                    type: 'POST',
                    cache: false,
                    async: true,
                    success: function (datos) {
                        if(datos.length>0) {
                            for (var x = 0; x < datos.length; x++) {
                                $('#cliente').append("<option value='"+datos[x].prov_id+"'>"+ datos[x].num_pon +" - " + datos[x].cli_apellidos +"  " + datos[x].cli_nombre +", " + datos[x].fecha +"</option>");
                            }
                        }
                    }

                });
            }

            $(document).ready(function () {
                trabajando();
                cargar_info();
            });

            // funcion que se llama cuando se ha seleccionado la ont y se ha pulsado el boton consultar

            $("#consultaruna").bind('click', function () {
                trabajando(1);
                var id = $("#cliente").val();
                $.ajax({
                    url: 'ont_info.php',
                    type: 'POST',
                    cache: false,
                    async: true,
                    data: {id: id},
                    success: function (datos) {
                        trabajando(0);
                        if(datos.length>0) {
                            for (var x = 0; x < datos.length; x++) {
                                if(datos[x].error==1){
                                    trabajando(0);
                                    alert("No se puede mostrar la información")
                                } else {
                                    var linea = "<tr><td>" +  $('#cliente option:selected').text().substr(0,$('#cliente option:selected').text().lastIndexOf(',')); + "</td>";

                                    linea = linea + "<td>" + datos[x].marca + "</td>";

                                    if (parseFloat(datos[x].temp) > 60) {
                                        linea = linea + "<td class='btn-danger'>" + datos[x].temp + "</td>";
                                    } else if (parseFloat(datos[x].temp) > 50) {
                                        linea = linea + "<td class='btn-warning'>" + datos[x].temp + "</td>";
                                    } else {
                                        linea = linea + "<td class='' >" + datos[x].temp + "</td>";
                                    }

                                    if (parseFloat(datos[x].rx) < -27) {
                                        linea = linea + "<td class='btn-danger' >" + datos[x].rx + "</td>";
                                    } else if (parseFloat(datos[x].rx) < -25) {
                                        linea = linea + "<td class='btn-warning' >" + datos[x].rx + "</td>";
                                    } else {
                                        linea = linea + "<td class='' >" + datos[x].rx + "</td>";
                                    }

                                    if (parseFloat(datos[x].tx) < -27) {
                                        linea = linea + "<td class='btn-danger' >" + datos[x].tx + "</td>";
                                    } else if (parseFloat(datos[x].tx) < -25) {
                                        linea = linea + "<td class='btn-warning' >" + datos[x].tx + "</td>";
                                    } else {
                                        linea = linea + "<td class='' >" + datos[x].tx + "</td>";
                                    }

                                    linea = linea + "<td class='' >" + datos[x].rx_olt + "</td>";
                                    linea = linea + "<td class='' >" + datos[x].volt + "</td></tr>";


                                    $("#aqui_las_ont").append(linea);
                                }
                            }
                        }
                    }
                });
            });


            // <th>Marca</th>
            // <th>Tmpº</th>
            // <th>Rx dBm</th>
            // <th>Tx dBm</th>
            // <th>Rx Cabecera</th>
            // <th>Tensión</th>

            function ver_estado_ont(id){
                alert(id);
            }
        </script>
    </body>
</html>