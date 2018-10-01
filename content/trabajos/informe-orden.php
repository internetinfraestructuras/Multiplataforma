<?php
ini_set('display_errors', 1);
error_reporting('E_ALL');

if (!isset($_SESSION)) {
    @session_start();
}
// ficheros necesarios
require_once('../../config/define.php');   // definicion de variables
require_once('../../config/util.php');     // herramientas y utilidades

if (intval($_SESSION['USER_LEVEL']) > 1)
    header("Location:../instalador/instalador.php");

// se encarga de comprobar que el usuario tiene nivel suficiente para visualizar esta pagina
// 0 admin, 1 revendedor, 2 operador, 3 instalador, 4 todos
check_session(2);
$util = new util();


// si el usuario es root cargará siempre los datos de todos
if ($_SESSION['USER_LEVEL'] == 0) {
    $cabeceras = $util->selectWhere('fibra.olts', array('id', 'descripcion'), '', 'descripcion');
} else {    // si no es root cargara solo los datos de este usuario y todos los que pertenezcan al mismo revendedor
    $cabeceras = $util->selectWhere('fibra.olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
}
$root = "../../";
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> Admin</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="<?php echo $root; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="<?php echo $root; ?>assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $root; ?>assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $root; ?>assets/css/color_scheme/green.css" rel="stylesheet" type="text/css"
          id="color_scheme"/>

    <!-- JQGRID TABLE -->
    <link href="<?php echo $root; ?>assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $root; ?>assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css"/>

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
        <?php require_once('../../menu-izquierdo.php'); ?>
        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- cargo menu superior, está declarado una sola vez para mejorar su mantenimiento-->
    <?php require_once('../../menu-superior.php'); ?>

    <section id="middle" style="margin-top:60px;">
        <div id="content" class="dashboard padding-20">

            <div class="row">
                <div class="col-md-12">

                    <div id="panel-2" class="panel panel-default">
                        <div class="panel-heading">
                            <span class="title elipsis">
                                <strong>INFORME DE ESTADO DE UNA ORDEN DE TRABAJO</strong> <!-- panel title -->
                            </span>
                        </div>

                        <!-- panel content -->
                        <div class="panel-body" style="min-height:400px">

                            <!-- tabs content -->
                            <div class="tab-content transparent">

                                <div id="porta_curso" class="tab-pane active"><!-- TAB 1 CONTENT -->

                                    <div class="table-responsive"  style="min-height:380px; overflow-y: hidden; overflow-x: hidden">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th>FECHA ACTUACIÓN</th>
                                                <th>CLIENTE</th>
                                                <th>SERVICIO</th>
                                                <th>ESTADO</th>
                                                <th>TECNICO</th>
                                                <th>RESULTADO</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    include_once('../../clases/Orden.php');
                                                    $orden=new Orden();
                                                    $lineas = $orden->getLineasOrdenCerradasEstados($_GET['id']);
                                                    while ($linea = mysqli_fetch_array($lineas)) {
                                                       echo "<tr>";
                                                       echo "<td>".$linea[0]."</td>";
                                                       echo "<td>".$linea[1]."</td>";
                                                       echo "<td>".$linea[2]."</td>";
                                                       echo "<td>".$linea[3]."</td>";
                                                       echo "<td>".$linea[4]."</td>";
                                                       echo "<td>".$linea[5]."</td>";
                                                       echo "</tr>";

                                                    }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div><!-- /TAB 1 CONTENT -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '<?php echo $root;?>assets/plugins/';</script>
<!-- jQuery library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<script type="text/javascript" src="<?php echo $root; ?>assets/js/app.js"></script>
<script type="text/javascript" src="<?php echo $root; ?>js/utiles.js"></script>


<!-- PAGE LEVEL SCRIPT -->
<script type="text/javascript">



    $(document).ready(function () {

    });




</script>
</body>
</html>