<?php
//*****************************************************************************
// se encarga de mostrar la interfaz donde los usuarios root pueden agregar
// cabeceras al sistema. Estas cabeceras contienen los datos de Ip, usuario,
// clave y revendedor al que pertence. tras su creación se lanza los script
// que cargar la configuracion base que esta recogida en la tabla: comandosiniciales
// esta operacion la realiza el fichero php/guardar-olts.php tras recibir los datos
// del formulario y guardar el registro en la tabla olts
//*****************************************************************************
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(0);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_OLT; ?> / Altas</title>
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

        <?php
            require_once ('menu-superior.php');
            $campos=array('revendedores.id','empresa','municipios.municipio');
            $result = $util->selectJoin("revendedores", $campos,"JOIN municipios ON municipios.id = revendedores.localidad ","empresa");
        ?>

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
                <li class="active">Agregar</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>ALTAS <?php echo strtoupper(DEF_OLT); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="php/guardar-olts.php" method="post"
                                  enctype="multipart/form-data" id="formulario" >
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="cabeceras"/>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label><?php echo DEF_REVENDEDOR; ?> </label>
                                                <select id="revendedor" name="cabeceras[revendedor]" class="form-control required">
                                                    <option value="0" selected>Administrador</option>

                                                    <?php
                                                    $c = 0;
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($c == 0) {
                                                            $ultimo = $row;
                                                            $c = 1;
                                                        }
                                                        echo "<option value='". $row['id']."'>" . $row['empresa'] . " / " . $row['municipio'] . "</option>";
                                                    }

                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4 col-sm-4">
                                                <label>Ip *</label>
                                                <input type="text" name="cabeceras[ip]" class="form-control required" placeholder="192.168.1.1">
                                            </div>

                                            <div class="col-md-4 col-sm-4">
                                                <label>Descripcion *</label>
                                                <input type="text" name="cabeceras[descripcion]" value=""
                                                       class="form-control required" placeholder="Puerto Serrano">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-4">
                                                <label>Marca </label>
                                                <input type="text" name="cabeceras[marca]" value=""
                                                       class="form-control " placeholder="Huawei">
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Modelo </label>
                                                <input type="text" name="cabeceras[modelo]" value=""
                                                       class="form-control " placeholder="MA5608T">
                                            </div>

                                            <div class="col-md-3 col-sm-4">
                                                <label>Usuario *</label>
                                                <input type="text" name="cabeceras[user]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Clave *</label>
                                                <input type="password" name="cabeceras[pass]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <center><b>Datos Técnicos</b></center>
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4">
                                                <label>Nº. Chasis *</label>
                                                <input type="number" name="cabeceras[c]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Nº. Tarjetas *</label>
                                                <input type="number" name="cabeceras[t]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Nº. Pons *</label>
                                                <input type="number" name="cabeceras[p]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Ont Id Desde *</label>
                                                <input type="number" name="cabeceras[idini]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Service Port desde *</label>
                                                <input type="number" name="cabeceras[serviceport]" value="" placeholder=""
                                                       class="form-control required">
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row">
                                        <center><b>Datos CCR / Router</b></center>
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4">
                                                <label>IP*</label>
                                                <input type="text" name="ipccr" id="n" value=""
                                                       class="form-control required" placeholder="10.200.50.1">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Usuario Api*</label>
                                                <input type="text" name="userapi" id="o" value=""
                                                       class="form-control required" placeholder="usuarioapi">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Clave usuario*</label>
                                                <input type="text" name="claveapi" id="p" value=""
                                                       class="form-control required" placeholder="claveapi">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Usuario ONT</label>
                                                <input type="text" name="useront" id="q" value=""
                                                       class="form-control required" placeholder="Acceso web ont">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Clave ONT*</label>
                                                <input type="text" name="passont" id="r" value=""
                                                       class="form-control required" placeholder="Acceso web ont">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>SSID</label>
                                                <input type="text" name="ssid" id="s" value=""
                                                       class="form-control required" placeholder="Mi Wifi Fibra">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4">
                                                <label>Vlan ACS</label>
                                                <input type="text" name="vlanacs" id="t" value=""
                                                       class="form-control required" placeholder="200">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Lan IP</label>
                                                <input type="text" name="lanip" id="x" value=""
                                                       class="form-control required" placeholder="192.168.100.1">
                                            </div>

                                            <div class="col-md-2 col-sm-4">
                                                <label>Dhcp Start</label>
                                                <input type="text" name="dhcpini" id="u" value=""
                                                       class="form-control required" placeholder="192.168.100.2">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Dhcp End</label>
                                                <input type="text" name="dhcpfin" id="v" value=""
                                                       class="form-control required" placeholder="192.168.100.250">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label>Máscara</label>
                                                <input type="text" name="mascara" id="w" value=""
                                                       class="form-control required" placeholder="255.255.255.0">
                                            </div>

                                        </div>
                                    </div>

                                </fieldset>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" onclick="checkpass()"
                                                class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                            VALIDAR Y GUARDAR
                                            <span class="block font-lato">verifique que toda la información es correcta</span>
                                        </button>
                                    </div>
                                </div>

                            </form>

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

    function checkpass(){
        var pass1=$("#pass1").val();
        var pass2=$("#pass2").val();

        if(pass1=='' || pass2==''){
            _toastr("Teclee las dos contraseñas", "top-right","error",false);
            return;
        }

        if(pass1===pass2) {
            trabajando();
            trabajando(1);
            setTimeout(function () {
               location.reload();
            }, 25000);
            $("#formulario").submit();
        }
        else
            _toastr("Las contraseñas no coinciden", "top-right","error",false);

    }


</script>



</body>
</html>