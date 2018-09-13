<?php
ini_set('display_errors',0);
error_reporting('E_ALL');

if (!isset($_SESSION)) {@session_start();}
// ficheros necesarios
require_once(__DIR__ . '/config/define.php');   // definicion de variables
require_once(__DIR__ . '/config/util.php');     // herramientas y utilidades

if(intval($_SESSION['USER_LEVEL'])>1)
    header("Location:content/instalador/instalador.php");

// se encarga de comprobar que el usuario tiene nivel suficiente para visualizar esta pagina
// 0 admin, 1 revendedor, 2 operador, 3 instalador, 4 todos
check_session(2);
$util= new util();


// si el usuario es root cargará siempre los datos de todos
if ($_SESSION['USER_LEVEL'] == 0) {
    $cabeceras = $util->selectWhere('fibra.olts', array('id', 'descripcion'), '', 'descripcion');
} else {    // si no es root cargara solo los datos de este usuario y todos los que pertenezcan al mismo revendedor
    $cabeceras = $util->selectWhere('fibra.olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
}
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
                    <!-- primera fila de cajas de información-->
                    <!-- portabilidades y ordenes de trabajo  -->
                    <div class="row">
                        <div class="col-md-6">

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading" >
									<span class="title elipsis">
										<strong>Portabilidades</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#porta_curso" data-toggle="tab">En Curso</a>
                                        </li>
                                        <li class=""><!-- TAB 2 -->
                                            <a href="#porta_completas" data-toggle="tab">Completadas</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body" style="height:400px">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="porta_curso" class="tab-pane active" ><!-- TAB 1 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $estados = array("SOLICITADA","TRAMITADA","PROCESANDO","RECHAZADA","ACEPTADA","CONTRATADA");
                                                        $estadosColores = array("label label-primary","label label-info","label label-warning","label label-danger","label label-success","label label-default");
                                                        $portas = $util->selectJoin("portabilidades",
                                                        array('FECHA_SOLICITUD', 'NOMBRE_TITULAR',  'NUMERO_PORTAR','clientes.NOMBRE', 'clientes.APELLIDOS','clientes.EMAIL','portabilidades.ESTADO','portabilidades.ID'),
                                                        " left JOIN clientes ON clientes.ID=portabilidades.ID_CLIENTE", 'FECHA_SOLICITUD','ESTADO != 6 AND portabilidades.ID_EMPRESA='.$_SESSION['USER_ID']);

                                                        while ($row = mysqli_fetch_array( $portas)) {
                                                            echo '
                                                                <tr >
                                                                    <td><span class="'.$estadosColores[intval($row[6])-1].'">'.$estados[intval($row[6])-1].'</span></td >
                                                                    <td >'.$row[0].'</td >
                                                                    <td >'.$row[3].' '.$row[4].'</td >
                                                                    <td >'.$row[2].'</td >
                                                                    <td ><span class="btn btn-default btn-xs btn-block" onclick="ver_mas('.$row[7].');" > Más</span ></td >
                                                                </tr >';
                                                    }
                                                    ?>


                                                    </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    Ver todo
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="porta_completas" class="tab-pane"><!-- TAB 2 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </tr>
                                                    </thead>
                                                   <tbody>
                                                   <?php
                                                   $estados = array("SOLICITADA","TRAMITADA","PROCESANDO","RECHAZADA","ACEPTADA","CONTRATADA");
                                                   $estadosColores = array("label label-primary","label label-info","label label-warning","label label-danger","label label-success","label label-default");
                                                   $portas = $util->selectJoin("portabilidades",
                                                       array('FECHA_SOLICITUD', 'NOMBRE_TITULAR',  'NUMERO_PORTAR','clientes.NOMBRE', 'clientes.APELLIDOS','clientes.EMAIL','portabilidades.ESTADO','portabilidades.ID'),
                                                       " left JOIN clientes ON clientes.ID=portabilidades.ID_CLIENTE", 'FECHA_SOLICITUD','ESTADO = 6 AND portabilidades.ID_EMPRESA='.$_SESSION['USER_ID']);

                                                   while ($row = mysqli_fetch_array( $portas)) {
                                                       echo '
                                                                <tr >
                                                                    <td><span class="'.$estadosColores[intval($row[6])-1].'">'.$estados[intval($row[6])-1].'</span></td >
                                                                    <td >'.$row[0].'</td >
                                                                    <td >'.$row[3].' '.$row[4].'</td >
                                                                    <td >'.$row[2].'</td >
                                                                    <td ><span class="btn btn-default btn-xs btn-block" onclick="ver_mas('.$row[7].');" > Más</span ></td >
                                                                </tr >';
                                                   }
                                                   ?>
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

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading">
									<span class="title elipsis">
										<strong>Ordenes de trabajo</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#orden_curso" data-toggle="tab">En Curso</a>
                                        </li>
                                        <li class=""><!-- TAB 2 -->
                                            <a href="#orden_completa" data-toggle="tab">completados</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body" style="height:400px">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="orden_curso" class="tab-pane active"><!-- TAB 1 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        require_once ('clases/Orden.php');
                                                        $ordenes = new Orden();
                                                        print_r($ordenes->getOrdenesEstados());
                                                    ?>


                                                    </tbody>
                                                </table>


                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    Ver todo
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="orden_completa" class="tab-pane"><!-- TAB 2 CONTENT -->

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
                                                    aqui los completados
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
                    </div>

                    <!-- segunda fila de cajas de información-->
                    <!-- estado ont y estadisticas instalaciones  -->
                    <div class="row">
                        <div class="col-md-6">

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading" >
									<span class="title elipsis">
										<strong>Portabilidades</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#ttab1_nobg" data-toggle="tab">En Curso</a>
                                        </li>
                                        <li class=""><!-- TAB 2 -->
                                            <a href="#ttab2_nobg" data-toggle="tab">Completadas</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body" style="height:400px">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="ttab1_nobg" class="tab-pane active" ><!-- TAB 1 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>

                                                <a class="size-12" href="#">
                                                    <i class="fa fa-arrow-right text-muted"></i>
                                                    Ver todo
                                                </a>

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="ttab2_nobg" class="tab-pane"><!-- TAB 2 CONTENT -->

                                            <div class="table-responsive" style="height:380px; overflow-y: scroll; overflow-x: hidden">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Número</th>
                                                        <th></th>
                                                    </tr>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

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

                            <div id="panel-2" class="panel panel-default">
                                <div class="panel-heading">
									<span class="title elipsis">
										<strong>INSTALACIONES REALIZADAS</strong> <!-- panel title -->
									</span>

                                    <!-- tabs nav -->
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><!-- TAB 1 -->
                                            <a href="#inst_sema" data-toggle="tab">Semanales</a>
                                        </li>
                                        <li class=""><!-- TAB 1 -->
                                            <a href="#inst_mes" data-toggle="tab">Mensuales</a>
                                        </li>
                                    </ul>
                                    <!-- /tabs nav -->


                                </div>

                                <!-- panel content -->
                                <div class="panel-body" style="height:400px">

                                    <!-- tabs content -->
                                    <div class="tab-content transparent">

                                        <div id="inst_sema" class="tab-pane active"><!-- TAB 1 CONTENT -->
                                            <div class="row">
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Cabecera</label>
                                                    <select id="cabecera" name="o" class="form-control">
                                                        <option value='0' selected>Todas agrupadas</option>
                                                        <option value='-1' >Todas separadas</option>
                                                        <?php
                                                        $c = 0;
                                                        // carga lista de cabeceras en el combo para poder filtrar los datos
                                                        while ($row = mysqli_fetch_array($cabeceras)) {
                                                            if ($c == 0) {
                                                                $ultimo = $row;
                                                                $c = 1;
                                                            }
                                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Desde: </label><br>
                                                    <input type="date" id="desde" name="desde" class="form-control">
                                                </div>
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Hasta: </label><br>
                                                    <input type="date" id="hasta" name="hasta" class="form-control">
                                                </div>

                                                <div class="col-xs-12 col-lg-3">
                                                    <br>
                                                    <input type="submit" class="btn btn-primary" value="Consultar">
                                                </div>

                                            </div>
                                            <div id="graph-normal-bar">

                                            </div>

                                        </div><!-- /TAB 1 CONTENT -->

                                        <div id="inst_mes" class="tab-pane"><!-- TAB 1 CONTENT -->
                                            <div class="row">
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Cabecera</label>
                                                    <select id="cabecera" name="o" class="form-control">
                                                        <option value='0' selected>Todas agrupadas</option>
                                                        <option value='-1' >Todas separadas</option>
                                                        <?php
                                                        $c = 0;
                                                        // carga lista de cabeceras en el combo para poder filtrar los datos
                                                        while ($row = mysqli_fetch_array($cabeceras)) {
                                                            if ($c == 0) {
                                                                $ultimo = $row;
                                                                $c = 1;
                                                            }
                                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Desde: </label><br>
                                                    <input type="date" id="desde" name="desde" class="form-control">
                                                </div>
                                                <div class="col-xs-12 col-lg-3">
                                                    <label>Hasta: </label><br>
                                                    <input type="date" id="hasta" name="hasta" class="form-control">
                                                </div>

                                                <div class="col-xs-12 col-lg-3">
                                                    <br>
                                                    <input type="submit" class="btn btn-primary" value="Consultar">
                                                </div>

                                            </div>
                                            <div class="panel-body nopadding">

                                                <div id="graph-donut"><!-- GRAPH CONTAINER --></div>

                                            </div>
                                        </div><!-- /TAB 1 CONTENT -->
                                    </div>
                                    <!-- /tabs content -->

                                </div>
                                <!-- /panel content -->

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
            function ver_mas(id) {
                alert(id);
            }

            var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            $(document).ready(function () {
                // $('#desde').val(new Date().toDateInputValue());
                // $('#hasta').val(new Date().toDateInputValue());

                var olt = '<?php echo $olt;?>';

                if(olt=='')
                    olt = $("#cabecera").val();

                var desde = '<?php echo $desde;?>';
                var hasta = '<?php echo $hasta;?>';
                estadisticas(olt, desde, hasta);
            });

            Date.prototype.toDateInputValue = (function () {
                var local = new Date(this);
                local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                return local.toJSON().slice(0, 10);
            });

            function estadisticas(olt, desde, hasta){


                $.ajax({
                    url: 'php/carga_estadisticas.php',
                    type: 'POST',
                    cache: false,
                    cache: false,
                    async: true,
                    data: {
                        olt: olt,
                        desde: desde,
                        hasta: hasta
                    },
                    success: function (data) {

                        var datos=[];
                        var total=0;

                        for (var x = 0; x < data.length; x++) {

                            datos[x]={x: data[x]['mes'], y: parseInt(data[x]['cant'])}
                            total = total + parseInt(data[x]['cant']);
                        }
                        $("#total").text(total);
                        loadScript(plugin_path + "raphael-min.js", function(){
                            loadScript(plugin_path + "chart.morris/morris.min.js", function(){

                                if (jQuery('#graph-normal-bar').length > 0){

                                    Morris.Bar({
                                        element: 'graph-normal-bar',
                                        data: datos,
                                        xkey: 'x',
                                        ykeys: ['y'],
                                        labels: ['Instalaciones']
                                    });

                                }

                            });
                        });


                    }
                });

            }
        </script>
    </body>
</html>
