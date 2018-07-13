<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Configuración de Router Fibra</title>
    <meta name="description" content=""/>
    <meta name="Author" content="Internetinfraestructuras S.L."/>

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
    </style>
</head>

<body>

<img src="img/animacion1.gif" style="height: 100%; width:100%; position:fixed; z-index:100000" id="formanima">
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

    <form id="form1" enctype="multipart/form-data" action="gestion_acs.php?p=<?php echo $_REQUEST['p']; ?>" method="post">
        <input type="hidden" name="guardando">';

        <section id="middle">
            <img src="img/save.png" onclick="submit1();" style="position: fixed; right:5%;bottom:5%; width:90px; cursor:pointer;z-index: 20000">
            <!-- page title -->
            <header id="page-header">
                <h1>Usted esta en</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Configuración del Router</a></li>
                </ol>
            </header>
            <!-- /page title -->

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
            check_session(3);

            if (isset($_POST['guardando'])) {
                $n = 0;
                $aItems = array();

                foreach ($_POST as $clave => $valor) {
                    $c = str_replace('_1_', '.1.', $clave);
                    $c = str_replace('_2_', '.2.', $c);
                    $c = str_replace('_3_', '.3.', $c);
                    $c = str_replace('_4_', '.4.', $c);
                    $c = str_replace('_DHCPLeaseTime', '.DHCPLeaseTime', $c);
                    $c = str_replace('-', '.', $c);
                    $c = str_replace('_WAN', '.WAN', $c);
                    $c = str_replace('_LAN', '.LAN', $c);
                    $c = str_replace('HW.LANBIND', 'HW_LANBIND', $c);
//                    echo $c."<br>".$n;

                    $v = $valor;

                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_DHCPServerEnable') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DHCPServerEnable';
                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_MinAddress') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MinAddress';
                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_MaxAddress') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MaxAddress';
                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement_DNSServers') $c = 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers';

                    if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS_Enable') {
                        if (trim($v) == 'on') $v = 'true'; else if (trim($v) == 'off') $v = 'false'; else if (trim($v) == '1') $v = 'true'; else if (trim($v) == '0') $v = 'false';
                    }

                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.X_HW_Option125Enable') {
                        if (trim($v) == 'on') $v = '1'; else if (trim($v) == 'off') $v = '0'; else if (intval(trim($v)) == 1) $v = '1'; else if (trim($v) == '0') $v = '0';
                    }

                    if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.X_HW_DHCPL2RelayEnable') {
                        if (trim($v) == 'on') $v = '1'; else if (trim($v) == 'off') $v = '0'; else if (intval(trim($v)) == 1) $v = '1'; else if (trim($v) == '0') $v = '0';
                    }

                    if ($c == 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSIDAdvertisementEnabled') {
                        if (trim($v) == 'on') $v = '1'; else if (trim($v) == 'off') $v = '0'; else if (intval(trim($v)) == 1) $v = '1'; else if (trim($v) == '0') $v = '0';
                    }

                    if (intval(strripos($c,'enable')>0)) {
                        if (trim($v) == 'on') $v = '1'; else if (trim($v) == 'off') $v = '0'; else if (intval(trim($v)) == 1) $v = '1'; else if (trim($v) == '0') $v = '0';
                    }

                    $n++;
                    if ($clave == 'device')
                        $id_device = $valor;

                    else if ($clave == 'guardando') {

                    } else
                        if ($c == 'InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers') {
                            $aItem = array('p' => $c, 'v' => $v[0] . "," . $v[1], 't' => "xsd:string");
                            array_push($aItems, $aItem);
                        } else {
                            $aItem = array('p' => $c, 'v' => $v, 't' => "xsd:string");
                            array_push($aItems, $aItem);
                        }
                }
                curl_close($ch);

                $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?connection_request";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                foreach ($aItems as $clave) {
                    $payload = json_encode(array('name' => 'setParameterValues', 'parameterValues' => [[$clave['p'], $clave['v'], $clave['t']]]));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    echo curl_exec($ch);

                }
                curl_close($ch);

            }

            if(isset($_REQUEST['p'])) {

                $pon = $_REQUEST['p'];

                //$result = $util->selectWhere('acs_ids', array('id_acs'), " pon='" . $pon."'");

//                while ($row = mysqli_fetch_array($result)) {
//                    $id_device = $row[0];
//                }

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


                $a = array();
                //refrescamos los valores desde el dispositivo


                ini_set('max_execution_time', 6000);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $url = "http://10.211.2.2:7557/devices/" . $id_device . "/tasks?timeout=3000&connection_request";
                $ch = curl_init($url);
                $aItems = array();

                include("php/lista_parametros_refresh.php");

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
                include("php/lista_parametros_consultar.php");
                curl_close($ch);

                echo "<input type='hidden' name='device' value='$id_device'";


            }



            ?>
            <div id="content" class="padding-20">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Interfaz Inalámbrica (Wifi)</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3 col-xs-9">
                                <label>SSID </label>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID"
                                       id="ssid" value="<?php echo $a[76]; ?>" maxlength="32" class="form-control "
                                       placeholder="Nombre de la wifi Max. 32 caracteres [aA09-_ ]"
                                       style="height:50px"><?php echo $a[77]; ?>
                            </div>
                            <div class="col-lg-3 col-xs-12 text-center">
                                <div class="col-lg-6 col-xs-6 text-center">
                                    <label class="switch switch-success">
                                        Mostrar SSID<br>
                                        <input name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSIDAdvertisementEnabled"
                                                type="checkbox" value="1" id="ssidon" <?php echo intval($a[82]) == 1 ? "checked" : ""; ?>><span
                                                class="switch-label" data-on="SI" data-off="NO"></span>
                                    </label>

                                </div>
                                <div class="col-lg-6 col-xs-6 text-center">
                                    <label class="switch switch-success">
                                        Activar WPS<br>
                                        <input type="checkbox" value="1" id="wpson" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.WPS.Enable"
                                            <?php echo intval($a[85]) == 1 ? "checked" : ""; ?>><span
                                                class="switch-label" data-on="SI" data-off="NO"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-12">
                                <label>Tipo Seguridad </label><br>
                                <select id="tiposeguridad" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.BeaconType" style="height:50px;">
                                    <option value="Basic" <?php echo $a[83] == 'Basic' ? "selected" : ""; ?> >Ninguna
                                    </option>
                                    <!--                                        <option value="basic">Shared</option>-->
                                    <option value="WPA" <?php echo $a[83] == 'WPA' ? "selected" : ""; ?>>WPA
                                        PreSharedKey
                                    </option>
                                    <option value="11i" <?php echo $a[83] == '11i' ? "selected" : ""; ?>>WPA2
                                        PreSharedKey
                                    </option>
                                    <option value="WPAand11i" <?php echo $a[83] == 'WPAand11i' ? "selected" : ""; ?>>
                                        WPA/WPA2 PreSharedKey
                                    </option>
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-xs-12">
                                <label>Encriptación</label><br>
                                <select id="encriptacion"
                                        name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.IEEE11iEncryptionModes"
                                    <?php echo $a[83] == 'disabled'; ?>
                                        style="height:50px;width:300px">
                                    <option value="AESEncryption" <?php echo $a[84] == 'AESEncryption' ? "selected" : ""; ?> >AES</option>
                                    <option value="TKIPEncryption" <?php echo $a[84] == 'TKIPEncryption' ? "selected" : ""; ?>>TKIP</option>
                                    <option value="TKIPandAESEncryption" <?php echo $a[84] == 'TKIPandAESEncryption' ? "selected" : ""; ?>>TKIP&amp;AES</option>
                                    <option value="none" <?php echo $a[84] == 'Basic' ? "selected" : ""; ?> >Ninguna</option>

                                </select><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-xs-12">
                                <label>Clave Wifi </label>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey"
                                       id="wifi_pass1" value="<?php echo $a[77]; ?>"
                                       class="form-control " placeholder="Mínimo 8 Caracteres" style="height:50px">
                            </div>
                            <div class="col-lg-3 col-xs-12">
                                <label>Canal Wifi </label><br>
                                <select id="canalwifi" name="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.Channel" style="height:50px;">
                                    <option value="0" <?php echo $a[112] == 'true' ? "selected" : ""; ?> >Automático</option>
                                    <option value="1" <?php echo $a[111] == '1' ? "selected" : ""; ?> >1</option>
                                    <option value="2" <?php echo $a[111] == '2' ? "selected" : ""; ?> >2</option>
                                    <option value="3" <?php echo $a[111] == '3' ? "selected" : ""; ?> >3</option>
                                    <option value="4" <?php echo $a[111] == '4' ? "selected" : ""; ?> >4</option>
                                    <option value="5" <?php echo $a[111] == '5' ? "selected" : ""; ?> >5</option>
                                    <option value="6" <?php echo $a[111] == '6' ? "selected" : ""; ?> >6</option>
                                    <option value="7" <?php echo $a[111] == '7' ? "selected" : ""; ?> >7</option>
                                    <option value="8" <?php echo $a[111] == '8' ? "selected" : ""; ?> >8</option>
                                    <option value="9" <?php echo $a[111] == '9' ? "selected" : ""; ?> >9</option>
                                    <option value="10" <?php echo $a[111] == '10' ? "selected" : ""; ?> >10</option>
                                    <option value="11" <?php echo $a[111] == '11' ? "selected" : ""; ?> >11</option>
                                    <option value="12" <?php echo $a[111] == '12' ? "selected" : ""; ?> >12</option>
                                    <option value="13" <?php echo $a[111] == '13' ? "selected" : ""; ?> >13</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Interfaz LAN</strong>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-2 col-xs-12 text-center">
                                <label>Ip Externa </label><br>
                                <?php echo $a[108]; ?>
                                <br>
                                <a href="http://<?php echo $a[108]; ?>" target="_blank"><img src="img/ont.png" style="height: 60px"></a>

                            </div>
                            <div class="col-lg-2 col-xs-12">
                                <label>Ip Interna </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.IPInterface.1.IPInterfaceIPAddress" id="ip_router" value="<?php echo $a[86]; ?>"
                                       class="form-control " placeholder="192.168.100.1"
                                       style="height:34px;width:140px"><br>
                                <label>Mascara </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.IPInterface.1.IPInterfaceSubnetMask" id="mascara_router"
                                       value="<?php echo $a[87]; ?>" class="form-control " placeholder="255.255.255.0"
                                       style="height:34px;width:140px"><br>
                            </div>
                            <div class="col-lg-2 col-xs-12">
                                <label>Opciones DHCP</label><br><br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" <?php echo intval($a[105]) == 1 ? "checked" : ""; ?>
                                            id="dhcp" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DHCPServerEnable">
                                    <i></i> DHCP Server
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" <?php echo intval($a[106]) == 1 ? "checked" : ""; ?>
                                            id="relay" name="InternetGatewayDevice-LANDevice-1-LANHostConfigManagement-X_HW_DHCPL2RelayEnable">
                                    <i></i> DHCP Relay
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" <?php echo intval($a[107]) == 1 ? "checked" : ""; ?>
                                            id="option125" name="InternetGatewayDevice-LANDevice-1-LANHostConfigManagement-X_HW_Option125Enable">
                                    <i></i> Option125
                                </label>
                            </div>
                            <div class="col-lg-2 col-xs-12">
                                <label>Ip Inicio </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MinAddress" id="ip_ini" value="<?php echo $a[88]; ?>"
                                       class="form-control " placeholder="192.168.100.2"
                                       style="height:34px;width:140px"><br>
                                <label>Ip Fin </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.MaxAddress" id="ip_fin" value="<?php echo $a[89]; ?>"
                                       class="form-control " placeholder="192.168.100.254"
                                       style="height:34px;width:140px"><br>
                            </div>
                            <div class="col-lg-2 col-xs-12">
                                <label>DNS Primario </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers[0]" id="dns1" value="<?php echo explode(",",$a[104])[0]; ?>"
                                       class="form-control " placeholder="8.8.8.8" style="height:34px;width:140px"><br>
                                <label>DNS Secundario </label><br>
                                <input type="text" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DNSServers[1]" id="dns2" value="<?php echo explode(",",$a[104])[1]; ?>"
                                       class="form-control " placeholder="8.8.4.4" style="height:34px;width:140px"><br>
                            </div>

                            <div class="col-lg-2 col-xs-12">
                                <label>Tiempo concesión (segundos) </label><br>
                                <input type="number" name="InternetGatewayDevice.LANDevice.1.LANHostConfigManagement.DHCPLeaseTime" id="tconces" value="<?php echo $a[103]; ?>" min="1"
                                       max="604800" class="form-control " placeholder="1"
                                       style="height:34px;width:90px"><br>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Interfaces WAN (Internet)</strong>
                    </div>

                    <div class="panel-body">
                        <label>Interfaz VLAN 1 (PPPoE)</label><br><br>

                        <div class="row">
                            <div class="col-lg-3 col-xs-12">
                                <label>Puertos Activos</label><br>
                                <label class="checkbox">
                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_LANBIND-Lan1Enable"
                                            <?php echo intval($a[4]) == 1 ? "checked" : ""; ?>
                                           id="w1-lan1">
                                    <i></i> Lan 1
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_LANBIND-Lan2Enable"
                                            <?php echo intval($a[5]) == 1 ? "checked" : ""; ?>
                                           id="w1-lan2">
                                    <i></i> Lan 2
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_LANBIND-Lan3Enable"
                                            <?php echo intval($a[6]) == 1 ? "checked" : ""; ?>
                                           id="w1-lan3">
                                    <i></i> Lan 3
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_LANBIND-Lan4Enable"
                                            <?php echo intval($a[7]) == 1 ? "checked" : ""; ?>
                                           id="w1-lan4">
                                    <i></i> Lan 4
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_LANBIND-SSID1Enable"
                                            <?php echo intval($a[8]) == 1 ? "checked" : ""; ?>
                                           id="w1-ssid1">
                                    <i></i> Wifi
                                </label>

                            </div>
                            <div class="col-lg-3">
                                <center>
                                    <label>Enrutado</label><br>
                                </center>
<!--                                <div class="col-lg-4 col-xs-12">-->
                                    <label class="checkbox">
                                        <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Enable"
                                                <?php echo intval($a[0]) == 1 ? "checked" : ""; ?> id="w1-wan">
                                        <i></i> Enable WAN
                                    </label>
                                    <br>
<!--                                </div>-->
<!--                                <div class="col-lg-4 col-xs-12">-->
                                    <label class="checkbox">
                                        <input type="checkbox" value="1"
                                                <?php echo intval($a[2]) == 1 ? "checked" : ""; ?>
                                               id="w1-vlan">
                                        <i></i> Enable VLAN
                                    </label>
                                <br>
<!--                                </div>-->
<!--                                <div class="col-lg-4 col-xs-12">-->
                                    <label class="checkbox">
                                        <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.NATEnabled"
                                                <?php echo intval($a[13]) == 1 ? "checked" : ""; ?>
                                               id="w1-nat">
                                        <i></i> Enable NAT
                                    </label>
<!--                                </div>-->
                            </div>
                            <div class="col-lg-2">
                                <label>Modo Operación</label><br>
                                <select id="WanMode" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.ConnectionType" style="height:46px;width:140px">
                                    <?php if ($a[9] == 'IP_Routed')
                                        echo '<option selected value="IP_Routed" >Router</option><option value="IP_Bridged" >Bridge</option>';
                                    else
                                        echo '<option value="IP_Routed" >Router</option><option selected value="IP_Bridged">Bridge</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Wlan ID </label><br>
                                <input type="text"  name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-1-WANPPPConnection-1-X_HW_VLAN"
                                       id="w1_lanid" value="<?php echo $a[2]; ?>"
                                       class="form-control " placeholder="100" style="height:34px;width:70px"><br>
                                <label>Descripción VLAN</label><br>
                                <input type="text"  name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Name"
                                       id="w1_vlname" value="<?php echo $a[1]; ?>"
                                       class="form-control " placeholder="Internet_100_Dhcp" style="height:34px;width:90%"><br>

                            </div>
                        </div>
                        <br><br>
                        
                        
                        <label>Interfaz VLAN 2 (DHCP)</label><br>
                        <br>
                        <div class="row">
                            <div class="col-lg-3 col-xs-12">
                                <label>Puertos Activos</label><br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_LANBIND-Lan1Enable"
                                            <?php echo intval($a[18]) == 1 ? "checked" : ""; ?>
                                           id="w2-lan1">
                                    <i></i> Lan 1
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_LANBIND-Lan2Enable"
                                            <?php echo intval($a[19]) == 1 ? "checked" : ""; ?>
                                           id="w2-lan2">
                                    <i></i> Lan 2
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_LANBIND-Lan3Enable"
                                            <?php echo intval($a[20]) == 1 ? "checked" : ""; ?>
                                           id="w2-lan3">
                                    <i></i> Lan 3
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_LANBIND-Lan4Enable"
                                            <?php echo intval($a[21]) == 1 ? "checked" : ""; ?>
                                           id="w2-lan4">
                                    <i></i> Lan 4
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_LANBIND-SSID1Enable"
                                            <?php echo intval($a[22]) == 1 ? "checked" : ""; ?>
                                           id="w2-ssid1">
                                    <i></i> Wifi
                                </label>

                            </div>
                            <div class="col-lg-3">
                                <center>
                                    <label>Enrutado</label><br>
                                </center>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.Enable"
                                            <?php echo intval($a[14]) == 1 ? "checked" : ""; ?> id="w2-wan">
                                    <i></i> Enable WAN
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1"
                                            <?php echo intval($a[2]) == 1 ? "checked" : ""; ?>
                                           id="w2-vlan">
                                    <i></i> Enable VLAN
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.NATEnabled"
                                            <?php echo intval($a[24]) == 1 ? "checked" : ""; ?>
                                           id="w2-nat">
                                    <i></i> Enable NAT
                                </label>
                                <!--                                </div>-->
                            </div>
                            <div class="col-lg-2">
                                <label>Modo Operación</label><br>
                                <select id="WanMode" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.ConnectionType" style="height:46px;width:140px">
                                    <?php if ($a[23] == 'IP_Routed')
                                        echo '<option selected value="IP_Routed" >Router</option><option value="IP_Bridged" >Bridge</option>';
                                    else
                                        echo '<option value="IP_Routed" >Router</option><option selected value="IP_Bridged">Bridge</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Wlan ID </label><br>
                                <input type="text"  name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-2-WANIPConnection-1-X_HW_VLAN"
                                       id="w1_lanid" value="<?php echo $a[16]; ?>"
                                       class="form-control " placeholder="100" style="height:34px;width:70px"><br>
                                <label>Descripción VLAN</label><br>
                                <input type="text"  name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.2.WANIPConnection.1.Name"
                                       id="w1_vlname" value="<?php echo $a[15]; ?>"
                                       class="form-control " placeholder="Internet_100_Dhcp" style="height:34px;width:90%"><br>

                            </div>
                        </div>

                        <label>Interfaz Telefonía</label><br><br>

                        <div class="row">
                            <div class="col-lg-3 col-xs-12">
                                <label>Puertos Activos</label><br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_LANBIND-Lan1Enable"
                                            <?php echo intval($a[33]) == 1 ? "checked" : ""; ?>
                                           id="w3-lan1">
                                    <i></i> Lan 1
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_LANBIND-Lan2Enable"
                                            <?php echo intval($a[34]) == 1 ? "checked" : ""; ?>
                                           id="w3-lan2">
                                    <i></i> Lan 2
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_LANBIND-Lan3Enable"
                                            <?php echo intval($a[35]) == 1 ? "checked" : ""; ?>
                                           id="w3-lan3">
                                    <i></i> Lan 3
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_LANBIND-Lan4Enable"
                                            <?php echo intval($a[36]) == 1 ? "checked" : ""; ?>
                                           id="w3-lan4">
                                    <i></i> Lan 4
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_LANBIND-SSID1Enable"
                                            <?php echo intval($a[37]) == 1 ? "checked" : ""; ?>
                                           id="w3-ssid1">
                                    <i></i> Wifi
                                </label>

                            </div>
                            <div class="col-lg-3">
                                <center>
                                    <label>Enrutado</label><br>
                                </center>
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.Enable"
                                            <?php echo intval($a[29]) == 1 ? "checked" : ""; ?> id="w2-wan">
                                    <i></i> Enable WAN
                                </label>
                                <br>
                                <label class="checkbox">
                                    <input type="checkbox" value="1"
                                            <?php echo intval($a[2]) == 1 ? "checked" : ""; ?>
                                           id="w2-vlan">
                                    <i></i> Enable VLAN
                                </label>
                                <br>
                                <!--                                </div>-->
                                <!--                                <div class="col-lg-4 col-xs-12">-->
                                <label class="checkbox">
                                    <input type="checkbox" value="1" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.NATEnabled"
                                            <?php echo intval($a[39]) == 1 ? "checked" : ""; ?>
                                           id="w3-nat">
                                    <i></i> Enable NAT
                                </label>
                            </div>
                            <div class="col-lg-2">
                                <label>Modo Operación</label><br>
                                <select id="WanMode" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.ConnectionType" style="height:46px;width:140px">
                                    <?php if ($a[38] == 'IP_Routed')
                                        echo '<option selected value="IP_Routed" >Router</option><option value="IP_Bridged" >Bridge</option>';
                                    else
                                        echo '<option value="IP_Routed" >Router</option><option selected value="IP_Bridged">Bridge</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Wlan ID </label><br>
                                <input type="text"  name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-3-WANIPConnection-1-X_HW_VLAN"
                                       id="w1_lanid" value="<?php echo $a[31]; ?>"
                                       class="form-control " placeholder="100" style="height:34px;width:70px"><br>
                                <label>Descripción VLAN</label><br>
                                <input type="text"  name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.3.WANIPConnection.1.Name"
                                       id="w1_vlname" value="<?php echo $a[30]; ?>"
                                       class="form-control " placeholder="Internet_100_Dhcp" style="height:34px;width:90%"><br>

                            </div>
                        </div>

<!--                        <label>Interfaz Televisión</label><br>-->
<!---->
<!--                        <div class="row">-->
<!--                            <div class="col-lg-3 col-xs-12">-->
<!--                                <label>Puertos Activos</label><br>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_LANBIND-Lan1Enable"-->
<!--                                           value="1" --><?php //echo intval($a[33]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-lan1">-->
<!--                                    <i></i> Lan 1-->
<!--                                </label>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_LANBIND-Lan2Enable"-->
<!--                                           value="1" --><?php //echo intval($a[34]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-lan2">-->
<!--                                    <i></i> Lan 2-->
<!--                                </label>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_LANBIND-Lan3Enable"-->
<!--                                           value="1" --><?php //echo intval($a[35]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-lan3">-->
<!--                                    <i></i> Lan 3-->
<!--                                </label>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_LANBIND-Lan4Enable"-->
<!--                                           value="1" --><?php //echo intval($a[36]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-lan4">-->
<!--                                    <i></i> Lan 4-->
<!--                                </label>-->
<!--                                <br>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_LANBIND-SSID1Enable"-->
<!--                                           value="1" --><?php //echo intval($a[37]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-ssid1">-->
<!--                                    <i></i> Wifi-->
<!--                                </label>-->
<!---->
<!--                            </div>-->
<!--                            <div class="col-lg-3">-->
<!--                                <center>-->
<!--                                    <label>Enrutado</label><br>-->
<!--                                </center>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.Enable"-->
<!--                                           value="1" --><?php //echo intval($a[29]) == 1 ? "checked" : ""; ?><!-- id="w2-wan">-->
<!--                                    <i></i> Enable WAN-->
<!--                                </label>-->
<!--                                <br>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox"-->
<!--                                           value="1" --><?php //echo intval($a[2]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-vlan">-->
<!--                                    <i></i> Enable VLAN-->
<!--                                </label>-->
<!--                                <br>-->
<!--                                <label class="checkbox">-->
<!--                                    <input type="checkbox" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.NATEnabled"-->
<!--                                           value="1" --><?php //echo intval($a[39]) == 1 ? "checked" : ""; ?>
<!--                                           id="w2-nat">-->
<!--                                    <i></i> Enable NAT-->
<!--                                </label>-->
<!--                            </div>-->
<!--                            <div class="col-lg-2">-->
<!--                                <label>Modo Operación</label><br>-->
<!--                                <select id="WanMode" name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.ConnectionType" style="height:46px;width:140px">-->
<!--                                    --><?php //if ($a[38] == 'IP_Routed')
//                                        echo '<option selected value="IP_Routed" >Router</option><option value="IP_Bridged" >Bridge</option>';
//                                    else
//                                        echo '<option value="IP_Routed" >Router</option><option selected value="IP_Bridged">Bridge</option>';
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
<!--                            <div class="col-lg-4">-->
<!--                                <label>Wlan ID </label><br>-->
<!--                                <input type="text"  name="InternetGatewayDevice-WANDevice-1-WANConnectionDevice-4-WANIPConnection-1-X_HW_VLAN"-->
<!--                                       id="w1_lanid" value="--><?php //echo $a[31]; ?><!--"-->
<!--                                       class="form-control " placeholder="100" style="height:34px;width:70px"><br>-->
<!--                                <label>Descripción VLAN</label><br>-->
<!--                                <input type="text"  name="InternetGatewayDevice.WANDevice.1.WANConnectionDevice.4.WANIPConnection.1.Name"-->
<!--                                       id="w1_vlname" value="--><?php //echo $a[45]; ?><!--"-->
<!--                                       class="form-control " placeholder="Internet_100_Dhcp" style="height:34px;width:90%"><br>-->
<!---->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Otras Configuraciones</strong>
                    </div>

                    <div class="panel-body">


                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <label>Dispositivos conectados</label><br>
                                <table class="text-center" id="jqgrid">
                                    <tr><td><b>IP</b></td><td><b>MAC</b></td><td><b>HOST</b></td></tr></b>
                                    <?php
                                    for ($nc=0;$nc<=intval($a[110]);$nc++) {
                                        echo ' 
                                            <tr>
                                                <td>' . $json[0]['InternetGatewayDevice']['LANDevice'][1]['Hosts']['Host'][$nc]['IPAddress']['_value'] . '</td>
                                                <td>' . $json[0]['InternetGatewayDevice']['LANDevice'][1]['Hosts']['Host'][$nc]['MACAddress']['_value'] . '</td>
                                                <td>' . $json[0]['InternetGatewayDevice']['LANDevice'][1]['Hosts']['Host'][$nc]['HostName']['_value'] . '</td>
                                            </tr>
                                            ';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="col-lg-6 col-xs-12">

                            </div>

                        </div>

                    </div>
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
    $(document).ready(function(){
        $("#formanima").css("display","none");
        jQuery("#jqgrid").jqGrid('setGridWidth', jQuery("#middle").width() - 32);

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

    function submit1() {

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


        $("#form1").submit();
    }

    $( "input:checkbox" ).change( function() {
        if(this.checked==true) {
            if (this.id=='w1-lan1' || this.id=='w2-lan1' || this.id=='w3-lan1'){
                $("#w1-lan1").prop( "checked", false );
                $("#w2-lan1").prop( "checked", false );
                $("#w3-lan1").prop( "checked", false );
                $(this).prop( "checked", true );
            }

            if (this.id=='w1-lan2' || this.id=='w2-lan2' || this.id=='w3-lan2'){
                $("#w1-lan2").prop( "checked", false );
                $("#w2-lan2").prop( "checked", false );
                $("#w3-lan2").prop( "checked", false );
                $(this).prop( "checked", true );
            }

            if (this.id=='w1-lan3' || this.id=='w2-lan3' || this.id=='w3-lan3'){
                $("#w1-lan3").prop( "checked", false );
                $("#w2-lan3").prop( "checked", false );
                $("#w3-lan3").prop( "checked", false );
                $(this).prop( "checked", true );
            }

            if (this.id=='w1-lan4' || this.id=='w2-lan4' || this.id=='w3-lan4'){
                $("#w1-lan4").prop( "checked", false );
                $("#w2-lan4").prop( "checked", false );
                $("#w3-lan4").prop( "checked", false );
                $(this).prop( "checked", true );
            }

            if (this.id=='w1-ssid1' || this.id=='w2-ssid1' || this.id=='w3-ssid1'){
                $("#w1-ssid1").prop( "checked", false );
                $("#w2-ssid1").prop( "checked", false );
                $("#w3-ssid1").prop( "checked", false );
                $(this).prop( "checked", true );
            }
        }
    });

</script>

</body>
</html>
