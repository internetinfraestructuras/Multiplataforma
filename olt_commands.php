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
    <title><?php echo OWNER; ?> <?php echo DEF_OLT; ?> / Comandos especiales en OLT</title>
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
                <li class="active">Comandos Especiales</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Ejecutar comandos directamente en la OLT</strong>
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

                                    </div>
                                </div>
                            </fieldset>
                            <br><br>
                            <div class="row" id="aquiloscomandos">
                                <table class="table table-striped">
                                <?php
                                    $result = $util->selectWhere('comandosespeciales',array('id','texto','omci'));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr><td width='35%'><b>$row[1]</b></td><td width='55%'>".crear_comando($row[0],$row[2])."</td><td width='10%'></td></tr>";
                                    }
                                ?>
                                </table>
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

<?php
function crear_comando($ncommand,$command){
    $util = new util();
    $return="";
    $c=1;
    $result2 = $util->selectWhere('parametros_x_comandoespecial',array('parametro'), 'id_comando='.$ncommand);

    while ($row2 = mysqli_fetch_array($result2)) {
        $return =  str_replace("<".$c.">","<input type='number' style='width:50px' name='".$c."' >",$command);
        $c++;
    }

    return $return;
}

?>

<script>
    trabajando();

    function verinfo(){
        var olt = $("#cabecera").val();

        trabajando(1);

    }

</script>



</body>
</html>