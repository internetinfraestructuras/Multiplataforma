<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------

//ini_set('display_errors',0);

if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
require_once('../../clases/Orden.php');
require_once "../../clases/telefonia/classTelefonia.php";

$telefonia = new Telefonia();
$util = new util();
$orden = new Orden();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);
$root = "../../";
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> Instalaciones</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>"/>

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
    <script src="../../assets/sweetalert/sweetalert2.all.min.js"></script>
    <script src="../../assets/sweetalert/funciones.js"></script>

    <link rel="stylesheet" href="../../assets/sweetalert/sweetalert2.min.css">


</head>
<!--
    .boxed = boxed version
-->
<body>

<!-- WRAPPER -->
<div id="wrapper" class="clearfix">



    <section id="">

        <?php require_once ($root.'menu-superior.php'); ?>
        <br><br><br>
        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Mostrar una orden</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">
            <form id="ordenar" action="<?php echo RUTA_ANTIGUA;?>api/pre_provision.php" method="post"
                  enctype="multipart/form-data">

                <input type="hidden" name="act_internet" value="false">
                <input type="hidden" name="act_voz" value="false">
                <input type="hidden" name="act_tv" value="false">
                <div class="row">
                    <?php
                    $listado = $orden->getOrden($_GET['id']);

                    $olt = $orden->getOlts();
                    $olts='';
                    foreach ($olt as $item) {
                        $olts = $olts . $item[0] . ",";
                    }

                    $parametros = array();


                    foreach ($listado as $linea){
                    ?>
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body" id="listado">
                                <div id="panel-1" class="panel panel-default">
                                    <div class="panel-heading">
                                    <span class="title elipsis h2">
                                        <strong><?php echo $linea['nombre'] . " " . $linea['apellidos']; ?></strong> /
                                    </span>

                                        <span class="title elipsis h2">
                                        <b>Orden ID:
                                            <?php
                                            echo $_GET['id'];
                                            echo '<input type = "hidden" name= "orden" value ="' . $_GET['id'] . '">';
                                            echo '<input type = "hidden" name= "IDCLIENTE" value ="' . $linea['idcliente'] . '">';
                                            echo '<input type = "hidden" name= "olts" value ="' . $olts . '">';
                                            echo '<input type = "hidden" name= "claveapi" value ="' . CLAVE_API . '">';
                                            echo '<input type = "hidden" name= "userlevel" value ="' . $_SESSION['USER_LEVEL'] . '">';
                                            echo '<input type = "hidden" name= "userid" value ="' . $_SESSION['USER_ID'] . '">';
                                            echo '<input type = "hidden" name= "revendedor" value ="' . $_SESSION['REVENDEDOR'] . '">';
                                            echo '<input type = "hidden" name= "descripcion" value ="' . $linea['nombre'] . " " . $linea['apellidos'] . '">';
                                            ?>
                                        </b>
                                    </span>
                                    </div>

                                    <div class="panel-footer">
                                        <div class="row">
                                            <?php

                                            $lineas = $orden->getLineasOrden($linea[0]);


                                            foreach ($lineas as $lineaD) {
//                                                var_dump($lineaD);
                                                echo "<div class='row' style='border-bottom:1px solid #c9c9c9'>";
                                                echo "<div class='col-xs-2 col-md-1'>";
                                                echo "<img src='../../img/serv" . $lineaD['servicio'] . ".png'>";

                                                if (intval($lineaD['servicio']) == 1)
                                                    echo '<input type = "hidden" name= "act_internet"  value="true">';
                                                if (intval($lineaD['servicio']) == 2)
                                                    echo '<input type = "hidden" name= "act_voz"  value="true">';
                                                if (intval($lineaD['servicio']) == 4)
                                                    echo '<input type = "hidden" name= "act_tv"  value="true">';


                                                echo "</div>";
                                                echo "<div class='hidden-xs col-md-2'><b>Servicio:</b><br><span class='datos'>";
                                                echo $lineaD['tipo'];
                                                echo "</span></div>";
                                                echo "<div class='col-xs-4 col-md-2'><b>Modelo:</b><br><span class='datos'>";
                                                echo $lineaD['modelo'];
                                                echo "</span></div>";
                                                echo "<div class='col-xs-6 col-md-2 col-lg-3'><b>Serial:</b><br><span class='datos'>";
                                                echo $lineaD['serial'];
                                                echo "</span></div>";
                                                echo "<div class='col-xs-12 col-md-2  '><b>Configuración:</b><br>";
                                                $config = $orden->getLineasOrdenDetalles($lineaD['ID_LINEA_DETALLE_CONTRATO']);
                                                foreach ($config as $valor) {
                                                    echo "<span style='font-size:1em;'><b>" . $valor['NOMBRE'] . ": </b>";
                                                    if ($valor['NOMBRE'] == 'PAQUETE DESTINO')
                                                        echo "<br>" . $telefonia->getPaqueteNombre($valor['VALOR']);
                                                    else
                                                        echo $valor['VALOR'];

                                                    if ($valor['NOMBRE'] == 'BAJADA') {
                                                        echo " Mb ";
                                                        echo '<input type = "hidden" name= "BAJADA" value ="' . $valor['VALOR'] . '">';
                                                    }
                                                    if ($valor['NOMBRE'] == 'SUBIDA') {
                                                        echo " Mb ";
                                                        echo '<input type = "hidden" name= "SUBIDA" value ="' . $valor['VALOR'] . '">';
                                                    }
                                                    if ($valor['NOMBRE'] == 'DATOS') {
                                                        echo " Mb ";
                                                    }
                                                    echo "</span><br>";

                                                    // si el servicio es telefonia fija, tomo el numero de telefono
                                                    if (intval($valor['ID_ATRIBUTO_SERVICIO']) == ATRIBUTO_TELEFONO_FIJO) {
                                                        echo '<input type = "hidden" name= "fijo" value ="' . $valor['VALOR'].'">';
                                                    }

                                                    // si el servicio es telefonia MOVIL, tomo el numero de telefono
                                                    if (intval($valor['ID_ATRIBUTO_SERVICIO']) == ATRIBUTO_TELEFONO_MOVIL) {
                                                        $numMovil = $valor['VALOR'];
                                                    }


                                                }

                                                echo "</div>";
                                                echo "<div class='col-xs-12 col-md-3 col-lg-2'>";
                                                if (intval($lineaD['servicio']) == ID_SERVICIO_INTERNET) {
//                                                    if (intval($lineaD['DETALLE'])==)
                                                    echo '<input type = "hidden" name= "pon" value ="' . $lineaD['pon'] . '">';
                                                    echo '<input type = "hidden" name= "serial" value ="' . $lineaD['serial'] . '">';
                                                    echo '
                                                <br class=\'visible-xs\'>
                                                <div class="btn-group">
                                                    <button type="button" onclick="revisar(this,' . $lineaD['ID'] . ')" class="btn btn-warning">Aprovisionar</button>
                                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Opciones Disponibles</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#"><i class="fa fa-edit"></i> Verificar Estado</a></li>
                                                        <li><a href="#"><i class="fa fa-recycle"></i> Reaprovisionar</a></li>
                                                        <li><a href="#"><i class="fa fa-question-circle"></i> Abrir Incidencia</a></li>
                                                    </ul>
                                                </div>
                                                <br class=\'visible-xs\'><br class=\'visible-xs\'>
                                            ';


                                                } if (intval($lineaD['servicio']) == ID_SERVICIO_IPTV) {
                                                    echo '
                                                        <br class=\'visible-xs\'>
                                                        <div class="btn-group" >
                                                            <button type="button" onclick="herramientas(' . $lineaD['ID'] . ')" class="btn btn-primary">Herramientas</button>
                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                                <span class="sr-only">Opciones Disponibles</span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="#"><i class="fa fa-edit"></i> Verificar Estado</a></li>
                                                                <li><a href="#"><i class="fa fa-recycle"></i> Reaprovisionar</a></li>
                                                                <li><a href="#"><i class="fa fa-question-circle"></i> Abrir Incidencia</a></li>
                                                            </ul>
                                                        </div>
                                                        <br class=\'visible-xs\'><br class=\'visible-xs\'>
                                                    ';
                                                }  if (intval($lineaD['servicio']) == ID_SERVICIO_MOVIL) {
                                                    if(intval($numMovil)>0) {
                                                        $llamadaTest = $telefonia->verificarInstalacionLineaMovil($numMovil);
                                                        if ($llamadaTest == '')
                                                            echo '
                                                            <br class=\'visible-xs\'>
                                                            <div class="btn-group" >
                                                                <button type="button" class="btn btn-primary">Llamar ' . NUMERO_LLAMAR_VERIFICAR_MOVIL . '</button>
                                                            </div>
                                                            <br class=\'visible-xs\'><br class=\'visible-xs\'>
                                                        ';
                                                        else
                                                            echo '
                                                            <br class=\'visible-xs\'>
                                                            <div class="btn-group" >
                                                                <button type="button" class="btn btn-success">Activado ' . $util->fecha_eur($llamadaTest) . '</button>
                                                            </div>
                                                            <br class=\'visible-xs\'><br class=\'visible-xs\'>
                                                        ';
                                                    } else {
                                                        $textoBoton = "Esperando Número";
                                                        echo '
                                                            <br class=\'visible-xs\'>
                                                            <div class="btn-group" >
                                                                <button type="button" class="btn btn-info">' . $textoBoton . '</button>
                                                            </div>
                                                            <br class=\'visible-xs\'><br class=\'visible-xs\'>
                                                        ';
                                                    }

                                                }

                                                echo "</div>";


                                                echo "</div><br>";
                                            }
                                            ?>
                                            <div class="col-xs-12 visible-xs">
                                                <br>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
            </form>
            <?php }
            ?>
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
    var boton;

    function revisar(b, id) {
        boton=b;
        $(boton).attr("disabled", true);
        boton.textContent = "Espere...";
        alerta("Aprovisionar","Asegurese de que la ONT esta conectada y encendida","info","Continuar","Cancelar", "aprovisionar","cancelar")
    }

    function aprovisionar() {

        $("#ordenar").submit();

    }

    function cancelar() {

        $(boton).attr("disabled", false);
        boton.textContent = "Aprovisionar";

    }


</script>


</body>
</html>