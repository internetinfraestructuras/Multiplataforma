<?php

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();


?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Manual de usuario</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

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

</head>
<!--
    .boxed = boxed version
-->
<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">
        <!-- cargo menu lateral izquierdo, está declarado una sola vez para mejorar su mantenimiento-->
        <?php require_once ('menu-izquierdo.php'); ?>
        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- cargo menu superior, está declarado una sola vez para mejorar su mantenimiento-->
    <?php require_once ('menu-superior.php'); ?>

    <section id="middle">


        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Ayuda</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Novedades de AT Control</strong>
                        </div>

                        <div class="panel-body">
                            <b>17.08.218</b><br>
                            <p><b>Se recomienda:</b> Antes de retirar una ONT de una instalación por bajas o sustitución, dar de baja desde la plataforma desde el menu Aprovisionamiento->Dar Bajas. De este modo la ONT se resetea a fábrica y se realizan todas las bajas de todos los servidores correspondientes para su posterior y correcto aprovisionamiento.</p>
                            <b>11.07.218</b><br>
                            <p>Lanzamiento de <b><a href="http://www.configuraturouter.com" target="_blank">www.configuraturouter.com</a></b>, esta plataforma esta diseñada para que puedas ofrecer a tus clientes una forma fácil de configurar los parametros básicos del router de fibra.<br>
                            Ahora puedes ofrecer a tus clientes la herramienta perfecta de configuración a la que pueden acceder utilizando los datos que aparecen en la etiqueta que tienen las ont modelo IPTVM en la parte inferior.</p>
                            <b>10.07.218</b>
                            <p>Ahora puedes configurar los parametros de los routers desde la plataforma de AT Control. En la pantalla principal haz clic sobre el icono de configurar(<i class="main-icon fa fa-cogs"></i>) y accederás a la pantalla de configuración donde podrás configurar muchos de los parametros del router seleccionado. Recuerda que esta opción solo esta dispoible para las ont modelo IPTVM</p>
                            <b>02.07.218</b>
                            <p>Hemos incluido en la pantalla principal dos iconos que informan sobre la causa por la que la ont fué desconectada la última vez. Las causas posibles son: (<img src="img/dying.png" alt="" style="height:17px;" class="">)
                                Falta de alimentación electrica o (<img src="img/loss.png" alt="" style="height:22px; margin-up:-8px" class="blink">) Falta de señal óptica</p>
<!--                            <iframe src="manual.pdf" style="width:100%; height:80vh;" frameborder="0"></iframe>-->


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
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>





</body>
</html>