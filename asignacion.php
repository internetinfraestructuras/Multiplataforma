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

//descomentar borrar
//$_SESSION['REVENDEDOR']=1;
//$_SESSION['USER_LEVEL']=0;

require_once('config/util.php');

ini_set('display_errors', 0);
error_reporting('E_ALL');
$util = new util();
check_session(4);

echo $_SESSION['USER_ID'] ."<br>";
echo $_SESSION['NOM_USER']."<br>" ;
echo $_SESSION['USER_LEVEL']."<br>";
echo $_SESSION['REVENDEDOR']."<br>" ;
echo $_SESSION['CIF']."<br>" ;
echo $_SESSION['LOGO'] ;

print_r($_POST);

if(isset($_POST["orden"]) && intval($_POST["orden"])>0) {
    $orden = $_POST["orden"];
    $idcliente = $_POST["IDCLIENTE"];
    $down = $_POST["BAJADA"];
    $up = $_POST["SUBIDA"];
    
    if ($_POST["act_internet"] == "true")
        $act_internet = true;

    if ($_POST["act_voz"] == "true")
        $act_voz = true;

    if ($_POST["act_tv"] == "true")
        $act_tv = true;
}

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_CLIENTES; ?> /Listados</title>
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
        .olts label{
            color:#1965A7;
            font-weight: 600;
        }
        .onts label{
            color:#46b8da;
            font-weight: 600;
        }
        .pppoes label{
            color: #da8e0f;
            font-weight: 600;
        }
        .chasis label{
            color: red;
            font-weight: 700;
        }


    </style>
</head>

<body>


<!-- WRAPPER -->
<div id="wrapper">
    <?php if(!isset($_POST['orden'])) { ?>
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
                    <li><a href="#"><?php echo DEF_PROVISIONES; ?></a></li>
                    <li class="active">Dar Altas</li>
                </ol>
            </header>
            <!-- /page title -->

        <?php }
        //*******************************************************************************************************
        // si el usuario es root cargo todas las cabeceras para poder realizar operaciones en cualquiera de ellas
        // si no solo cargo las que pertenecen al usuario activo
        //*******************************************************************************************************
        if ($_SESSION['USER_LEVEL'] == 0) {
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), '', 'descripcion');
        } else {
            $cabeceras = $util->selectWhere('olts', array('id', 'descripcion'), ' wifero = (SELECT ID_EMPRESA FROM usuarios WHERE usuarios.id=' . $_SESSION["USER_ID"] . ')', 'descripcion');
        }

        ?>
        <div id="content" class="padding-20">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>SELECCIÓN DE CABECERA Y CLIENTE</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-md-6 col-xs-12">
                            <label>Seleccionar Cabecera</label>
                            <select id="cabecera" class="form-control" onchange="select_cabecera(this.value)">
                                <?php
                                $c = 0;
                                //*******************************************************************************************************
                                // muestro las cabeceras en el combo
                                //*******************************************************************************************************

                                while ($row = mysqli_fetch_array($cabeceras)) {
                                    if ($c == 0) {
                                        $ultimo = $row;
                                        $c = 1;
                                    }
                                    if ($row['id'] == $_COOKIE['cabecera'])
                                        echo "<option value='" . $row['id'] . "' selected>" . $row['descripcion'] . "</option>";
                                    else
                                        echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>
                        <?php
                            if(isset($_POST['orden'])){

                            }
//                            if(!isset($_POST['orden'])) {
                        ?>

                            <div class="col-lg-4 col-sm-6 col-md-6 col-xs-9 ocultar_si_es_orden">
                                <label>Seleccionar Cliente</label><br>
                                <select id="cliente" class="form-control select2" onchange="cambiarcliente(this.value)">
                                    <option value='0' selected disabled>Seleccione uno</option>

                                </select>
                            </div>
                            <div class="col-lg-1 col-sm-2 col-md-2 col-xs-3 text-center ocultar_si_es_orden">
                                <label class="switch switch">
                                    Ver todos<br>
                                    <input type="checkbox" id="ckmostrartodos" onchange="mostrartodos(this.checked);">
                                    <span class="switch-label" data-on="SI" data-off="NO"></span>
                                </label>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-md-6 col-xs-12 ocultar_si_es_orden">
                                <label>Cliente Rápido</label><br>
                                <input type="text" name="nuevo_nom" id="nuevo_nom" class="form-control"
                                       style="width:39%; display:inline" placeholder="Nombre">
                                <input type="text" name="nuevo_ape" id="nuevo_ape" class="form-control"
                                       style="width:59%; display:inline" placeholder="Apellidos">

                                <!--                            <a href="add-clie.php">-->
                                <!--                                <button type="button" class="btn btn-primary" style="margin-top:25px"> Nuevo </button>-->
                                <!--                            </a>-->
                            </div>
<!--                        --><?php //} ?>
                    </div>
                    <br>

                    <div class="row" id="linea_datos_clientes" style="display:none">
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-6"><b>Nombre</b></div>
                        <div class="col-lg-4 col-sm-2 col-md-2 col-xs-6"><b>Apellidos</b></div>
                        <div class="col-lg-4 col-sm-2 col-md-2 col-xs-6"><b>Dirección</b></div>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-6"><b>Teléfono</b></div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-6"><span
                                    id="nom_selected"></span></div>
                        <input type="hidden" id="nomcli" value="">
                        <input type="hidden" id="apecli" value="">
                        <input type="hidden" id="telcli" value="">

                        <div class="col-lg-4 col-sm-2 col-md-2 col-xs-6"><span
                                    id="ape_selected"></span></div>
                        <div class="col-lg-4 col-sm-2 col-md-2 col-xs-6"><span
                                    id="dir_selected"></span></div>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-6"><span
                                    id="tel_selected"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-default ocultar_si_es_orden">
                <div class="panel-heading panel-heading-transparent">
                    <strong>SELECCION DEL SERVICIO</strong>
                </div>

                <div class="panel-body ">

                    <div class="row ">
                        <div class="col-lg-3 text-center">
                            <br>
                            <div style="zoom:200%; border: 1px #898989 solid; border-radius:5px;padding-bottom:10px;">
                                <label class="switch switch-info switch-round">
                                    <span style="font-size:.8em">Internet</span><br>
                                    <i class="fa fa-internet-explorer fa-2x"></i>
                                    <input type="checkbox" id="internet_btn" <?php if ($act_internet) echo 'checked' ?>>
                                    <span class="switch-label" data-on="SI" data-off="NO"></span>
                                </label>
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-3 text-center">
                            <br>
                            <div style="zoom:200%; border: 1px #898989 solid; border-radius:5px;padding-bottom:10px;">
                                <label class="switch switch-info  switch-round">
                                    <span style="font-size:.8em">Telefonía</span><br>
                                    <i class="fa fa-phone fa-2x"></i>
                                    <input type="checkbox" id="voip_btn" <?php if ($act_voz) echo 'checked' ?>>
                                    <span class="switch-label" data-on="SI" data-off="NO"></span>
                                </label>
                            </div>
                            <br>
                        </div>

                        <div class="col-lg-3 text-center">
                            <br>
                            <div style="zoom:200%; border: 1px #898989 solid; border-radius:5px;padding-bottom:10px;">
                                <label class="switch switch-info switch-round">
                                    <span style="font-size:.8em">Televisión</span><br>
                                    <i class="fa fa-tv fa-2x"></i>
                                    <input type="checkbox" id="iptv_btn" <?php if ($act_tv) echo 'checked' ?>>
                                    <span class="switch-label" data-on="SI" data-off="NO"></span>
                                </label>
                            </div>
                            <br>
                        </div>

                        <div class="col-lg-3 text-center">
                            <br>
                            <div style="zoom:200%; border: 1px #898989 solid; border-radius:5px;padding-bottom:10px;">
                                <label class="switch switch-info switch-round">
                                    <span style="font-size:.8em">VPN / Nodo</span><br>
                                    <i class="fa fa-wifi fa-2x"></i>
                                    <input type="checkbox" id="vpn_btn">
                                    <span class="switch-label" data-on="SI" data-off="NO"></span>
                                </label>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default mostrar_si_es_orden" id="panel_internet" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos Internet</strong>
                </div>
                <div class="panel-borde" style="height:8px"></div>
                <div class="panel-body">

                    <div class="row olts">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Modelo Ont</label>
                            <select name="ont-profile" id="ont-profile"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-md-2 col-xs-6">
                            <label>Gestionada Por</label>
                            <select name="gestionada" id="gestionada"
                                    class="form-control pointer required" onchange="gestionada(this.value);">

                                <?php {
                                    echo '<option value="0">CABECERA</option>';
                                    echo '<option value="1" selected="selected">ROUTER EXTERNO</option>';
                                }

                                ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-6">

                            <label>Tipo Ip</label>
                            <select name="tipoip" id="tipoip" onchange="tipoip(this.value)"
                                    class="form-control pointer required">
                                <option value="0" selected>Dinámica</option>
                                <option value="1">Fija</option>
                                <!--                                --><?php //if($_COOKIE['tipoip']==1)
                                //                                    echo '<option value="1" selected>Fija</option>';
                                //                                else
                                //                                    echo '<option value="1">Fija</option>';
                                //                                ?>
                            </select>
                            <!--                            --><?php //if(intval($_COOKIE['gestionada'])==0) echo 'disabled="disabled"';?>
                        </div>
                        <div class="col-lg-2 col-xs-6">

                            <label>Asignación Ip</label>
                            <select name="asignaip" id="asignaip" onchange="asignada(this.value);"
                                    class="form-control pointer required">
                                <option value="0">OLT</option>
                                <?php if ($_COOKIE['asignada'] == 1)
                                    echo '<option value="1" selected>PPPOE</option>';
                                else
                                    echo '<option value="1">PPPOE</option>';
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-xs-12" id="aqui_vpn" style="display:none">

                        </div>

                        <div class="col-lg-3 col-xs-12">

                            <div class="col-xs-6" id="velup_ocultar">
                                <!--                             style="display: -->
                                <?php //echo $_COOKIE['asignada']==1? 'none':'block'; ?><!--"-->
                                <label>Subida</label>
                                <select name="velocidad_up" id="velocidad_up"
                                        class="form-control pointer required">
                                    <option value="">Seleccionar una</option>
                                </select>
                            </div>

                            <div class="col-xs-6" id="veldw_ocultar">
                                <!--                             style="display: -->
                                <?php //echo $_COOKIE['asignada']==1? 'none':'block'; ?><!--"-->
                                <label>Bajada</label>
                                <select name="velocidad_dw" id="velocidad_dw"
                                        class="form-control pointer required">
                                    <option value="">Seleccionar una</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row onts">
                        <div class="col-lg-3 col-xs-12">
                            <label>Número PON ONT</label>
                            <input type="text" name="serial_pon" id="serial_pon" class="required form-control masked" data-format="****************" data-placeholder="_"
                                   placeholder="Utilice el icono de buscar automáticamente">
                        </div>

                        <div class="col-lg-1 col-xs-12 col-md-6 col-sm-6 text-center" id="btn-autofind">
                            <span class="btn btn-primary" style="margin-top:25px; width:100%; z-index:400000">
                                <i class="fa fa-2x fa-search"></i>
                            </span>
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center">-->
                        <!--                            <span class="btn btn-success" id="btn-barcode2" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->


                        <div class="col-lg-2 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Número de Serie ONT</label>
                            <label class="hidden-xs">Número de Serie ONT</label>
                            <input type="text" name="serial" id="serial" class="required form-control"
                                   placeholder="010118IPTV12123456">
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">SSID Wifi <i
                                        class="fa fa-asterisk"></i></label>
                            <label class="hidden-xs">SSID Wifi <i class="fa fa-asterisk"></i></label>
                            <input type="text" name="ssid" id="ssid" class="form-control" placeholder="Mi Fibra Wifi">
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Clave Wifi <i
                                        class="fa fa-asterisk"></i></label>
                            <label class="hidden-xs">Clave Wifi <i class="fa fa-asterisk"></i></label>
                            <input type="text" name="clavewifi" id="clavewifi"
                                   class="form-control" data-placeholder="">
                        </div>
                        <div class="col-lg-2 col-xs-12 text-center">
                            <i class="fa fa-asterisk"></i><br>Solo Routers Internet Infraestructuras
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-12 col-md-6 col-sm-6 text-center" id="btn-barcode2">-->
                        <!--                            <span class="btn btn-success" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->
                        <!--                        <div class="col-lg-1 col-xs-12"></div>-->


                    </div>
                    <br>
                    <div class="row pppoes" id="pppoes_ocultar">

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                            <label>PPPoE Profile</label>
                            <select name="pppoe_profile" id="pppoe_profile" class="form-control pointer">
                            </select>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class=" col-xs-6">
                                <label class="visible-xs" style="margin-top:20px;">Usuario PPPoE</label>
                                <label class="hidden-xs">Usuario PPPoE</label>
                                <input type="text" name="user_pppoe" id="user_pppoe" class="form-control">
                            </div>
                            <div class="col-xs-6">
                                <label class="visible-xs" style="margin-top:20px;">Password PPPoE</label>
                                <label class="hidden-xs">Password PPPoE</label>
                                <input type="text" name="pass_pppoe" id="pass_pppoe" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="col-xs-4  voip_text" style="display:none">
                                <label class="visible-xs" style="margin-top:20px;">Usuario Voz Ip</label>
                                <label class="hidden-xs">Usuario Voz Ip</label>
                                <input type="text" name="user_vozip" id="user_vozip" class="form-control">
                            </div>
                            <div class="col-xs-4  voip_text"  style="display:none">
                                <label class="visible-xs" style="margin-top:20px;">Password Voz Ip</label>
                                <label class="hidden-xs">Password Voz Ip</label>
                                <input type="text" name="pass_vozip" id="pass_vozip" class="form-control">
                            </div>
                            <div class="col-xs-4  voip_text"  style="display:none">
                                <label class="visible-xs" style="margin-top:20px;">Télefono fijo</label>
                                <label class="hidden-xs">Télefono fijo</label>
                                <input type="tel" name="num_tel" id="num_tel" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="row chasis">
                        <br>
                        <div class="col-lg-2 col-xs-4">
                            <label>Chasis</label>
                            <select name="c" id="c" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Tarjeta</label>
                            <select name="t" id="t" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Pon</label>
                            <select name="p" id="p" class="form-control pointer required">

                            </select>
                        </div>
                        <br class="visible-xs"><br class="visible-xs">

                        <div class="col-lg-2 col-xs-6">
                            <label>Nº Caja</label>
                            <input type="text" name="caja" id="caja" placeholder="0.0"
                                   value="<?php echo $_COOKIE['caja']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <label>Puerto</label>
                            <input type="number" name="puerto" id="puerto" min="0" max="999"
                                   value="<?php echo $_COOKIE['puerto']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-12 text-right">
                            <a href="#" id="activar-internet" style="width:100%;margin-top:25px" class="btn btn-blue">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-internet-explorer"></i>
                            </a>
                        </div>
                    </div>


                </div>
            </div>

            <div class="panel panel-default" id="panel_voip" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos voz ip</strong>
                </div>
                <div class="panel-borde" style="height:8px"></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Modelo Ont</label>
                            <select name="vo-ont-profile" id="vo-ont-profile"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-md-2 col-xs-6">
                            <label>Gestionada Por</label>
                            <select name="vo-gestionada" id="vo-gestionada"
                                    class="form-control pointer required" onchange="gestionada(this.value);">
                                <option value="0" selected>OLT</option>
                                <?php if ($_COOKIE['asignada'] == 1)
                                    echo '<option value="1" selected>ROUTER EXTERNO</option>';
                                else
                                    echo '<option value="1">ROUTER EXTERNO</option>';
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6 hidden">
                            <label>Velocidad Subida</label>
                            <select name="vo-velocidad_up" id="vo-velocidad_up"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6 hidden">
                            <label>Velocidad Bajada</label>
                            <select name="vo-velocidad_dw" id="vo-velocidad_dw"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Tipo Ip</label>
                            <select name="vo-tipoip" id="vo-tipoip" onchange="tipoip(this.value)"
                                    class="form-control pointer required" disabled="disabled">
                                <option value="0">Dinámica</option>
                                <option value="1">Fija</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Asignación Ip</label>
                            <select name="vo-asignaip" id="vo-asignaip"
                                    class="form-control pointer required" disabled="disabled">
                                <option value="0">OTN</option>
                                <option value="1">PPPOE</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-3 col-xs-12">
                            <label>Número PON ONT</label>
                            <input type="text" name="vo-serial_pon" id="vo-serial_pon"
                                   class="required form-control masked" data-format="****************"
                                   data-placeholder="_" placeholder="16 Caracteres (puede buscar auto o usar lector)">
                        </div>

                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center" id="vo-btn-autofind">
                            <span class="btn btn-primary" style="margin-top:25px; width:100%; z-index:400000">
                                <i class="fa fa-2x fa-search"></i>

                            </span>
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center">-->
                        <!--                            <span class="btn btn-success" id="vo-btn-barcode2" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->


                        <div class="col-lg-3 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Número de Serie ONT</label>
                            <label class="hidden-xs">Número de Serie ONT</label>
                            <input type="text" name="vo-serial" id="vo-serial" class="required form-control"
                                   placeholder="010118IPTV12123456">
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-12 col-md-6 col-sm-6 text-center" id="vo-btn-barcode2">-->
                        <!--                            <span class="btn btn-success" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->
                        <!--                        <div class="col-lg-1 col-xs-12"></div>-->

                        <div class="col-lg-3 col-xs-12" id="vo-aqui_vpn">

                        </div>
                    </div>

                    <div class="row">
                        <br>
                        <div class="col-lg-2 col-xs-4">
                            <label>Chasis</label>
                            <select name="vo-c" id="vo-c" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Tarjeta</label>
                            <select name="vo-t" id="vo-t" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Pon</label>
                            <select name="vo-p" id="vo-p" class="form-control pointer required">

                            </select>
                        </div>

                        <div class="col-lg-2 col-xs-6">
                            <br class="visible-xs"><br class="visible-xs">
                            <label>Nº Caja</label>
                            <input type="text" name="vo-caja" id="vo-caja" placeholder="0.0"
                                   value="<?php echo $_COOKIE['caja']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <label>Puerto</label>
                            <input type="number" name="vo-puerto" id="vo-puerto" min="0" max="999"
                                   value="<?php echo $_COOKIE['puerto']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-12 text-right">
                            <a href="#" id="activar-voip" style="width:100%;margin-top:25px" class="btn btn-blue">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-phone"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="idenolt" value="">
            </div>

            <div class="panel panel-default" id="panel_iptv" style="display:none">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Datos Iptv</strong>
                </div>
                <div class="panel-borde" style="height:8px"></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Modelo Ont</label>
                            <select name="tv-ont-profile" id="tv-ont-profile"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Gestionada Por</label>
                            <select name="tv-gestionada" id="tv-gestionada"
                                    class="form-control pointer required" onchange="gestionada(this.value);">
                                <option value="0" selected>OLT</option>
                                <?php if ($_COOKIE['asignada'] == 1)
                                    echo '<option value="1" selected>ROUTER EXTERNO</option>';
                                else
                                    echo '<option value="1">ROUTER EXTERNO</option>';
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Subida</label>
                            <select name="tv-velocidad_up" id="tv-velocidad_up"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Velocidad Bajada</label>
                            <select name="tv-velocidad_dw" id="tv-velocidad_dw"
                                    class="form-control pointer required">
                                <option value="">Seleccionar una</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Tipo Ip</label>
                            <select name="tv-tipoip" id="tv-tipoip" onchange="tipoip(this.value)"
                                    class="form-control pointer required" disabled="disabled">
                                <option value="0">Dinámica</option>
                                <option value="1">Fija</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-md-2 col-xs-6">
                            <label>Asignación Ip</label>
                            <select name="tv-asignaip" id="tv-asignaip"
                                    class="form-control pointer required" disabled="disabled">
                                <option value="0">ONT</option>
                                <option value="1">PPPOE</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-12" id="tv-aqui_vpn">

                        </div>

                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-3 col-xs-12">
                            <label>Número PON ONT</label>
                            <input type="text" name="tv-serial_pon" id="tv-serial_pon"
                                   class="required form-control masked" data-format="****************"
                                   data-placeholder="_" placeholder="16 Caracteres (puede buscar auto o usar lector)">
                        </div>

                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center" id="tv-btn-autofind">
                            <span class="btn btn-info" style="margin-top:25px; width:100%; z-index:400000">
                                <i class="fa fa-2x fa-search"></i>

                            </span>
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-6 col-md-6 col-sm-6 text-center">-->
                        <!--                            <span class="btn btn-success" id="tv-btn-barcode2" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->


                        <div class="col-lg-3 col-xs-12">
                            <label class="visible-xs" style="margin-top:20px;">Número de Serie ONT</label>
                            <label class="hidden-xs">Número de Serie ONT</label>
                            <input type="text" name="tv-serial" id="tv-serial" class="required form-control"
                                   placeholder="010118IPTV12123456">
                        </div>

                        <!--                        <div class="col-lg-1 col-xs-12 col-md-6 col-sm-6 text-center" id="tv-btn-barcode2">-->
                        <!--                            <span class="btn btn-success" style="margin-top:25px; width:100%; z-index:400000"> <i class="fa fa-2x fa-barcode"></i> </span>-->
                        <!--                        </div>-->
                        <!--                        <div class="col-lg-1 col-xs-12"></div>-->


                    </div>
                    <div class="row">
                        <br>
                        <div class="col-lg-2 col-xs-4">
                            <label>Chasis</label>
                            <select name="tv-c" id="tv-c" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Tarjeta</label>
                            <select name="tv-t" id="tv-t" class="form-control pointer required">

                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-4">
                            <label>Pon</label>
                            <select name="tv-p" id="tv-p" class="form-control pointer required">

                            </select>
                        </div>

                        <div class="col-lg-2 col-xs-6">
                            <br class="visible-xs"><br class="visible-xs">
                            <label>Nº Caja</label>
                            <input type="text" name="tv-caja" id="tv-caja" placeholder="0.0"
                                   value="<?php echo $_COOKIE['caja']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-6">
                            <label>Puerto</label>
                            <input type="number" name="tv-puerto" id="tv-puerto" min="0" max="999"
                                   value="<?php echo $_COOKIE['puerto']; ?>"
                                   class="form-control pointer required">
                        </div>
                        <div class="col-lg-2 col-xs-12 text-right">
                            <a href="#" id="tv-activar-iptv" style="width:100%;margin-top:25px" class="btn btn-blue">
                                <span>Revisar y Activar</span>
                                <i class="fa fa-television"></i>
                            </a>
                        </div>
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


<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="padding:5%">
            <div class="text-center">
                <h3 class="text-danger text-red">Por favor revisa los datos</h3>
                <img src="img/ont.png" class="img-responsive" id="img-provision">
                <img class="img-responsive" id="img-provision2">
                <br><br>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5" id="text_cliente"><b>Cliente:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_cliente"></div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Nº. PON Ont:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_pon"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Nº. Serie Ont:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_serial"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Modelo Ont:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_mod"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Vel. Subida</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_up"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Vel. Bajada</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_dw"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Caja:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_caja"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Chasis:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_c"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Tarjeta:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_t"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Pon:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_p"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-5"><b>Puerto:</b></div>
                <div class="col-lg-8 col-xs-7" id="prev_puerto"></div>
            </div>

            <div class="row">
                <br><br>
                <div class="col-lg-6 col-xs-12"></div>
                <div class="col-lg-3 col-xs-6 text-right">
                    <a href="#" id="" data-dismiss="modal" style="margin-top:25px" class="btn btn-danger">
                        <span>Cancelar</span>

                    </a>
                </div>
                <div class="col-lg-3 col-xs-6 text-right">
                    <a href="#" id="btn-enviar" onclick="enviar();" style="margin-top:25px" class="btn btn-success">
                        <span>Activar</span>
                    </a>
                </div>
            </div>
            <div id="trabajando" style="display:none">
                <span id="texto_trabajando">Realizando operaciones en los servidores, esto puede tardar.<br>Por
                favor espera.<br><br></span>
                <div class="progress skill-bar ">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="99"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <span class="skill">Progreso: <i class="val"></i></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <br>
                <center>
                    <span style="font-size:1em; color:red" id="msg_error"></span>
                </center>
            </div>
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
    var id_ini = 67;
    var sp_ini = 100;
    var encontradas = false;
    var servicio = 0;
    var abortado = false;
    var mac = '';

    function tipoip(seleccion) {
        if (seleccion == 1) {
            $("#aqui_vpn").css('display', 'block');
            $("#aqui_vpn").empty();
            $("#aqui_vpn").append('<div class="col-xs-6">' +
                '<label>Ip</label><input type="text" name="ip_fija" id="ip_fija" class="required form-control" placeholder="10.25.36.50">' +
                '</div><div class="col-xs-6">' +
                '<label>Máscara</label><input type="text" name="mascara" id="mascara" class="required form-control" value="255.255.255.0"></div>');
        } else {
            $("#aqui_vpn").empty();
            $("#aqui_vpn").css('display', 'none');
        }
    }

    // funcion que habilita o desabilita el combo de gestionada por en funcion del parametro recibido
    function gestionada(value) {
        return;
        if (value == 1) {
            // $("#velocidad").attr("disabled", false);
            $("#tipoip").attr("disabled", false);
            $("#asignaip").attr("disabled", false);
            $("#veldw_ocultar").css("display", 'none');
            $("#velup_ocultar").css("display", 'none');
            $("#pppoes_ocultar").css("display", 'block');

        } else {
            // $("#velocidad").attr("disabled", true);
            $("#tipoip").attr("disabled", true);
            $("#asignaip").attr("disabled", true);
            $("#veldw_ocultar").css("display", 'block');
            $("#velup_ocultar").css("display", 'block');
            $("#pppoes_ocultar").css("display", 'none');

        }
    }

    // funcion que habilita o desabilita los select correspondiente a la gestion de velocidades
    function asignada(value) {
        return;
        if (value == 1) {
            $("#pppoes_ocultar").css("display", 'block');
            $("#veldw_ocultar").css("display", 'none');
            $("#velup_ocultar").css("display", 'none');

        } else {
            $("#pppoes_ocultar").css("display", 'none');
            $("#veldw_ocultar").css("display", 'block');
            $("#velup_ocultar").css("display", 'block');

        }
    }


    // cuando se pulsa el checkbox de aprovisionar internet

    $("#internet_btn, #iptv_btn").bind('click', function () {
        var nuevo_nom = $('#nuevo_nom').val();
        var clienteselected = $("#cliente").val();

        // si no se ha seleccionado un cliente o teclado un nombre y apellido, aviso y retorno

        if (clienteselected == null && nuevo_nom == '') {
            alert("Debes seleccionar o teclear un cliente");
            $("#internet_btn").prop('checked', false);
            return;
        }

        // no se puede aprovisionar internet al mismo tiempo que una vpn
        // asi que cuando se activa internet, desactivo vpn y viceversa
        $("#vpn_btn").prop('checked', false);


        //cuento los servicios que se han marcado para activar

        var cuantos = 0;
        if ($("#internet_btn").prop('checked') == true)
            cuantos = cuantos + 1;
        if ($("#iptv_btn").prop('checked') == true)
            cuantos = cuantos + 1;
        if ($("#voip_btn").prop('checked') == true)
            cuantos = cuantos + 1;
        if ($("#vpn_btn").prop('checked') == true)
            cuantos = cuantos + 1;

        // si se ha activado alguno, muestro el panel de internet
        if (cuantos > 0)
            $("#panel_internet").css('display', 'block');
        else
            $("#panel_internet").css('display', 'none');


        // si no los oculto todos
        if (cuantos == 0) {
            $("#panel_internet").css('display', 'none');
            $("#panel_iptv").css('display', 'none');
            $("#panel_voip").css('display', 'none');
            $(".panel-borde").css('background-color', '#fff');
        }


        $("#panel_iptv").css('display', 'none');
        $("#panel_voip").css('display', 'none');
        $(".panel-borde").css('background-color', '#c9c9c9');
        servicio = 1;

        // cargo una imagen de un router en la ventana modal que se mostrará cuando se pulse revisar y aprovisionar
        if ($("#voip_btn").prop('checked') == true) {
            $("#img-provision").attr('src', 'img/ont.png');
            $("#img-provision2").attr('src', 'img/telefono.png');
        } else {
            $("#img-provision").attr('src', 'img/ont.png');
            $("#img-provision").attr('src', '');
        }

        // limpio el body de esa modal
        $("#aqui_vpn").empty();
        // limpio la modal
        $("#activar-internet").empty();
        $("#activar-internet").append('<span>Revisar y Activar </span><i class="fa fa-internet-explorer"></i>');
    });

    $("#voip_btn").bind('click', function () {
        if(this.checked)
            $(".voip_text").css('display', 'block');
        else
            $(".voip_text").css('display', 'none');

    });

    $("#vpn_btn").bind('click', function () {


        var nuevo_nom = $('#nuevo_nom').val();
        var clienteselected = $("#cliente").val();

        // if(clienteselected==null && nuevo_nom==''){
        //     alert("Debes seleccionar o teclear un cliente");
        //     return;
        // }
        $("#internet_btn").prop('checked', false);
        $("#voip_btn").prop('checked', false);
        $("#iptv_btn").prop('checked', false);


        if ($("#vpn_btn").prop('checked') == true)
            $("#panel_internet").css('display', 'block');
        else
            $("#panel_internet").css('display', 'none');

        $("#panel_iptv").css('display', 'none');
        $("#panel_voip").css('display', 'none');
        $(".panel-borde").css('background-color', '#c9c9c9');

        // indica que se va a aprovisionar una vpn, esto se diferencia de un alta normal
        // en que la velocidad es la maxima posible ya que se trata de un servicio especial
        // para una red desde la que se da servicio por wifi
        // ademas hay que activar un service port y unas prioridades diferentes

        servicio = 4;

        $("#velocidad_dw option:last").attr("selected", "selected");
        $("#velocidad_up option:last").attr("selected", "selected");
        $("#aqui_vpn").empty();
        $("#aqui_vpn").css('display', 'block');

        $("#img-provision").attr('src', 'img/antena.png');
        $("#aqui_vpn").append('<label class="visible-xs" style="margin-top:20px;">Descripción de la VPN</label>\n' +
            '                            <label class="hidden-xs">Descripción de la VPN</label>\n' +
            '                            <input type="text" name="desc_vpn" id="desc_vpn" class="required form-control" placeholder="Estación Wifi">');
        $("#activar-internet").empty();
        $("#activar-internet").append('<span>Revisar y Activar </span>\n' +
            '                                <i class="fa fa-wifi"></i>');

    });


    $("#activar-internet").bind('click', function () {

        var serie = $("#serial").val();
        var lprof = $("#ont-profile").val();
        var olt = $("#cabecera").val();
        var up = $("#velocidad_up option:selected").text();
        var dw = $("#velocidad_dw option:selected").text();
        var idcliente = $("#cliente").val();
        var caja = $("#caja").val();
        var c = $("#c").val();
        var t = $("#t").val();
        var p = $("#p").val();
        var puerto = $("#puerto").val();
        var npon = $("#serial_pon").val();
        var clavewifi = $("#clavewifi").val();

        // pppoe
        var userpppoe = $("#user_pppoe").val();
        var passpppoe = $("#pass_pppoe").val();

        // vozip
        var uservoip = $("#user_vozip").val();
        var passvoip = $("#pass_vozip").val();
        var numtel = $("#num_tel").val();

        $("#prev_serial").html(serie);
        $("#prev_pon").html(npon);
        $("#prev_mod").html(lprof);
        $("#prev_up").html(up);
        $("#prev_dw").html(dw);
        $("#prev_caja").html(caja);
        $("#prev_c").html(c);
        $("#prev_t").html(t);
        $("#prev_p").html(p);
        $("#prev_puerto").html(puerto);

        var nuevo_nom = $('#nuevo_nom').val();


        var mensaje = "";



        if (olt == '') {
            mensaje = mensaje + "Debes seleccionar una cabecera\n";
        }

        if (servicio == 4) {
            $("#prev_cliente").html($("#desc_vpn").val());
            $("#text_cliente").html("<b>Descripcion de la Vpn:<b>");

            var desc_vpn = $("#desc_vpn").val();
            if (desc_vpn == '' || desc_vpn == null) {
                mensaje = mensaje + "Debes teclear la descripción de la VPN\n";
            }
        } else {
            $("#text_cliente").html("<b>Cliente:<b>");
            if ((idcliente == '' || idcliente == null) && (nuevo_nom == '')) {
                mensaje = mensaje + "Debes seleccionar o teclear un cliente\n";
            }
            if (nuevo_nom == '')
                $("#prev_cliente").html($("#cliente option:selected").text());
            else
                $("#prev_cliente").html(nuevo_nom);

        }

        if (lprof == '' || lprof == null) {
            mensaje = mensaje + "Debes seleccionar el modelo de la ONT\n";
        }


        if (serie == '') {
            mensaje = mensaje + "Debes teclear el número de SERIE de la ONT\n";
        }
        if (npon == '') {
            mensaje = mensaje + "Debes teclear el número de PON de la ONT\n";
        }

        if (npon.length != 16) {
            mensaje = mensaje + "Número de serie de la ONT incorrecto\n";
        }

        if (up == '' || up == null) {
            mensaje = mensaje + "Debes seleccionar la velocidad de subida\n";
        }
        if (dw == '' || dw == null) {
            mensaje = mensaje + "Debes seleccionar la velocidad de bajada\n";
        }
        if (caja == '') {
            mensaje = mensaje + "Falta el número de caja\n";
        }
        if (c == '' || c == null) {
            mensaje = mensaje + "Falta el número de chasis\n";
        }
        if (t == '' || t == null) {
            mensaje = mensaje + "Falta el número de Tarjeta\n";
        }
        if (p == '' || p == null) {
            mensaje = mensaje + "Falta el número de pon\n";
        }
        if (puerto == '') {
            mensaje = mensaje + "Falta el número de puerto\n";
        }

        if (clavewifi.length < 8 && clavewifi.length > 0) {
            mensaje = mensaje + "La clave wifi debe tener al menos 8 caracteres\n";
        }

        if ($('#voip_btn').is(':checked')) {
            if(uservoip == '' || passvoip == '')
                mensaje = mensaje + "Si desea aprovisionar la Voz IP, debe especificar el usuario y clave del proveedor";
        }



        if (mensaje != '') {
            alert(mensaje);
            return;
        } else {
            $("#ajax").modal();
        }
        //enviar();

    });

    $("#activar-voip").bind('click', function () {

        var serie = $("#vo-serial").val();
        var lprof = $("#vo-ont-profile").val();
        var olt = $("#cabecera").val();
        var up = 150;
        var dw = 150;
        var idcliente = $("#vo-cliente").val();
        var caja = $("#vo-caja").val();
        var c = $("#vo-c").val();
        var t = $("#vo-t").val();
        var p = $("#vo-p").val();
        var puerto = $("#vo-puerto").val();
        var npon = $("#vo-serial_pon").val();
        var clavewifi = $("#clavewifi").val();


        $("#prev_serial").html(serie);
        $("#prev_pon").html(npon);
        $("#prev_mod").html(lprof);
        $("#prev_up").html(up);
        $("#prev_dw").html(dw);
        $("#prev_caja").html(caja);
        $("#prev_c").html(c);
        $("#prev_t").html(t);
        $("#prev_p").html(p);
        $("#prev_puerto").html(puerto);


        var nuevo_nom = $('#nuevo_nom').val() + " " + $('#nuevo_ape').val();


        var mensaje = "";

        if (olt == '') {
            mensaje = mensaje + "Debes seleccionar una cabecera\n";
        }


        $("#text_cliente").html("<b>Cliente:<b>");
        if ((idcliente == '' || idcliente == null) && (nuevo_nom == '')) {
            mensaje = mensaje + "Debes seleccionar o teclear un cliente\n";
        }
        if (nuevo_nom == '')
            $("#prev_cliente").html($("#cliente option:selected").text());
        else
            $("#prev_cliente").html(nuevo_nom);


        if (lprof == '' || lprof == null) {
            mensaje = mensaje + "Debes seleccionar el modelo de la ONT\n";
        }


        if (serie == '') {
            mensaje = mensaje + "Debes teclear el número de SERIE de la ONT\n";
        }
        if (npon == '') {
            mensaje = mensaje + "Debes teclear el número de PON de la ONT\n";
        }

        if (npon.length != 16) {
            mensaje = mensaje + "Número de serie de la ONT incorrecto\n";
        }


        if (caja == '') {
            mensaje = mensaje + "Falta el número de caja\n";
        }
        if (c == '' || c == null) {
            mensaje = mensaje + "Falta el número de chasis\n";
        }
        if (t == '' || t == null) {
            mensaje = mensaje + "Falta el número de Tarjeta\n";
        }
        if (p == '' || p == null) {
            mensaje = mensaje + "Falta el número de pon\n";
        }
        if (puerto == '') {
            mensaje = mensaje + "Falta el número de puerto\n";
        }

        if (mensaje != '') {
            alert(mensaje);
            return;
        } else {
            $("#ajax").modal();
        }

    });


    function cerrar_modal() {
        //       $("#resultado_modal").hide(1000);
        $(".modal-backdrop").css("display", "none");
    }

    // *********************************************************************
    // cuando se pulsa el boton de guardar los datos
    //**********************************************************************
    function enviar() {

        $("#msg_error").text('');
        $("#btn-enviar").attr('disabled', 'disabled');

        // pppoe
        var userpppoe = $("#user_pppoe").val();
        var passpppoe = $("#pass_pppoe").val();

        // vozip
        var uservoip = $("#user_vozip").val();
        var passvoip = $("#pass_vozip").val();
        var numtel = $("#num_tel").val();


        if (servicio == 1 || servicio == 4) {
            var serie = $("#serial").val();
            var lprof = $("#ont-profile").val();
            var olt = $("#cabecera").val();
            var up = $("#velocidad_up").val();
            var dw = $("#velocidad_dw").val();
            var idcliente = $("#cliente").val();
            var caja = $("#caja").val();
            var c = $("#c").val();
            var t = $("#t").val();
            var p = $("#p").val();
            var puerto = $("#puerto").val();
            var npon = $("#serial_pon").val();
            var nuevo_nom = $("#nuevo_nom").val();
            var nuevo_ape = $('#nuevo_ape').val();
            var idenolt = 0;
            var gestionada = $("#gestionada").val();
            var tipoip = $("#tipoip").val();
            var asignada = $("#asignaip").val();
            var ssid = $("#ssid").val();
            var clavewifi = $("#clavewifi").val();
            var pppoe_profile = $("#pppoe_profile").val();
        }

        if (servicio == 2) {
            var serie = $("#serial").val();
            var lprof = $("#ont-profile").val();
            var olt = $("#cabecera").val();
            var up = $("#velocidad_up").val();
            var dw = $("#velocidad_dw").val();
            var idcliente = $("#cliente").val();
            var caja = $("#caja").val();
            var c = $("#c").val();
            var t = $("#t").val();
            var p = $("#p").val();
            var puerto = $("#puerto").val();
            var npon = $("#serial_pon").val();
            var nuevo_nom = $("#nuevo_nom").val();
            var nuevo_ape = $('#nuevo_ape').val();
            var idenolt = $('#idenolt').val();
            var gestionada = $("#gestionada").val();
            var tipoip = $("#tipoip").val();
            var asignada = $("#asignaip").val();
            var ssid = $("#ssid").val();
            var clavewifi = $("#clavewifi").val();
        }

        if (servicio == 3) {
            var serie = $("#vo-serial").val();
            var lprof = $("#vo-ont-profile").val();
            var olt = $("#cabecera").val();
            var up = 150;
            var dw = 150;
            var idcliente = $("#vo-cliente").val();
            var caja = $("#vo-caja").val();
            var c = $("#vo-c").val();
            var t = $("#vo-t").val();
            var p = $("#vo-p").val();
            var puerto = $("#vo-puerto").val();
            var npon = $("#vo-serial_pon").val();
            var nuevo_nom = $("#vo-nuevo_nom").val();
            var nuevo_ape = $('#nuevo_ape').val();
            var idenolt = 0;
            var gestionada = $("#vo-gestionada").val();
            var tipoip = $("#vo-tipoip").val();
            var asignada = $("#vo-asignaip").val();
            var ssid = $("#ssid").val();
            var clavewifi = $("#clavewifi").val();
        }

        var act_internet = $("#internet_btn").prop('checked');
        var act_tv = $("#iptv_btn").prop('checked');
        var act_voz = $("#voip_btn").prop('checked');
        var act_vpn = $("#vpn_btn").prop('checked');

        var ipfija = $("#ip_fija").val();
        var mascara = $("#mascara").val();

        $("#trabajando").css('display', 'block');
        $("#texto_trabajando").html('Realizando operaciones en los servidores, esto puede tardar.<br>Por favor espera.<br><br><br>');
        $.featherlight("#trabajando").defaults;

        $('.progress .progress-bar').css("width",
            function () {
                return "0%";
            }
        );

        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "10%";
                }
            );
        }, 5000);

        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "20%";
                }
            );
        }, 10000);

        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "40%";
                }
            );
        }, 20000);
        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "60%";
                }
            );
        }, 30000);
        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "80%";
                }
            );
        }, 40000);
        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "90%";
                }
            );
        }, 50000);
        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "95%";
                }
            );
        }, 60000);
        setTimeout(function () {
            $('.progress .progress-bar').css("width",
                function () {
                    return "99%";
                }
            );
        }, 85000);
        setTimeout(function () {
            abortar();
        }, 900000);


        var comando = "alta";
        var servicios = 100;

        if (servicio == 4) {
            var cliente = $("#desc_vpn").val();
            servicios = 500;
        } else {
            if (nuevo_nom == '')
                var cliente = $("#nomcli").val() + "_" + $("#apecli").val() + "_" + $("#telcli").val();
            else
                var cliente = nuevo_nom + " " + nuevo_ape;
        }

        console.log('enviando: ' + olt + " " + comando
            + " " + serie + " " + lprof + " " + lprof + " " + cliente + " " + servicios + " " + up + " " + dw + " " + caja
            + " " + c + " " + t + " " + p + " " + puerto + " " + idcliente + " " + id_ini + " " + sp_ini + " " + npon + " " + servicio
            + " " + nuevo_nom + " " + nuevo_ape);


        $.ajax({
            url: 'telnet_.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                olt: olt,
                command: comando,
                serial: serie,
                lineprofile: lprof,
                serverprofile: lprof,
                descrp: cliente,
                servicio: servicios,
                up: up,
                dw: dw,
                caja: caja,
                c: c,
                t: t,
                p: p,
                lat: '',
                lon: '',
                puerto: puerto,
                idcliente: idcliente,
                id_ini: id_ini,
                sp_ini: sp_ini,
                num_pon: npon,
                vpn: servicio,
                nuevo_nom: nuevo_nom,
                nuevo_ape: nuevo_ape,
                idenolt: idenolt,
                act_internet: act_internet,
                act_voz: act_voz,
                act_tv: act_tv,
                act_vpn: act_vpn,
                gestionada: gestionada,
                tipoip: tipoip,
                asignada: asignada,
                ipfija: ipfija,
                mascara: mascara,
                ssid: ssid,
                clavewifi: clavewifi,
                pppoe_profile: pppoe_profile,
                userpppoe : userpppoe,
                passpppoe : passpppoe,
                uservoip : uservoip,
                passvoip : passvoip,
                numtel : numtel

    },
            success: function (data) {
                $("#contenido_resultado").empty();
                var error_content = "<center>";

                var datos = data[0].result;
                var rx = data[0].rx;
                var tx = data[0].tx;
                var marca = data[0].marca;
                var temp = data[0].temp;
                var volt = data[0].volt;
                var rxolt = data[0].rx_olt;
                mac = data[0].mac;


                if (datos == 0 && parseInt(temp) > 0) {
                    error_content += "<img src='img/ok.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:darkgreen'>CORRECTO</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Operación realizada correctamente</span><br><br>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "RX dBm:</div><div class='col-lg-7 text-left'>" + rx + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "TX dBm:</div><div class='col-lg-7 text-left'>" + tx + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "RX Olt:</div><div class='col-lg-7 text-left'>" + rxolt + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "Volt:</div><div class='col-lg-7 text-left'>" + volt + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "Temp:</div><div class='col-lg-7 text-left'>" + temp + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "Marca:</div><div class='col-lg-7 text-left'>" + marca + "</div>";
                    error_content += "<div class='col-lg-5 text-left'>";
                    error_content += "Mac:</div><div class='col-lg-7 text-left'>" + mac + "</div>";


                } else if (datos == 0) {
                    error_content += "<img src='img/ok.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:darkgreen'>CORRECTO</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Operación realizada correctamente</span><br><br>";
                }
                else if (datos == -2) {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:red'>ERROR: 200:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>No queda espacio libre en ese pon<br>Superado el limite de 128</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Revisa o elimina las<br>no usadas</span><br><br>";
                }
                else if (datos == 1) {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:red'>ERROR: 301:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Ese numero PON se <br>encuentra dado de alta</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Intenta darlo de baja<br>o usar otra ONT</span><br><br>";
                }
                else if (datos == 2) {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:red'>ERROR: 302:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Faltan datos de configuración<br>en la cabecera</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Indica al administrador<br>el error 302</span><br><br>";
                }
                else if (datos == 3) {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:red'>ERROR: 303:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Alguna de las velocidades seleccionadas<br> no están disponibles en esta cabecera</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Selecciona otra velocidad o solicita al<br>administrador el alta de dicha velocidad</span><br><br>";
                }
                else if (datos == 4) {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:red'>ERROR: 304:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Error asignando espacio<br></span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Intenta Borrar esta ont en el menú:<br>Aprovisionar->Bajas<br> Después puedes intentarlo de nuevo</span><br><br>";
                }
                else if (datos == 6) {
                    error_content += "<img src='img/warning.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:orange'>ALERTA: 106:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Ont aprovisionada correctamente<br>pero se encuentra OFFLINE</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Revisar alimentación<br>y conector de fibra</span><br><br>";
                }
                else {
                    error_content += "<img src='img/error.png'><br><br>";
                    error_content += "<span style='font-size:2em;color:orange'>ERROR: 000:</span><br>";
                    error_content += "<span style='font-size:1.4em;color:#000'>Error no controlado</span><br><br>";
                    error_content += "<img src='img/ayuda.png'><br>";
                    error_content += "<span style='font-size:1.4em;color:orangered'>Indique al administrador<br>el número de error,<br> Gracias</span><br><br>";
                }

                error_content += "</center><br><br><br><br><right>";

                if (datos == 0)
                    error_content += '<span data-dismiss="modal" onclick="location.reload();" class="btn btn-success"><span> Continuar </span><i class="fa fa-close"></i></span></right>';
                else
                    error_content += '<span data-dismiss="modal" onclick="cerrar_modal();" class="btn btn-danger"><span> Salir </span><i class="fa fa-close"></i></span></right>';

                $("#contenido_resultado").append(error_content);


            }, complete(data) {
                $.ajax({
                    url: 'php/parametros_tr069.php',
                    type: 'POST',
                    cache: false,
                    async: true,
                    data: {
                        olt: olt,
                        pon: npon,
                        ssid: ssid,
                        clavewifi: clavewifi,
                        mac: mac,
                        act_voz: act_voz
                    },
                    complete: function () {
                        $.featherlight.close();
                        $("#trabajando").css('display', 'none');
                        $("#ajax").modal('hide');
                        $("#resultado_modal").modal();
                        $("#resultado_modal").css('z-index', '10000');
                        abortado = true;
                    }
                });

            }
        });

    }


    function abortar() {
        if (abortado)
            return;

        $.featherlight.close();
        $("#trabajando").css('display', 'none');
        $("#ajax").modal('hide');
        $("#resultado_modal").modal();
        $("#resultado_modal").css('z-index', '10000');
    }

    // cada vez qeu se selecciona un cliente del combo, cargamos los datos del mismo

    function cambiarcliente(id) {

        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                idcliente: id
            },
            success: function (datos) {

                $("#linea_datos_clientes").css('display', 'block');
                $("#nom_selected").html(datos[0].nombre);
                $("#ape_selected").html(datos[0].apellidos);
                $("#dir_selected").html(datos[0].direccion);
                $("#tel_selected").html(datos[0].tel1 + " / " + datos[0].tel2);
                $("#nomcli").val(datos[0].nombre);
                $("#apecli").val(datos[0].apellidos);
                if (datos[0].tel1 != '')
                    $("#telcli").val(datos[0].tel1);
                else
                    $("#telcli").val(datos[0].tel2);

                $("#serial_pon").val('');
                $("#tv-serial_pon").val('');
                $("#vo-serial_pon").val('');

                $("#serial").val('');
                $("#tv-serial").val('');
                $("#vo-serial").val('');

                $("#c").val(0).change();
                $("#t").val(0).change();
                $("#p").val(0).change();

                $("#tv-c").val(0).change();
                $("#tv-t").val(0).change();
                $("#tv-p").val(0).change();

                $("#vo-c").val(0).change();
                $("#vo-t").val(0).change();
                $("#vo-p").val(0).change();

                $("#serial_pon").val(datos[0].provision[5]);
                $("#tv-serial_pon").val(datos[0].provision[5]);
                $("#vo-serial_pon").val(datos[0].provision[5]);

                $("#caja").val(datos[0].provision[6]);
                $("#tv-caja").val(datos[0].provision[6]);
                $("#vo-caja").val(datos[0].provision[6]);

                $("#puerto").val(datos[0].provision[7]);
                $("#tv-puerto").val(datos[0].provision[7]);
                $("#vo-puerto").val(datos[0].provision[7]);


                $("#serial").val(datos[0].provision[4]);
                $("#tv-serial").val(datos[0].provision[4]);
                $("#vo-serial").val(datos[0].provision[4]);

                $("#c").val(parseInt(datos[0].provision[1])).change();
                $("#t").val(parseInt(datos[0].provision[2])).change();
                $("#p").val(parseInt(datos[0].provision[3])).change();

                $("#tv-c").val(parseInt(datos[0].provision[1])).change();
                $("#tv-t").val(parseInt(datos[0].provision[2])).change();
                $("#tv-p").val(parseInt(datos[0].provision[3])).change();

                $("#vo-c").val(parseInt(datos[0].provision[1])).change();
                $("#vo-t").val(parseInt(datos[0].provision[2])).change();
                $("#vo-p").val(parseInt(datos[0].provision[3])).change();

                $("#idenolt").val(parseInt(datos[0].provision[0])).change();


            }
        });
    }

    function select_cabecera(id) {

        $('#velocidad_up').empty();
        $('#velocidad_dw').empty();

        $('#tv-velocidad_up').empty();
        $('#tv-velocidad_dw').empty();

        $('#vo-velocidad_up').empty();
        $('#vo-velocidad_dw').empty();

        $('#pppoe_profile').empty();

        var up = <?php echo isset($_COOKIE["up"]) ? $_COOKIE["up"] : 0; ?>;
        var dw = <?php echo isset($_COOKIE["dw"]) ? $_COOKIE["dw"] : 0; ?>;
        var profile = '<?php echo isset($_COOKIE['profile']) ? $_COOKIE['profile'] : 0; ?>';

        $.ajax({
            url: 'carga_perfiles.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                for (var x = 0; x < data.length; x++) {

                    // internet
                    if (data[x].perfil_olt == up)
                        $('#velocidad_up')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#velocidad_up')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));

                    // ip tv

                    if (data[x].perfil_olt == up)
                        $('#tv-velocidad_up')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#tv-velocidad_up')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));

                    // vo ip

                    if (data[x].perfil_olt == up)
                        $('#vo-velocidad_up')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#vo-velocidad_up')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));


                    // internet

                    if (data[x].perfil_olt == dw)
                        $('#velocidad_dw')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#velocidad_dw')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));

                    // ip tv
                    if (data[x].perfil_olt == dw)
                        $('#tv-velocidad_dw')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#tv-velocidad_dw')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));

                    // vo ip
                    if (data[x].perfil_olt == dw)
                        $('#vo-velocidad_dw')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));
                    else
                        $('#vo-velocidad_dw')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil_olt)
                                .text(data[x].nombre_perfil));


                }
            }
        });

        $.ajax({
            url: 'carga_perfiles_ont.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $('#ont-profile').empty();
                $('#tv-ont-profile').empty();
                $('#vo-ont-profile').empty();
                for (var x = 0; x < data.length; x++) {
                    $('#ont-profile')
                        .append($("<option></option>")
                            .attr("value", data[x].nombre_perfil)
                            .text(data[x].nombre_perfil));

                    $('#tv-ont-profile')
                        .append($("<option></option>")
                            .attr("value", data[x].nombre_perfil)
                            .text(data[x].nombre_perfil));

                    $('#vo-ont-profile')
                        .append($("<option></option>")
                            .attr("value", data[x].nombre_perfil)
                            .text(data[x].nombre_perfil));
                }

            }
        });


        $.ajax({
            url: 'carga_datos_olt.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $('#c').empty();
                for (var x = 0; x < data[0].c; x++) {
                    $('#c')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#t').empty();
                for (var x = 0; x < data[0].t; x++) {
                    $('#t')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#p').empty();
                for (var x = 0; x < data[0].p; x++) {
                    $('#p')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }

                $('#tv-c').empty();
                for (var x = 0; x < data[0].c; x++) {
                    $('#tv-c')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#tv-t').empty();
                for (var x = 0; x < data[0].t; x++) {
                    $('#tv-t')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#tv-p').empty();
                for (var x = 0; x < data[0].p; x++) {
                    $('#tv-p')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }

                $('#vo-c').empty();
                for (var x = 0; x < data[0].c; x++) {
                    $('#vo-c')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#vo-t').empty();
                for (var x = 0; x < data[0].t; x++) {
                    $('#vo-t')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                $('#vo-p').empty();
                for (var x = 0; x < data[0].p; x++) {
                    $('#vo-p')
                        .append($("<option></option>")
                            .attr("value", x)
                            .text(x));
                }
                id_ini = data[0].id;
                sp_ini = data[0].sp;
            }
        });

        $.ajax({
            url: 'carga_perfiles_pppoe.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function (data) {
                $('#pppoe_profile').empty();

                for (var x = 0; x < data.length; x++) {
                    if (data[x].perfil == profile)
                        $('#pppoe_profile')
                            .append($("<option selected></option>")
                                .attr("value", data[x].perfil)
                                .text(data[x].descripcion));
                    else
                        $('#pppoe_profile')
                            .append($("<option></option>")
                                .attr("value", data[x].perfil)
                                .text(data[x].descripcion));
                }

            }
        });

    }

    $(document).ready(function () {
        select_cabecera($("#cabecera").val());
        cargar_clientes(false);
    });

    //******************************************************************************
    // el boton azul con la lupa carga esta funcion y esta llama a autofind.php
    // autofind.php devuelve un listado de las otn conectadas a la olt que aun no estan aprovisionadas
    // *****************************************************************************
    function btnautofind() {
        $("#encontradas").modal();

        $('#encontradas_content').empty();
        $('#encontradas_content').append('<center><img src="img/procesando.gif" id="img_procesando"><br><br><h3>Esto puede tardar hasta un minuto</h3></center>');
        $('#encontradas_content')
            .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

        var olt = $("#cabecera").val();
        if (olt == '') {
            alert("Debes seleccionar una cabecera");
        } else {
            setTimeout(function () {
                encontradas = false;
                no_se_encuentra_nada();
            }, 60000);

            $.ajax({
                url: 'autofind.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    olt: olt
                },
                success: function (datos) {


                    $('#encontradas_content').empty();

                    $('#encontradas_content').append($("<b><div class='row'>" +
                        "<div class='col-lg-2 col-xs-3'>C/T/P</div>" +
                        "<div class='col-lg-8 col-xs-6'>Número PON</div>" +
                        "<div class='col-lg-2 col-xs-3'></div></div></b>"
                    ));
                    if (datos.length > 0) {
                        for (var x = 0; x < datos.length; x++) {
                            $('#encontradas_content')
                                .append($("<div class='row'>" +
                                    "<div class='col-lg-2 col-xs-3'>" + datos[x].f[0] + "/" + datos[x].f[1] + "/" + datos[x].f[2] + "</div>" +
                                    "<div class='col-lg-8 col-xs-6'>" + datos[x].s + "</div>" +
                                    "<div class='col-lg-2 col-xs-3'><h2>" +
                                    "<button class='btn btn-info btn-xs' style='margin-top:-26px' data-dismiss='modal' onclick='asignar_este(\"" + datos[x].f[0].replace(/\s/g, '') + "\",\"" + datos[x].f[1].replace(/\s/g, '') + "\",\"" + datos[x].f[2].replace(/\s/g, '') + "\",\"" + datos[x].s.replace(/\s/g, '') + "\",\"" + datos[x].serie.replace(/\s/g, '') + "\");'> <i class='fa fa-2x fa-copy'></i> </button>" +
                                    "</h2></div></div>"
                                ));
                        }
                        encontradas = true;
                    }
                    else {
                        encontradas = true;
                        $('#encontradas_content').empty();
                        $('#encontradas_content')
                            .append($("<div class='row'>" +
                                "<div class='col-lg-12 text-center'><span style='color:red;font-weight: 700'>No se ha encontrado ninguna ONT</span></div>" +
                                "<div class='col-lg-12 text-center'>" +
                                "<button class='btn btn-warning' style='margin-top:30px' data-dismiss='modal' onclick='btnautofind();'> <i class='fa fa-2x fa-refresh'></i>Reintentar </button>" +
                                "</div></div>"
                            ));
                    }
                    $('#encontradas_content')
                        .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

                }
            });
        }
    }

    $("#btn-autofind").bind('click', function () {
        btnautofind();
    });

    $("#tv-btn-autofind").bind('click', function () {
        btnautofind();
    });

    $("#vo-btn-autofind").bind('click', function () {
        btnautofind();
    });


    $("#btn-barcode2").bind('click', function () {
        btnautofind();
    });

    //**********************************************************
    // cuando no se encuentra ninguna ont conectada

    function no_se_encuentra_nada() {
        if (encontradas == true)
            return;

        $('#encontradas_content').empty();
        $('#encontradas_content')
            .append('<center><span style="font-size:1.2em; color:red">Tiempo de espera agotado y no se ha encontrado una ONT</span></center>');

        $('#encontradas_content')
            .append($("<div class='row'>" +
                "<div class='col-lg-12 text-center'>" +
                "<button class='btn btn-warning' style='margin-top:30px' data-dismiss='modal' onclick='btnautofind();'> <i class='fa fa-2x fa-refresh'></i>Reintentar </button>" +
                "</div></div>"
            ));

        $('#encontradas_content')
            .append('<br><br><br><br><br><span id="" style="margin-top:25px" data-dismiss="modal" class="btn btn-danger"><span>Salir</span><i class="fa fa-close"></i></span>');

    }

    //**************************************************
    // cuando se muestran las ont en autofind y se pulsa el boton de copiar el numero pon

    function asignar_este(c, t, p, serial, serie) {
        $("#serial_pon").val('');
        $("#tv-serial_pon").val('');
        $("#vo-serial_pon").val('');


        $("#serial_pon").val(serial);
        $("#tv-serial_pon").val(serial);
        $("#vo-serial_pon").val(serial);


        $("#user_pppoe").val(serie);
        // $("#tv-user_pppoe").val(serial);
        // $("#vo-user_pppoe").val(serial);


        $("#pass_pppoe").val(serial+"**");
        // $("#tv-pass_pppoe").val(serial)+"**";
        // $("#vo-pass_pppoe").val(serial)+"**";


        $("#user_vozip").val(serial);
        // $("#tv-user_pppoe").val(serial);
        // $("#vo-user_pppoe").val(serial);


        $("#pass_vozip").val(serial);
        // $("#tv-pass_pppoe").val(serial)+"**";
        // $("#vo-pass_pppoe").val(serial)+"**";



        $("#serial").val(serie);
        $("#vo-serial").val(serie);
        $("#tv-serial").val(serie);


        $("#c").val(parseInt(c)).change();
        $("#t").val(parseInt(t)).change();
        $("#p").val(parseInt(p)).change();

        $("#tv-c").val(parseInt(c)).change();
        $("#tv-t").val(parseInt(t)).change();
        $("#tv-p").val(parseInt(p)).change();

        $("#vo-c").val(parseInt(c)).change();
        $("#vo-t").val(parseInt(t)).change();
        $("#vo-p").val(parseInt(p)).change();

        $("#c").attr('disabled',true);
        $("#t").attr('disabled',true);
        $("#p").attr('disabled',true);

        $("#encontradas").hide(1000);
    }

    function mostrartodos(estado) {

        cargar_clientes(estado);
    }

    function cargar_clientes(filtro) {
        $('#cliente').empty();
        $('#cliente').append("<option value='0' selected disabled>Seleccione uno</option>");
        var cli_desde_orden = <?php echo $idcliente;?>;

        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {filtro: filtro},
            success: function (datos) {
                if (datos.length > 0) {
                    for (var x = 0; x < datos.length; x++) {
                        if(parseInt(cli_desde_orden)==parseInt(datos[x].id)) {
                            $('#cliente').append("<option selected value='" + datos[x].id + "'>" + datos[x].apellidos + " " + datos[x].nombre + "</option>");
                            $('.ocultar_si_es_orden').css('display', 'none');
                            $('.mostrar_si_es_orden').css('display', 'block');

                        } else
                            $('#cliente').append("<option value='" + datos[x].id + "'>" + datos[x].apellidos + " " + datos[x].nombre + "</option>");
                    }
                }
            }
        });
    }
</script>

</body>
</html>