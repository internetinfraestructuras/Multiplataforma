<?php

if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(2);

if ($_SESSION['USER_LEVEL'] == 0) {
    $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
} else {
    $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
}

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_OLT; ?> / Consultas</title>
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
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_OLT; ?></a></li>
                <li class="active">Consultas</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Consultar IP & SERIAL</strong>
                        </div>

                        <div class="panel-body">
                            <fieldset>
                                <!-- required [php action request] -->
                                <input type="hidden" name="action" value="cabeceras"/>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-3">
                                            <label>Seleccionar Cabecera</label>
                                            <select id="cabecera" class="form-control" onchange="select_cabecera(this.value)">
                                                <option value='0' disabled selected>Seleccionar una</option>"

                                                <?php
                                                $c = 0;

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
                                        <div class="col-md-3 col-sm-3">
                                            <label>PON *</label>
                                            <input type="text" name="pon" id="pon" class="form-control required" placeholder="4857544300FFDC9B">
                                        </div>

                                        <div class="col-md-3 col-sm-3">

                                            <label><br></label>
                                            <button type="button" onclick="verinfo()"
                                                    class="btn btn-3d btn-success  btn-block">
                                                OBTENER INFORMACIÓN
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row">
                                <br>
                                <div class="col-xs-1"></div>
                                <div class="col-xs-3">
                                    <b>Ip:</b><br><br>
                                    <span id="ip"></span>
                                </div>
                                <div class="col-xs-3">
                                    <b>Mac:</b><br><br>
                                    <span id="mac"></span>
                                </div>
                                <div class="col-xs-3">
                                    <b>Serial:</b><br><br>
                                    <span id="serial"></span>
                                </div>
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
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>


<script>
    trabajando();

    function verinfo(){
        var olt = $("#cabecera").val();

        if(olt==0 || olt==null){
            alert("Selecciona una Cabecera");
            return;
        }
        var pon = $("#pon").val();
        if(pon=='' || pon==null){
            alert("Teclea un número PON");
            return;
        }
        trabajando(1);
        $.ajax({
            url: 'obtener_ip.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {olt: olt, pon:pon},
            success: function (data) {
                trabajando(0);
                $("#mac").text(data[0].mac);
                $("#ip").text(data[0].ip);
            }
        });
    }


</script>



</body>
</html>