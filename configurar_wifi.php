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

if (isset($_POST['serial']) && isset($_POST['password']) && $_POST['serial'] != '' && $_POST['password'] != '') {
    $u = $_POST['serial'];
    $p = $_POST['password'];
    $result = $util->selectWhere("etiquetas.series", array("pon"), "pathnumber = '" . $u . "' AND PON='" . $p . "'");
    $row = mysqli_fetch_array($result);
    if (intval($row[0]) != '') {
        $_SESSION['sesion_iniciada'] = $row[0];
        setcookie('usuario', $u, time() + (86400 * 30), "/");
        setcookie('clave', $p, time() + (86400 * 30), "/");

    } else {
        $login_fail = true;
    }

}
$aItems = array();
if (isset($_POST['guardando'])) {
    $n = 0;
    foreach ($_POST as $clave => $valor) {
        $c = str_replace('_1_', '.1.', $clave);
        $c = str_replace('_LAN', '.LAN', $c);
        $v = $valor;
//        echo "-".$v."-";


        if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_DHCPServerEnable') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DHCPServerEnable';
        if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_MinAddress') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MinAddress';
        if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_MaxAddress') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MaxAddress';
        if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_DNSServers') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers';
        if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS_Enable') {
            $c = 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS.Enable';
            if (trim($v) == 'on') $v = 'true'; else if (trim($v) == 'off') $v = 'false'; else if (trim($v) == '1') $v = 'true'; else if (trim($v) == '0') $v = 'false';
        }

        if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSIDAdvertisementEnabled') {
            if (trim($v) == 'on') $v = 'true'; else if (trim($v) == 'off') $v = 'false'; else if (trim($v) == '1') $v = 'true'; else if (trim($v) == '0') $v = 'false';
        }

        $n++;
        if ($clave == 'device')
            $id_device = $valor;
        else if ($clave == 'guardando') {

        } else
            if ($n == 12) {
                $aItem = array('p' => $c, 'v' => $v[0] . "," . $v[1], 't' => "xsd:string");
                array_push($aItems, $aItem);
            } else {
//                if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey') {
//                    if ($v != '') {
//
//                        $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey', 'v' => $v, 't' => "xsd:string");
//                        array_push($aItems, $aItem);
//                        $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.IEEE11iEncryptionModes', 'v' => $_POST['InternetGatewayDevice_LANDevice_1_WLANConfiguration_1_IEEE11iEncryptionModes'], 't' => "xsd:string");
//                        array_push($aItems, $aItem);
//                        $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.BeaconType', 'v' => $_POST['InternetGatewayDevice_LANDevice_1_WLANConfiguration_1_BeaconType'], 't' => "xsd:string");
//                        array_push($aItems, $aItem);
//                        $aItem = array('p' => $c, 'v' => $v, 't' => "xsd:string");
//                        array_push($aItems, $aItem);
//                    }
//                } else {
                    $aItem = array('p' => $c, 'v' => $v, 't' => "xsd:string");
                    array_push($aItems, $aItem);
//                    if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.IEEE11iEncryptionModes') {
//                        $aItem = array('p' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPAEncryptionModes', 'v' => $v, 't' => "xsd:string");
//                        array_push($aItems, $aItem);
//
//
//                    }
//                }
            }

    }

    $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?connection_request";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $aItems=asort($aItems);

    foreach ($aItems as $clave) {
        $payload = json_encode(array('name' => 'setParameterValues', 'parameterValues' => [[$clave['p'], $clave['v'], $clave['t']]]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);
    }
     curl_close($ch);

}

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Configuración de Router Fibra</title>
    <meta name="description" content=""/>
    <meta name="Author" content="Internet Infraestructuras S.L."/>

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

        label {
            font-weight: 700;
        }

        body {
            background-color: #fff;
        }
    </style>

    <style>
        body {
            background: url(img/fondo2.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            opacity: 50%;
        }
    </style>

</head>

<body>


<!-- WRAPPER -->
<div id="">


    <section id="">
        <div id="" class="">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent" style="height:50px">
                    <div class="col-lg-6 text-left">
                        <strong>Configuración del router fibra</strong>
                    </div>
                    <div class="col-lg-6 text-right">
                    <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] != '') { ?>
                        <img src="img/Loading_2.gif" style="height: 40px;margin-top:-10px; display:none" id="formanima1">
                        <input type="button" class="btn btn-info pull-right" style="margin-top:-10px;"
                               value="Guardar Cambios" onclick="submit()">
                        <a href="cerrar_sesion.php">
                            <input type="button" class="btn btn-warning pull-right" style="margin-top:-10px;"
                                   value="Cerrar Sesión">
                        </a>
                    <?php } ?>
                    </div>
                </div>
                <div class="panel-body">
                    <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] != '') {
                        echo '<form id="form" enctype="multipart/form-data" action="configurar_wifi.php" method="post">
                                    <input type="hidden" name="guardando">';

                        $pon = $_SESSION['sesion_iniciada'];

                        $result = $util->selectJoin("revendedores", array('empresa', 'direccion', 'localidad', 'revendedores.provincia', 'tel1', 'tel2',
                            'email', 'web', 'logo', 'provincias.provincia', 'municipios.municipio'),
                            "join provincias on provincias.id = revendedores.provincia join municipios on municipios.id=revendedores.localidad", "",
                            "revendedores.id = (select revendedor from usuarios where usuarios.id = (select id_usuario from aprovisionados where num_pon='" . $pon . "' limit 1) limit 1)");

                        $empresa = mysqli_fetch_array($result);

                        $ch = curl_init('http://10.211.2.2:7557/devices/');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($ch);
                        $json = json_decode($result, true);
                        curl_close($ch);

                        for ($c = 0; $c < count($json); $c++) {
                            if ($json[$c]['_deviceId']['_SerialNumber'] == $pon) {
                                $id_device = $json[$c]['_id'];
                                break;
                                break;
                            }
                        }

                        echo "<input type='hidden' name='device' value='$id_device'";
                        $a = array();
                        //refrescamos los valores desde el dispositivo


                        ini_set('max_execution_time', 6000);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?timeout=3000&connection_request";
                        $ch = curl_init($url);

                        include("php/lista_parametros_refresh_wifi.php");
                        $cfg = array();
                        foreach ($aItems as $clave) {
                            array_push($cfg, $clave['p']);
                            $payload = json_encode(array('name' => 'refreshObject', 'objectName' => $clave['p']));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_exec($ch);
                        }


                        // leemos los parametros desde el dispositivo
                        $url = "http://10.211.2.2:7557/devices/?query=%7B%22_id%22%3A%22" . $id_device . "%22%7D";
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($ch);
                        $json = json_decode($result, true);

                        include("php/lista_parametros_consultar_wifi.php");
//                        print_r($a);
//                        return;
                        curl_close($ch);
                        ?>
                        <div class="row">
                            <div class="col-lg-4 col-xs-12 text-center">
                                <img src="<?php echo $empresa[8]; ?>" style="max-width: 400px;"><br><br>
                                <span style="font-size:2.5em; font-weight: 700"><?php echo $empresa[0]; ?></span><br>
                                <span style="font-size:1.5em; font-weight: 500"><?php echo $empresa[1]; ?></span><br>
                                <span style="font-size:1em; font-weight: 500"><?php echo $empresa[10]; ?></span> -
                                <span style="font-size:1em; font-weight: 500"><?php echo $empresa[9]; ?></span><br><br>
                                <span style="font-size:1em; font-weight: 500"><?php echo $empresa[4]; ?></span> -
                                <span style="font-size:1em; font-weight: 500"><?php echo $empresa[5]; ?></span><br><br>
                                <span style="font-size:1em; font-weight: 600"><?php echo $empresa[6]; ?></span><br>
                                <span style="font-size:1em; font-weight: 600"><?php echo $empresa[7]; ?></span><br><br><br>
                                Powered By:<br><br>
                                <img src="http://s672094617.web-inicial.es/s/misc/logo.png?t=1530283921" style="max-width: 300px;"><br><br>

                            </div>
                            <div class="col-lg-1 col-xs-12"></div>
                            <div class="col-lg-3 col-xs-12">
                                <div  style="width:300px">
                                    <div class="col-xs-6 text-center">
                                        <label class="switch switch-success">
                                            Mostrar SSID<br>
                                            <input type="hidden"
                                                   name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSIDAdvertisementEnabled"
                                                   value="off">
                                            <input type="checkbox"
                                                   name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSIDAdvertisementEnabled"
                                                   id="ssidon"
                                                <?php echo intval($a[8]) == 1 ? "checked" : ""; ?>><span
                                                    class="switch-label" data-on="SI" data-off="NO"></span>
                                        </label>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <label class="switch switch-success">
                                            Activar WPS<br>
                                            <input type="hidden"
                                                   name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS.Enable"
                                                   value="off">
                                            <input type="checkbox"
                                                   name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS.Enable"
                                                   id="wps"
                                                <?php echo intval($a[11]) == 1 ? "checked" : ""; ?>><span
                                                    class="switch-label" data-on="SI" data-off="NO"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <br><label>SSID (Nombre de la Wifi)</label>

                                    <input type="text" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID"
                                           id="ssid" value="<?php echo $a[2]; ?>" maxlength="32" class="form-control "
                                           placeholder="Nombre de la wifi Max. 32 caracteres [aA09-_ ]"
                                           style="height:50px;width:300px">
                                </div>
                                <div class="col-xs-12">
                                    <br> <label>Tipo Seguridad </label><br>
                                    <select id="tiposeguridad"
                                            name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.BeaconType"
                                            style="height:50px;width:300px">
                                        <option value="Basic" <?php echo $a[9] == 'Basic' ? "selected" : ""; ?> >Ninguna
                                        </option>
<!--                                        <option value="basic">Shared</option>-->
                                        <option value="WPA" <?php echo $a[9] == 'WPA' ? "selected" : ""; ?>>WPA
                                            PreSharedKey
                                        </option>
                                        <option value="11i" <?php echo $a[9] == '11i' ? "selected" : ""; ?>>WPA2
                                            PreSharedKey
                                        </option>
                                        <option value="WPAand11i" <?php echo $a[9] == 'WPAand11i' ? "selected" : ""; ?>>
                                            WPA/WPA2 PreSharedKey
                                        </option>
<!--                                        <option value="WPA" --><?php //echo $a[9] == 'WPA' ? "selected" : ""; ?><!-->
<!--                                            Enterprise-->
<!--                                        </option>-->
<!--                                        <option value="11i" --><?php //echo $a[9] == '11i' ? "selected" : ""; ?><!-->
<!--                                            Enterprise-->
<!--                                        </option>-->
<!--                                        <option value="WPAand11i" --><?php //echo $a[9] == 'WPAand11i' ? "selected" : ""; ?><!-->
<!--                                            WPA/WPA2 Enterprise-->
<!--                                        </option>-->
                                    </select><br>
                                </div>
                                <div class="col-xs-12">
                                    <br> <label>Encriptación</label><br>
                                    <select id="encriptacion"
                                            name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.IEEE11iEncryptionModes"
                                            <?php echo $a[9] == 'disabled'; ?>
                                            style="height:50px;width:300px">
                                        <option value="AESEncryption" <?php echo $a[10] == 'AESEncryption' ? "selected" : ""; ?> >AES</option>
                                        <option value="TKIPEncryption" <?php echo $a[10] == 'TKIPEncryption' ? "selected" : ""; ?>>TKIP</option>
                                        <option value="TKIPandAESEncryption" <?php echo $a[10] == 'TKIPandAESEncryption' ? "selected" : ""; ?>>TKIP&amp;AES</option>
                                        <option value="none" <?php echo $a[9] == 'Basic' ? "selected" : ""; ?> >Ninguna</option>

                                    </select><br>
                                </div>
                                <div class="col-xs-12">
                                    <br><label>Clave Wifi </label>
                                    <input type="text"
                                           name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey"
                                           id="wifi_pass1" value="<?php echo $a[3]; ?>"
                                           class="form-control " placeholder="Mínimo 8 Caracteres"
                                           style="height:50px;width:300px"><br>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-12">
                                <div class="col-xs-12">
                                    <label>Configuración LAN</label><br><br>
                                    <label style="margin-top:-8px" class="checkbox">
                                        <input type="checkbox" <?php echo intval($a[18]) == 1 ? "checked" : ""; ?>
                                               value="1" id="dhcp"
                                               name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DHCPServerEnable">
                                        <i></i> Servidor DHCP
                                    </label>
                                </div>
                                <div class="col-xs-12">
                                    <label>Ip Inicio </label><br>
                                    <input type="text"
                                           name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MinAddress"
                                           id="ip_ini" value="<?php echo $a[14]; ?>"
                                           class="form-control " placeholder="192.168.100.2"
                                           style="height:50px;width:300px"><br>
                                    <label>Ip Fin </label><br>
                                    <input type="text"
                                           name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MaxAddress"
                                           id="ip_fin" value="<?php echo $a[15]; ?>"
                                           class="form-control " placeholder="192.168.100.254"
                                           style="height:50px;width:300px"><br>
                                </div>
                                <div class="col-xs-12">
                                    <label>DNS Primario </label><br>
                                    <input type="text"
                                           name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers[0]"
                                           id="dns1" value="<?php echo explode(",", $a[17])[0]; ?>"
                                           class="form-control " placeholder="8.8.8.8"
                                           style="height:50px;width:300px"><br>
                                    <label>DNS Secundario </label><br>
                                    <input type="text"
                                           name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers[1]"
                                           id="dns2" value="<?php echo explode(",", $a[17])[1]; ?>"
                                           class="form-control " placeholder="8.8.4.4"
                                           style="height:50px;width:300px"><br>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-12">


                            </div>
                        </div>
                    <?php } else { ?>

                        <div class="col-lg-4 text-center">
                            Powered By:<br><br>
                            <img src="http://s672094617.web-inicial.es/s/misc/logo.png?t=1530283921" style="max-width: 300px;"><br><br>
                        </div>
                        <div class="col-lg-5 text-center">
                            <!-- login form -->
                            <form action="configurar_wifi.php" id="form1" method="post" enctype="multipart/form-data" class="">
                                <header><i class="fa fa-users"></i> Acceso Usuarios</header>

                                <fieldset>
                                    <div class="col-xs-12 col-lg-5">
                                        <br>
                                        <i class="icon-append fa fa-barcode"></i>
                                        <label class="">Usuario (ID): <i class="fa fa-question-circle"></i></label>
                                        <label class="input">
                                            <input type="text" id="serial" name="serial" class="form-control" value="<?php echo $_COOKIE['usuario'];?>">
                                            <span class="tooltip tooltip-top-right">Teclee el número de serie impreso en el router</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-lg-5">
                                        <br>
                                        <i class="icon-append fa fa-lock"></i>
                                        <label class="">Contraseña (PON): <i class="fa fa-question-circle"></i></label>
                                        <label class="input">
                                            <input type="password" id="password" name="password" class="form-control" value="<?php echo $_COOKIE['clave'];?>">
                                            <b class="tooltip tooltip-top-right">Teclea tu contraseña impresa en el
                                                router</b>
                                        </label>

                                    </div>
                                    <div class="col-xs-12 col-lg-2">
                                        <br><br>

                                        <button type="button" class="btn btn-primary" onclick="submit1();"
                                                style="margin-top:5px">Entrar
                                        </button>

                                    </div>
                                    <div class="forgot-password">
                                        <?php
                                        if (isset($login_fail) && $login_fail == true) echo "<h5 style='color:red'>Datos erroneos</h5>";
                                        ?>
                                    </div>

                                </fieldset>
                            </form>
                            <center>
                            <img src="img/Loading_2.gif" id="formanima2" style="width:40px; display:none">
                            </center>
                            <!-- /login form -->
                        </div>
                        <div class="col-lg-3 text-center">
                            <br>
                            <img src="img/ont1.PNG" id="ayuda" style="max-width: 300px;"><br><br>
                        </div>
                    <?php } ?>
                </div>
            </div>
    </section>
    </form>
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
<script src="//cdn.rawgit.com/noelboss/featherlight/1.7.12/release/featherlight.min.js" type="text/javascript"
        charset="utf-8"></script>


<script>

    function submit1() {
        $("#formanima2").css('display', 'block');
        $("#form1").submit();

    }

    function submit() {
        var a = $("#dhcp").val();
        var b = $("#ssid").val();
        var c = $("#wifi_pass1").val();
        var d = $("#ip_ini").val();
        var e = $("#ip_fin").val();
        var f = $("#dns1").val();
        var g = $("#dns2").val();
        var h = $("#ssidon:checked").val();
        var i = $("#wps").val();

        if(a==1 && (d=='' || e=='' || (f=='' && g==''))){
            alert("Si activas el DHCP, debes completar (Ip inicio, Ip Fin y uno de los DNS)");
            return;
        }

        if(h=='on' && b==''){
            alert("El nombre de la WIFI no puede estar vacio");
            return;
        }

        $("#formanima1").css('display', 'block');


        $("#form").submit();
    }

    $( ".fa-question-circle" ).bind( "mouseenter", function() {
        $("#ayuda").attr("src","img/etiqueta1.PNG");
    });

    $( ".fa-question-circle" ).bind( "mouseleave", function() {
        $("#ayuda").attr("src","img/ont1.PNG");
    });

    $( "#tiposeguridad" ).change( function() {
        if(this.value =='Basic'){
            $("#encriptacion option[value=none]").attr('selected','selected');
            $("#encriptacion").val('none');
            $("#encriptacion").prop('disabled',true);
        } else {
            $("#encriptacion").val('TKIPandAESEncryption');
            $("#encriptacion").prop('disabled',false);

        }
    });



</script>

</body>
</html>



