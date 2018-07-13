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

ini_set('display_errors', 1);
error_reporting('E_ALL');
$util = new util();
check_session(4);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - Configuración Router</title>
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
        label{
            font-weight: 700;
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
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#">Configuración CCR</a></li>
            </ol>
        </header>
        <!-- /page title -->
        <?php

        // creo un objeto de la clase util para poder realizar operaciones de consultas a base de datos
        $util = new util();

        // si el usuario es root cargará siempre los datos de todos
        if ($_SESSION['USER_LEVEL'] == 0) {
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
        } else {    // si no es root cargara solo los datos de este usuario y todos los que pertenezcan al mismo revendedor
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT revendedor FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
        }

        ?>

        <div id="content" class="padding-20">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Interfaz Inalámbrica (Wifi)</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Seleccionar Cabecera</label>
                            <select id="cabecera" class="form-control" onchange="select_cabecera(this.value)"  style="height:50px">
                                <option value='0' selected>Ver Todas</option> "

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
                        <br>

                        <div class="col-lg-2 col-xs-9">
                            <label>Ip Router </label>
                            <input type="text" name="iprouter" id="iprouter"  class="form-control masked" data-format="999.999.999.999" data-placeholder="_" placeholder="192.168.1.1"  style="height:50px">
                        </div>

                        <div class="col-lg-3 col-xs-9">
                            <label>SSID </label>
                            <input type="text" name="ssid" id="ssid" val="<?php echo $json[76];?>" maxlength="32" class="form-control " placeholder="Nombre de la wifi Max. 32 caracteres [aA09-_ ]" style="height:50px">
                        </div>
                        <?php echo $json[76]; ?>
                        <div class="col-lg-1 col-xs-3 text-center">
                            <label class="switch switch-success">
                                Ocultar SSID<br><br>
                                <input type="checkbox"><span class="switch-label " data-on="SI" data-off="NO"></span>
                            </label>

                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Tipo Seguridad </label><br>
                            <select id="tiposeguridad" name="tiposeguridad"  style="height:50px">
                                <option value="open">Open</option>
                                <option value="shared">Shared</option>
                                <option value="wpa-psk">WPA PreSharedKey</option>
                                <option value="wpa2-psk">WPA2 PreSharedKey</option>
                                <option value="wpa/wpa2-psk">WPA/WPA2 PreSharedKey</option>
                                <option value="wpa">WPA Enterprise</option>
                                <option value="wpa2">WPA2 Enterprise</option>
                                <option value="wpa/wpa2">WPA/WPA2 Enterprise
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-xs-12">
                            <label>Encriptación</label><br>
                            <select id="encriptacion" name="encriptacion" style="height:50px">
                                <option value="AESEncryption">AES</option>
                                <option value="TKIPEncryption">TKIP</option>
                                <option value="TKIPandAESEncryption">TKIP&amp;AES</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Clave Wifi </label>
                            <input type="password" name="wifi_pass1" id="wifi_pass1" val=""  class="form-control " placeholder="Mínimo 8 Caracteres" style="height:50px">
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Repetir</label>
                            <input type="password" name="wifi_pass2" id="wifi_pass2" val=""  class="form-control " placeholder="Repita Password" style="height:50px">
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Interfaz LAN (Equipos Cableados)</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-xs-12">
                            <label>Puertos RJ45</label><br>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="lan1">
                                <i></i> Lan 1
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="lan2">
                                <i></i> Lan 2
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="lan3">
                                <i></i> Lan 3
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="lan4">
                                <i></i> Lan 4
                            </label>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Ip Router </label><br>
                            <input type="text" name="ip_router" id="ip_router" val=""  class="form-control " placeholder="192.168.100.1" style="height:34px;width:140px"><br>
                            <label>Mascara </label><br>
                            <input type="text" name="mascara_router" id="mascara_router" val=""  class="form-control " placeholder="255.255.255.0" style="height:34px;width:140px"><br>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Opciones DHCP</label><br><br>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="dhcp">
                                <i></i> DHCP Server
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="relay">
                                <i></i> DHCP Relay
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="opt125">
                                <i></i> Option125
                            </label>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>Ip Inicio </label><br>
                            <input type="text" name="ip_ini" id="ip_ini" val=""  class="form-control " placeholder="192.168.100.2" style="height:34px;width:140px"><br>
                            <label>Ip Fin </label><br>
                            <input type="text" name="ip_fin" id="ip_fin" val=""  class="form-control " placeholder="192.168.100.254" style="height:34px;width:140px"><br>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label>DNS Primario </label><br>
                            <input type="text" name="dns1" id="dns1" val=""  class="form-control " placeholder="8.8.8.8" style="height:34px;width:140px"><br>
                            <label>DNS Secundario </label><br>
                            <input type="text" name="dns2" id="dns2" val=""  class="form-control " placeholder="8.8.8.4" style="height:34px;width:140px"><br>
                        </div>

                        <div class="col-lg-2 col-xs-12">
                            <label>Tiempo concesión </label><br>
                            <input type="number" name="tconces" id="tconces" val="" min="1" max="60" class="form-control " placeholder="1" style="height:34px;width:140px"><br>
                            <label>Valor</label><br>
                            <select id="vconces" name="vconces" style="height:34px;width:140px">
                               <option value="60">minutos</option>
                                <option value="3600">horas</option>
                                <option value="86400">días</option>
                                <option value="604800">semanas</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Interfaz WAN (Conexión a internet)</strong>
                </div>

                <div class="panel-body">
                    <label>Interfaz VLAN 1</label><br><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12">
                            <label>Puertos Activos</label><br>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="w1-lan1">
                                <i></i> Lan 1
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="w1-lan2">
                                <i></i> Lan 2
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="w1-lan3">
                                <i></i> Lan 3
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="w1-lan4">
                                <i></i> Lan 4
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="1" id="w1-ssid1">
                                <i></i> Wifi
                            </label>

                        </div>
                        <div class="col-lg-5">
                            <center>
                                <label>Enrutado</label><br>
                            </center>
                            <div class="col-lg-4 col-xs-12">
                                <label class="checkbox">
                                    <input type="checkbox" value="1" id="w1-wan">
                                    <i></i> Enable WAN
                                </label>
                                <br>

                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <label class="checkbox">
                                    <input type="checkbox" value="1" id="w1-vlan">
                                    <i></i> Enable VLAN
                                </label>
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <label class="checkbox">
                                    <input type="checkbox" value="1" id="w1-nat">
                                    <i></i> Enable NAT
                                </label>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <label>Interfaz PPPoE</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>

                    <label>Interfaz IpTv</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>

                    <label>Interfaz VozIp</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>



                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Otras Configuraciones</strong>
                </div>

                <div class="panel-body">
                    <label>Puertos</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>

                    <label>Interfaz PPPoE</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>

                    <label>Interfaz IpTv</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>

                    <label>Interfaz VozIp</label><br>

                    <div class="row">
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                        <div class="col-lg-2 col-xs-12"></div>
                    </div>



                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade modal-lg" id="encontradas" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="encontradas_content" style="padding:5%">
            <center>
                <img src="img/procesando.gif" id="img_procesando">
                <br><br>
                <h3>Esto puede tardar hasta un minuto</h3>
            </center>
        </div>
    </div>
</div>

<div class="modal fade" id="resultado_modal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="contenido_resultado" style="padding:5%">
        </div>
    </div>
</div>




    <!-- JAVASCRIPT FILES -->
    <script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
    <script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="assets/js/app.js"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.7.12/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>


    <script>


    </script>

</body>
</html>



