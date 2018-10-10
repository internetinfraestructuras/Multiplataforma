<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ PERMITE REALIZAR CONTRATOS DE TODOS LOS SERVICIOS ║
    ║ El procedimiento es seleccionar un cliente e ir   ║
    ║ Agregando servicios. Despues se generan las ordenes
    ║ de trabajos
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');

$util = new util();
check_session(2);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Contratación de servicios</title>
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
    <script type="text/javascript" src="js/utiles.js"></script>
    <link rel="stylesheet" href="content/firma/css/signature-pad.css">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>


        /*form styles*/
        #msform {
            text-align: left;
            position: relative;
            margin-top: 10px;
        }

        #msform fieldset {
            background: white;
            border: 1px solid #c9c9c9;
            border-radius: 2px;
            box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
            padding: 15px 15px;
            box-sizing: border-box;
            width: 96%;
            margin: 0 2%;

            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*inputs*/
        #msform input[type=text],#msform input[type=number],#msform input[type=email],#msform input[type=tel],#msform textarea {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 3px;
            margin-bottom: 7px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            font-size: 14px;
            font-family: 'Open Sans', Arial, sans-serif;
            font-weight: 300;
        }

        #msform input:focus, #msform textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 2px solid #1D9FC1;
            outline-width: 0;
            transition: All 0.5s ease-in;
            -webkit-transition: All 0.5s ease-in;
            -moz-transition: All 0.5s ease-in;
            -o-transition: All 0.5s ease-in;
        }

        /*buttons*/
        #msform .action-button {
            width: auto;
            min-width: 120px;
            background: #1D9FC1;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px 5px;
            /*margin: 10px 5px;*/
            bottom: 10px;
            position: absolute;
            right: 10px;
        }

        #msform .action-button:hover, #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #1D9FC1;
        }

        #msform label {
            font-weight: 600;
        }

        #msform .action-button-previous {
            width: auto;
            min-width: 120px;
            background: #bec3c9;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px 5px;
            /*margin: 10px 5px;*/
            bottom: 10px;
            position: absolute;
            left: 10px;

        }

        #msform .action-button-previous:hover, #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;
        }

        /*headings*/
        .fs-title {
            font-size: 18px;
            text-transform: uppercase;
            color: #2C3E50;
            margin-bottom: 10px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .fs-subtitle {
            font-weight: normal;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
            text-align: center;

        }

        #progressbar li {
            list-style-type: none;
            color: #0F0F5E;
            text-transform: uppercase;
            font-size: 9px;
            width: 16%;
            float: left;
            position: relative;
            letter-spacing: 1px;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 24px;
            height: 24px;
            line-height: 26px;
            display: block;
            font-size: 12px;
            color: #333;
            background: white;
            border-radius: 25px;
            margin: 0 auto 10px auto;
        }

        /*progressbar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: white;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1; /*put it behind the numbers*/
        }

        #progressbar li:first-child:after {
            /*connector not needed before the first step*/
            content: none;
        }

        /*marking active/completed steps green*/
        /*The number of the step and the connector before it = green*/
        #progressbar li.active:before, #progressbar li.active:after {
            background: #0f0f88;
            color: white;
        }

        #progressbar li.completed:before, #progressbar li.completed:after {
            background: #1D9FC1;
            color: white;
        }

        /* Not relevant to this form */
        .dme_link {
            margin-top: 30px;
            text-align: center;
        }

        .dme_link a {
            background: #000;
            font-weight: bold;
            color: #ee0979;
            border: 0 none;
            border-radius: 25px;
            cursor: pointer;
            padding: 5px 25px;
            font-size: 12px;
        }

        .dme_link a:hover, .dme_link a:focus {
            background: #C5C5F1;
            text-decoration: none;
        }

        .ocultar {
            display: none;
        }

        .caja {
            min-height: 560px;

        }

        form .row {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .x100{
            width:100%;
            background: #fff;
            background-color: #fff;
            max-height: 35px;

            padding: 0px;
            margin-bottom: 5px;
        }

    </style>
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

        <?php require_once('menu-superior.php'); ?>

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
                <li><a href="#">Ventas</a></li>
                <li class="active">Alta contrato</li>
            </ol>

        </header>
        <!-- /page title -->


        <div id="content" class="">
            <form id="msform" class="validate">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Cliente</li>
                    <li>Packs</li>
                    <li>Servicios</li>
                    <li>Campañas</li>
                    <li>Asignación</li>
                    <li>Activar</li>
                </ul>
<!--                <div class="spinner" id="animacion1">-->
<!--                    <div></div>-->
<!--                    <div></div>-->
<!--                    <div></div>-->
<!--                    <div></div>-->
<!--                </div>-->
                <!-- fieldsets -->
                <fieldset class="caja">

                    <div class="row">
                        <div class="col-md-8 col-sm-9">
                            <label>Seleccione un cliente o cree uno nuevo</label><br>
                            <select id="id" name="id" onchange="seleccionado(this.value)"
                                    class="form-control select2">
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-3 text-right">
                            <a href="/add-clie.php">
                                <span class="btn btn-primary" style="margin-top:25px;padding-top:9px;" >Crear uno nuevo</span>
                            </a>
                            <br><br>
                        </div>
                    </div>

                    <div class="row ocultar">
                        <div class="form-group">
                            <div class="col-md-2 col-sm-3">
                                <label>Nombre</label>
                                <input disabled type="text" name="nombre" value="" id="nombre"
                                       class="form-control required datoscli">
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label>Apellidos</label>
                                <input disabled type="text" name="apellidos" id="apellidos"
                                       class="form-control datoscli">
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <label>Tipo de Cliente</label>
                                <select disabled name="tipocli" id="tipocli"  class="form-control pointer">
                                    <option value="-1">--- Seleccionar uno ---</option>
                                    <?php $util->carga_select('clientes_tipos', 'ID', 'NOMBRE', 'ID'); ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <label>Tipo de Documento</label>
                                <select disabled name="tipodoc" id="tipodoc" onchange="cambia_tipo_cliente(this.value)" class="form-control pointer">
                                    <option value="-1">--- Seleccionar uno ---</option>
                                    <?php $util->carga_select('tipos_documentos', 'ID', 'NOMBRE', 'ID'); ?>
                                </select>
                            </div>


                            <div class="col-md-2 col-sm-3">
                                <label id="tipodocumento">Dni</label>
                                <input disabled type="text" name="dni" id="dni"
                                       class="form-control datoscli " placeholder="99999999A">
                            </div>

                        </div>
                    </div>

                    <div class="row ocultar">
                        <div class="form-group">
                            <div class="col-md-2 col-sm-3">
                                <label>Nacionalidad </label>
                                <select disabled name="nacion" id="nacion"
                                        class="form-control pointer"  onchange="carga_comunidades(this.value)">
                                    <option value="-1">--- Seleccionar una ---</option>
                                    <?php $util->carga_select('pais', 'id', 'paisnombre', 'paisnombre','','','',28); ?>

                                </select>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <label>Región</label>
                                <select disabled name="region" id="regiones"
                                        class="form-control pointer " onchange="carga_provincias(this.value)">
                                    <option value="-1">--- Seleccionar una ---</option>
                                    <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad'); ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <label>Provincia </label>
                                <select disabled name="provincia" id="provincias"
                                        class="form-control pointer " onchange="carga_poblaciones(this.value)">
                                    <option value="-1">--- Seleccionar una ---</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-4">
                                <label>Localidad </label>
                                <select disabled name="localidad" id="localidades"
                                        class="form-control pointer ">
                                    <option value="-1">--- Seleccionar una ---</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label>Dirección</label>
                                <input disabled type="text" name="direccion" id="direccion"
                                       class="form-control datoscli ">
                            </div>
                        </div>
                    </div>

                    <div class="row ocultar">
                        <div class="form-group">
                            <div class="col-md-2 col-sm-2">
                                <label>CP </label>
                                <input disabled type="number" min="0" max="99999" name="cp" id="cp"
                                       class="form-control datoscli required">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label>Email </label>
                                <input disabled type="email" name="email" id="email"
                                       class="form-control datoscli required">
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label>Tel Fijo</label>
                                <input disabled type="tel" name="tel1" id="tel1"
                                       class="form-control datoscli required">
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label>Tel Móvil</label>
                                <input disabled type="tel" name="tel2" id="tel2" class="form-control datoscli">
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label>Fecha Nacimiento</label>
                                <input disabled type="date" name="nacim" id="nacim" class="form-control datoscli">
                            </div>

                        </div>
                    </div>
                    <div class="row ocultar">
                        <div class="form-group">
                            <div class="col-md-3 col-sm-3">
                                <label>Entidad </label>
                                <input disabled type="text" name="banco" id="banco" class="form-control datoscli required" placeholder="Nombre del banco o caja">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label>IBAN</label>
                                <input disabled type="text" name="iban" id="iban" class="form-control datoscli required">
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label>Swift</label>
                                <input disabled type="text" name="swift" id="swift" class="form-control datoscli">
                            </div>

                            <div class="col-md-3">
                                <label>Consentimientos LOPD <a href="lopd.html" target="_blank"><i class="fa fa-question-circle"></i></a> </label>
                                <br>
                                <select disabled name="consentimiento" id="consentimiento"
                                        class="form-control pointer">
                                    <option value="-1">--- Seleccionar una ---</option>
                                    <?php $util->carga_select('clientes_consentimientos', 'ID', 'NOMBRE', 'NOMBRE','ID_EMPRESA = '.$_SESSION['REVENDEDOR']); ?>

                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="row ocultar">
                        <div class="form-group">

                            <div class="col-xs-12">
                                <label>Notas </label>
                                <input disabled type="text" name="notas" id="notas"
                                       class="form-control datoscli required">
                            </div>
                        </div>
                    </div>
                    <!-- Consentipiento Ley Lopd   -->
                    <?php
                    $util = new util();
                    $consentimientos = $util->selectWhere('clientes_consentimientos', array('ID', 'NOMBRE'), '', 'ID');

                    ?>

                    <div id="error"></div>
                    <input  type="button" name="" id="atras1" onclick="cancelar();"
                           class="ocultar action-button-previous"
                           value="Paso Anterior"/>
                    <input type="button" name="next" id="next1" class="next action-button" value="Continuar"/>

                </fieldset>

                <fieldset class="caja">
                    <div class="row">
                        <div class="col-lg-5 col-xs-12">
                            <label><b>Seleccione un pack de servicios o los servicios por separados en la siguiente pantalla</b></label><br>
                            <select  name="paquete" id="paquete"
                                    class="form-control pointer " onchange="paquete_seleccionado(this)">
                                <option value="">Sin paquete</option>
                                <?php $util->carga_select('paquetes', 'id', 'NOMBRE, PVP', 'NOMBRE', 'ID_EMPRESA = '.$_SESSION['REVENDEDOR'], 2, array('', ' €')); ?>
                            </select>
                        </div>


                        <div class="col-lg-1 col-xs-12">
<!--                            <label><b>Adicionales</b></label><br><br>-->
<!--                            <span id="pvp_extras"></span>-->
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label><b>PVP Pack</b></label><br><br>
                            <span id="pvp"></span>
                        </div>

                        <div class="col-lg-3 col-xs-12 text-right">
                            <label><b>PVP Total</b></label><br><br>
                            <span name="pvp_final" id="pvp_final">
                        </div>

                    </div>
                    <!-- aqui se cargarán los servicios que contiene el paquete seleccionado-->
                    <div id="aqui_los_servicios" style="height: 330px;overflow:scroll; overflow-x: hidden"></div>


                    <input  type="button" name="previous" class="previous action-button-previous"  value="Paso Anterior"/>
                    <input  type="button" name="next" id="next2" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja">
                    <div class="row">
                        <div class="col-lg-5 col-xs-12">
                            <label><b>Tanto si ha seleccionado un pack como no,<br>puede seleccionar servicios adicionales</b></label><br>

                        </div>
                        <div class="col-lg-1 col-xs-12">

                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label><b>PVP Pack</b></label><br><br>
                            <span id="pvp3"></span>
                        </div>

                        <div class="col-lg-2 col-xs-12">
                            <label><b>Pvp Extras</b></label><br><br>
                            <span id="pvp_extras"></span>
                        </div>


                        <div class="col-lg-2 col-xs-12 text-right">
                            <label><b>PVP Total</b></label><br><br>
                            <span name="pvp_final" id="pvp_final3">
                        </div>

                    </div>
                    <!-- aqui se cargarán los servicios que contiene el paquete seleccionado-->
                    <div class="table-responsive" style="height: 330px;overflow:scroll;overflow-x: hidden">
                        <table class="table table-condensed nomargin">
                            <thead class="text-center">
                            <tr>
                                <th>ID</td>
                                <th>Familia</th>
                                <th>Servicio</th>
                                <th  class="text-right">Coste <span class="fa fa-eye" style="font-size:1em; cursor: pointer;margin-left:.5em" onclick="ver_coste();"></span></th>
                                <th>IVA</th>
                                <th class="text-right">PVP</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                            </thead>
                            <tbody id="aqui_la_tabla2"></tbody>
                        </table>
                    </div>

                    <input  type="button" name="previous" class="previous action-button-previous"  value="Paso Anterior"/>
                    <input  type="button" name="next" id="next3" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja">
                    <div class="row" style="height:40px">
                        <div class="col-lg-4 col-xs-12">
                            <label><b>Seleccionar una campaña</b></label><br><br>
                            <select  name="campanas" id="campanas" class="form-control pointer " onchange="campana_seleccionada(this)">
                                <option value="-1" data-dto="0" data-dias="0">Sin campaña</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xs-12">
                            <label><b>PVP Pack</b></label><br><br>
                            <span id="pvp2"></span>
                        </div>

                        <div class="col-lg-2 col-xs-12">
                            <label><b>Adicionales</b></label><br><br>
                            <span id="pvp_extras2"></span>
                        </div>

                        <div class="col-lg-2 col-xs-12">
                            <label><b>Descuentos</b></label><br><br>
                            <span id="descuentos"></span>
                        </div>


                        <div class="col-lg-2 col-xs-12 text-right">
                            <label><b>PVP Final</b></label><br><br>
                            <span name="pvp_final2" id="pvp_final2">
                        </div>

                    </div>

<!--                    <div class="row">-->
<!--                        <div class="col-xs-12 ">-->
<!--                            <b>Puede modificar tanto el porcentaje como la duración de la promo, aplicable solo a este contrato</b><br>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-lg-4 col-xs-12 ">
                        <b>Puede seleccionar una promoción y/o modificar tanto el porcentaje de descuento como la duración en días o la fecha de finalización y/o permanencia</b>
                    </div>
                    <div class="col-lg-2 col-xs-12 ">
                        <label><b>Descuento %</b></label>
                        <select  name="dto" id="dto" class="form-control" style="width:80px" onchange="calcular_final(this.value)">
                            <?php
                                for($n=0;$n<=100;$n++){
                                    echo '<option value="'.$n.'">'.$n.'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-xs-12 ">
                        <label><b>Días Promoción</b></label>
                        <input  type="number" name="dto_meses" id="dto_meses" min="0" class="form-control" style="max-width: 80px" onblur="calcular_fin_promo(this.value)" ></input>
                    </div>

                    <div class="col-lg-2 col-xs-12 ">
                        <label><b>Descuento Hasta</b></label>
                        <input  type="date" min="" name="dto_hasta" id="dto_hasta" style="max-width: 200px"  class="form-control">
                    </div>

                    <div class="col-lg-2 col-xs-12 ">
                        <label><b>Permanencia Hasta</b></label>
                        <input  type="date" min="" name="permanencia" id="permanencia" style="max-width: 200px"  class="form-control">
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <br>
                            <b>Próximas facturas hasta fin de permanencia:</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" id="aqui_las_facturas" style="overflow-x: hidden; overflow-y: scroll;"></div>
                    </div>

                    <input  type="button" name="previous" class="previous action-button-previous"
                           value="Paso Anterior"/>
                    <input  type="button" name="next" id="next4" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja" id="ultima_pantalla">
                    <div id="aqui_los_servicios_y_productos" style="max-height:460px;overflow-x: hidden; overflow-y: scroll;margin-bottom:8px;"></div>

                    <input  type="button" name="previous" class="previous action-button-previous"
                           value="Paso Anterior"/>

                    <input  type="button" name="next" id="next5" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja">
                    <div class="row">
                        <div class="col-xs-12" id="aqui_el_resumen"></div>
                    </div>

                    <label><b>Por favor lea y acepte las condiciones</b></label><br><br>

                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                            $util = new util();
                            $texto = $util->selectWhere('textos_legales', array('ID', 'TEXTO','TEXTO_ACEPTACION'), ' UBICACION="porta1" ', 'ID');
                            $row = mysqli_fetch_array($texto);
                            echo $row[1];
                            echo "<br><br>";
                            $texto = $util->selectWhere('textos_legales', array('ID', 'TEXTO','TEXTO_ACEPTACION'), ' UBICACION="porta2" ', 'ID');
                            $row = mysqli_fetch_array($texto);
                            echo $row[1];

                            ?>

                            <label class="checkbox">
                                <input type="checkbox" value="1" onclick="goToAnchor('signature-pad')">
                                <i></i> Aceptar y firmar el contrato
                            </label>

                        </div>
                    </div>
                    <input  type="button" name="previous"  class="previous action-button-previous" value="Paso Anterior"/>
                    <div class="text-center">
                        <br><br><br>
                        <span id="avisofinreserva" style="font-size:1em; color: red; font-weight: 500"></span>

                    </div>
                </fieldset>

            </form>
        </div>


        <div id="signature-pad" class="signature-pad" style="position: relative; margin-top:900px; visibility: visible">
            <div class="signature-pad--body">
                <canvas></canvas>
            </div>
            <div class="signature-pad--footer">
                <div class="description">Firme y finalice</div>

                <div class="signature-pad--actions">
                    <div class="col-lg-3">
                        <br><br><br><br>
                        <button type="button" class="btn btn-default button clear" data-action="clear">Limpiar</button>
                        <br><br><br><br>
                    </div>
                    <div class="col-lg-3">

                    </div>
                    <div class="col-lg-3">

                    </div>
                    <div class="col-lg-3">
                        <br><br><br><br>
                        <button type="button" class="btn btn-success button save" id="btn-finalizar" data-action="save-svg">Finalizar</button>
                        <br><br><br><br>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>

</div>
<div class="modal fade" id="imprimir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Proceso Finalizado</h4>
            </div>
            <div class="modal-body">
                <p>Contrato guardado correctamente. Puede revisar el estado en la ficha del cliente</p>
                <div class="col-lg-3"></div>
                <div class="col-lg-3">
                    <br><br>
                    <a href="index.php"><img src="img/exit.png"></a>
                </div>
                <div class="col-lg-3">
                    <br><br>
                    <a href="" id="imprimir_img" target="_blank"><img src="img/printer.png"></a>
                </div>
                <div class="col-lg-3"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="content/firma/js/signature_pad.umd.js"></script>
<script src="content/firma/js/app.js"></script>

<script>
    var nuevo = 0;
    var id_cliente_seleccionado = 0;
    var id_paquete_seleccionado = 0;
    var precio = 0;
    var tot_extras = 0;
    var mostrandocoste=false;
    var servicios_contratados=[];
    var tipos_servicios=['Internet','Tel. Fija', 'Tel Móvil', 'Televisión'];
    var id_contrato_borrador=0;
    var diaspromo = 0;
    var totalMes=0;
    var codigo_reserva=0;
    var meses_permencia=0;
    var precioPack=0;
    var contenedordenumeros=0;
    var numerosNuevos=[];
    var totalDescuento=0;
    var avanzar = true;
    var sintodoslosproductos=false;


    function mostrarmodal(){
           $("#masservicios").modal();
    }

    $('#imprimir').on('hidden.bs.modal', function () {
        alerta('Atención','Para salir, utilice el boton correspondiente');
        clickEvent.preventDefault();
        clickEvent.stopPropagation();
        return false;
        location.href('index.php');
    });

    // carga las provincias en el combo correspondiente
    // se llama cada vez que selecciona una comunidad autonoma

    // cargo las regiones por Ajax, cada vez que se cambia el pais
    function carga_comunidades(id, sel = 0) {
        var select = $("#regiones");
        select.empty();
        select.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_regiones.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id)
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].region + '</option>');
                    else
                        select.append('<option value="' + data[i].id + '">' + data[i].region + '</option>');

                });
            }
        });
    }



    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_provincias(id, sel = 0) {
        var select = $("#provincias");
        select.empty();
        select.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_prov.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id)
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].provincia + '</option>');
                    else
                        select.append('<option value="' + data[i].id + '">' + data[i].provincia + '</option>');

                });
            }
        });
    }

    // cargo las localidades por Ajax cada vez que se cambia de provincia
    function carga_poblaciones(id, sel = 0) {
        var select = $("#localidades");
        select.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_pobla.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id)
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].municipio + '</option>');
                    else
                        select.append('<option value="' + data[i].id + '">' + data[i].municipio + '</option>');
                });
            }
        });
    }

    function cambia_tipo_cliente(valor) {
        if(valor==1)
            $("#tipodocumento").text('Dni');
        if(valor==2)
            $("#tipodocumento").text('Nie');
        if(valor==3)
            $("#tipodocumento").text('Cif');
        if(valor==4)
            $("#tipodocumento").text('Pasaporte');

    }
    function cambia_consentimiento(item) {
        if(item.id=="consentimiento['1']"){
            if(item.checked==true) {
                $(".consentimiento").prop('checked', false);
                $(item).prop('checked', true);
            }
        } else if(item.checked==true) {
            document.getElementById("consentimiento['1']").checked = false;
        }

    }
    // cargo los clientes para que pueda seleccionarse y editarlo
    function carga_clientes() {
        var select = $("#id");
        select.empty();
        select.append('<option value="-1">--- Seleccionar uno ---</option>');
        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            async: true,
            success: function (data) {
                $.each(data, function (i) {

                    select.append('<option value="' + data[i].id + '">' + data[i].apellidos + " " + data[i].nombre + '</option>');
                });
            }
        });
    }

    // cuando se pulsa el boton cancelar despues de haber pulsado el de nuevo, oculto los campos y pongo nuevo a 0
    function cancelar() {
        nuevo = 0;
        $(".ocultar").css('display', 'none');
        $("#atras1").css('display', 'none');
        $("#id").css('display', 'block');
        $("#atras1").css('position', 'absolute');
        $("#atras1").val('Cancelar');
        $("#next1").val('Continuar');
    }


    $(document).ready(function () {
        carga_clientes();
        $("#atras1").css('display', 'none');
        $("#next1").css('display', 'none');
        $("#dto_hasta").val(hoy());
        cargar_servicios();
        cargar_campanas();
    });

    function calcular_final(dtos) {
        // $("#pvp_final").val(round(parseFloat(precio+tot_extras) - ((dto / 100) * parseFloat(precio+tot_extras)), 2));
        $("#descuentos").html('<p style="font-size:2.5em; font-weight:600; color: #10c139; margin-top:-1px">' +  round(parseFloat((dtos / 100) * (precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final2").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final3").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        totalMes = round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2);
        totalDescuento=(dtos / 100) * (precio + tot_extras);
        calcular_facturas();
    }

    function calcular_fin_promo(dias) {

        diaspromo=dias;

        $( "#dto_hasta" ).val(sumarDias(dias));
        calcular_facturas();
    }


    function calcular_facturas() {

        $("#aqui_las_facturas").empty();
        $("#aqui_las_facturas").append('<div class="row" style="overflow:auto;overflow-x: hidden">');
        $("#aqui_las_facturas").append('<div class="table-responsive" style="max-height: 220px;">' +
            '<table class="table table-condensed nomargin">' +
            '<thead class=""><tr><th>Nº</th><th>Fecha</th><th>Importe</th></thead><tbody id="aqui_las_lineas"></tbody></table>');

        var diaFacturacion = <?php echo intval( $_SESSION['dia_facturacion']) ?>;
        var meses = diaspromo / 30;

        var f = new Date();
         // + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

        var hoy = f.getDate();

        for(nm=1; nm<=parseInt(meses_permencia+1); nm++){
            if(nm==1){
                var totalPrimerMes= totalMes - ((totalMes/30)* (hoy-diaFacturacion));
            }

            f.setSeconds(31*86400);
            var options = {year: "numeric", month: "long", day: "numeric"};
            f.setDate(diaFacturacion);

            var fecha_mes = f.toLocaleString("es-ES", options);

            if(nm==1)
                $("#aqui_las_lineas").append('<tr style="color:#1D9FC1; font-weight:600"><td >'+nm+'</td><td>'+fecha_mes+'</td><td>'+parseFloat(totalPrimerMes).toPrecision(4)+'</td></tr>');
            else if(nm>meses)
                $("#aqui_las_lineas").append('<tr><td>'+nm+'</td><td>'+fecha_mes+'</td><td>'+parseFloat(totalMes+totalDescuento).toPrecision(4)+'</td></tr>');
            else
                $("#aqui_las_lineas").append('<tr><td>'+nm+'</td><td>'+fecha_mes+'</td><td>'+parseFloat(totalMes).toPrecision(4)+'</td></tr>');
        }

    }



    // cuando se selecciona un cliente, recibo el id y lo cargo por ajax desde carga_cli que al pasarle una id
    // solo devuelve ese registro

    function seleccionado(id) {

        nuevo = 0;

        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            cache: false,
            async: true,
            data: {
                idcliente: id
            },
            success: function (data) {
                $("#regiones").val(parseInt(data[0].region)).change();
                $("#provincias").empty();

                carga_provincias(parseInt(data[0].region), parseInt(data[0].provincia));
                $("#dni").val(data[0].dni);
                $("#nombre").val(data[0].nombre);
                $("#apellidos").val(data[0].apellidos);
                $("#direccion").val(data[0].direccion);
                $("#cp").val(data[0].cp);

                carga_poblaciones(parseInt(data[0].provincia), parseInt(data[0].municipio));


                $("#tel1").val(data[0].tel1);
                $("#tel2").val(data[0].tel2);
                $("#email").val(data[0].email);
                $("#notas").val(data[0].notas);
                $("#nacim").val(data[0].fnacimiento);
                $("#banco").val(data[0].banco);
                $("#iban").val(data[0].iban);
                $("#swift").val(data[0].swift);

                $("#tipocli").val(data[0].tipocli).change();
                $("#tipodoc").val(data[0].tipodoc).change();
                $("#consentimiento").val(data[0].idconsentimiento).change();

                $("#hash").val(md5(id));

                $(".ocultar").css('display', 'block');
            }
        });

        id_cliente_seleccionado = id;
        $("#next1").css('display', 'block');

    }

    function paquete_seleccionado(item) {

        $("#aqui_los_servicios").empty();
        $("#aqui_los_servicios").append('<div class="row">');
        $("#aqui_los_servicios").append('<div class="table-responsive" >' +
            '<table class="table table-condensed nomargin">' +
            '<thead class="text-center"><tr><th>ID</td><th>Familia</th><th>Nombre Paquete</th><th class="text-right">Coste <span class="fa fa-eye" style="font-size:1em; cursor: pointer;margin-left:.5em" onclick="ver_coste();"></span>' +
            '</th><th>IVA</th><th class="text-right">PVP</th><th class="text-right">Permanencia</th></thead><tbody id="aqui_la_tabla"></tbody>');
        precio = 0;
        tot_extras = 0;

        $("#pvp_extras").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + round(tot_extras, 2) + ' &euro;</p>');
        var dtos = parseFloat($("#dto").val()) || 0;

        $("#pvp_final").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

        $("#pvp_extras2").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + round(tot_extras, 2) + ' &euro;</p>');
        var dtos = parseFloat($("#dto").val()) || 0;

        $("#pvp_final2").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final3").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

        servicios_contratados=[];

        precio = parseFloat(jQuery(item).find(':selected').data("extra")) || 0;
        var dtos = parseFloat($("#dto").val()) || 0;


        $("#pvp").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + precio + ' &euro;</p>');
        $("#pvp_final").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

        $("#pvp2").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + precio + ' &euro;</p>');
        $("#pvp3").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + precio + ' &euro;</p>');
        $("#pvp_final2").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final3").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

        id_paquete_seleccionado=item.value;
        precioPack=precio;
        // console.log(precioPack);

        $.ajax({
            url: 'content/servicios/carga_paquetes.php',
            type: 'POST',
            cache: false,
            async: false,
            data: {
                idpaquete: item.value
            },
            success: function (datos) {
                servicios_contratados=[];
                $.each(datos, function (i) {
                    $("#aqui_la_tabla").append('<tr><td>' + datos[i].idservicio + '</td><td>' + datos[i].tipo + '</td><td><i class="fa fa-question-circle"></i> ' +
                        datos[i].comercial + '</td><td class="text-right"><span class="oculta_coste" style="display:none">' + datos[i].coste + ' &euro;</td><td>' + datos[i].impuesto + '%</td><td class="text-right">' +
                        datos[i].pvp + ' &euro;</td>' +
                        '<td class="text-right"><input type="number" min="0" value="' + datos[i].permanencia + '" style="height:25px; font-size:1em;width:40px; padding:1px;"> Meses</td></tr>');

                    /** con la p controlo que es del paquete
                        el siguiente elemento es 0 para portas 1 para numeros nuevos,
                        se actualiza al cambiar el valor del select */
                    //              0                   1                   2           3   4                  5    6   7  8    9
                    var servicio=[datos[i].idservicio, datos[i].id_tipo, datos[i].pvp, 1, datos[i].comercial, 'p', '0','','', datos[i].permanencia ];
                    servicios_contratados.push(servicio);

                    if(parseInt(datos[i].permanencia)>=parseInt(meses_permencia))
                        meses_permencia=parseInt(datos[i].permanencia);
                });
                // console.log(servicios_contratados);
                $(".ocultar").css('display', 'block');

            }
        });

        cargar_servicios();
        $("#next2").css('display', 'block');
        calcular_facturas();
    }

    function campana_seleccionada(item) {
        var dtos = parseFloat(jQuery(item).find(':selected').data("dto")) || 0;
        var dias = parseFloat(jQuery(item).find(':selected').data("dias")) || 0;
        $("#dto_meses").val(dias);
        $("#dto").val(parseInt(dtos)).change();
        // var total2 = total * (dto/100);
        $("#pvp_final2").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final3").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#descuentos").html('<p style="font-size:2.5em; font-weight:600; color: #10c139; margin-top:-1px">' +  round(parseFloat((dtos / 100) * (precio + tot_extras)), 2) + ' &euro;</p>');

        // var date2 = $('#dto_hasta').datepicker('getDate');

        // var date2 = hoy(dias);
        $( "#dto_hasta" ).val(sumarDias(dias));

        diaspromo=dias;
        totalMes = round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2);
        calcular_facturas();
        $("#next3").css('display', 'block');

    }

    function cargar_servicios(){
        // $("#agregar_servicios").empty();
        // $("#agregar_servicios").append('<div class="row">');
        // $("#agregar_servicios").append('<div class="table-responsive" style="height:160px; overflow-x: scroll; ">' +
        //     '<table class="table table-condensed nomargin"><tbody id="aqui_la_tabla2"></tbody>');


        $.ajax({
            url: 'content/servicios/carga_servicios.php',
            type: 'POST',
            cache: false,
            async: false,
            success: function (datos) {

                $.each(datos, function (i) {
                    $("#aqui_la_tabla2").append('<tr><td>' + datos[i].idservicio + '</td><td>' + datos[i].tipo + '</td><td><i class="fa fa-question-circle"> </i> ' +
                        datos[i].comercial + '</td><td class="text-right"><span class="oculta_coste" style="display:none">' + datos[i].coste + ' &euro;</span></td><td>' + datos[i].impuesto + '%</td><td class="text-right">' +
                        datos[i].pvp + ' &euro;</td>' +
                        '<td class="text-right">' +
                        '<select style="width:40px; height:28px;font-size:1em; padding:0px" data-pvp="' + datos[i].pvp + '" id="' + datos[i].idservicio +'" ' +
                        'data-permanencia="' + datos[i].permanencia + '" '+
                        'data-id_servicio="' + datos[i].idservicio.replace(/\s/g, "")  + '" data-id_familia="' + datos[i].id_tipo +  '" data-servicio="' + datos[i].comercial + '" onchange="add_service(this);">'+
                        '<option value=0 selected>0</option>'+
                        '<option value=1>1</option>'+
                        '<option value=2>2</option>'+
                        '<option value=3>3</option>'+
                        '<option value=4>4</option>'+
                        '<option value=5>5</option>'+
                        '<option value=6>6</option>'+
                        '<option value=7>7</option>'+
                        '<option value=8>8</option>'+
                        '<option value=9>9</option>'+
                        '</select></td></tr>');

                });

                $("#agregar_servicios").css('display', 'block');
            }
        });
    }

    function cargar_campanas(){

        $.ajax({
            url: 'content/servicios/carga_campanas.php',
            type: 'POST',
            cache: false,
            async: false,
            success: function (datos) {

                $.each(datos, function (i) {
                    $("#campanas").append(
                        '<option value="'+datos[i].idcampana+'" data-dto="'+datos[i].descuento+'" data-dias="'+datos[i].duracion+'">'+datos[i].nombre+'</option>');
                });

            }
        });
    }

    /*
        -------------------------------------------------
        Efectos y Control del formulario step by step
        -------------------------------------------------
     */
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function () {

        if (animating) return false;


        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        if (this.id == 'next1') {

            if (parseInt($("#id")[0].selectedIndex) == -1) {
                alert("Debe seleccionar un cliente o crear uno nuevo");
                avanzar = false;
                return;
            } else {


                $("#next1").css('display', 'none');
                animating = true;


                $("#next1").css('display', 'block');
            }

            guardar_borrador(1);
        }


        if (this.id == 'next3'){
            if(id_paquete_seleccionado==0 && servicios_contratados.length==0){
                alert("Debe seleccionar un paquete o servicio");
                avanzar = false;
                return;
            }
            $("#campanas").val(0).change();
            guardar_borrador(2);

        }



        if (this.id == 'next4'){
            if($("#campanas").val()==-1){
                if(!confirm("No ha seleccionado ninguna campaña, ¿estás seguro/a?"))
                    return;
                else {
                    guardar_borrador(3);
                    asignar_productos();
                }
            } else{
                guardar_borrador(3);
                asignar_productos();
            }
        }

        if (this.id == 'next5'){

            // if($("#campanas").val()==-1){
            //     if(!confirm("No ha seleccionado ninguna campaña, ¿estás seguro/a?"))
            //         return;
            // } else{
            //     if(!guardar_borrador(4))
            //         return;
            // }
            avanzar = true;
            $('.selnuevo').each(function (){
                var elemento = this.id;

                // $("#"+elemento).css('border','solid 1px #c9c9c9');
                if(this.value==-1){
                    alertaOk('ATENCION','No puede dar un alta de telefonía fija, sin seleccionar un número de teléfono','warning','Entendido');
                    $("#"+elemento).css('border','solid 2px red');
                    avanzar = false;
                    return;
                }
            });

            if(!guardar_borrador(4) && avanzar==true)
                return;
        }



        function asignar_productos(){

            $.ajax({
                url: 'content/servicios/lista_servicios_productos.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    servicios: servicios_contratados
                },
                success: function (data) {

                    // for (var x = 0; x < data.length; x++) {
                    //     $('#velocidades')
                    //         .append($("<option></option>")
                    //             .attr("value",data[x].perfil_olt)
                    //             .text('OLT: ' + data[x].perfil_olt + ' / Descripción: ' +data[x].nombre_perfil));
                    //
                    // }
                }
            });

        }

        // pedir:
        /*
        Movil : (porta / nuevo), Porta proceso aparte
            nuevo: listado de numeros libres: airenetwork: pool y numero de reserva para 10 minutos
            icc del almacen
            digito control esta impreso en la tarjeta y la api
            producto asociado

            Fibra:  Pon
         */

        if (avanzar) {
            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            $("#progressbar li").eq($("fieldset").index(next_fs) - 1).addClass("completed");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({'left': left, 'opacity': opacity});
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
            animating = false;
        }
    });

    function guardar_borrador(paso) {

        // paso 1 quiere decir que se ha seleccionado o creado el cliente,
        // entonces creamos el contrato en borrador y guardamos el id del borrador

        if(paso==1) {

            $.ajax({
                url: 'content/servicios/guardar-borrador.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    action: 'borrador',
                    id_cliente: id_cliente_seleccionado
                },
                success: function (data) {

                    if(parseInt(data)>0)
                        id_contrato_borrador = data;
                }
            });
        }

        // paso dos es cuando se ha seleccionado el paquete y los servicios
        if(paso==2) {

            $.ajax({
                url: 'content/servicios/guardar-borrador-lineas.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    action: 'borrador',
                    id_paquete: id_paquete_seleccionado,
                    id_borrador: id_contrato_borrador,
                    lineas: servicios_contratados
                }
            });
            $( "#permanencia" ).val(sumarDias(meses_permencia*31));
        }

        //paso tres cuando se seleccionan los servicios adicionales al paquete
        if(paso==3) {
            // guardo la campaña del contrato en borrador
            $.ajax({
                url: 'content/servicios/guardar-borrador-campana.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    action: 'borrador',
                    id_borrador: id_contrato_borrador,
                    id_campana: $("#campanas").val(),
                    dto:$("#dto").val(),
                    dias:$("#dto_meses").val(),
                    hasta:$("#dto_hasta").val()
                }
            });

            // cargo los numeros de telefonos moviles y fijos que hay en la tabla portabilidades
            // cuyo estado este pendiente y los guardo en array para poder llenar los selects

            var aMoviles=[];
            var aFijos=[];
            var aNuevos=[];
            var aProductos=[];

            $.ajax({
                url: 'content/ventas/cargar-portas.php',
                type: 'POST',
                cache: false,
                async: false,
                data: {
                    id: id_cliente_seleccionado

                },
                success: function (data) {
                    $.each(data, function (i) {
                        if(data[i].tipo==2){
                            aFijos.push(data[i]);
                        } else {
                            aMoviles.push(data[i]);
                        }
                    });

                }
            });

            // cargo los productos en stock del revendodor

            $.ajax({
                url: 'content/ventas/cargar-productos.php',
                type: 'POST',
                cache: false,
                async: false,
                success: function (data) {
                    $.each(data, function (i) {
                        aProductos.push(data[i]);
                    });
                }
            });
            // console.log(aFijos);
            // console.log(aMoviles);
            // console.log(aProductos);

            // listo los servicios y productos para ir asociando datos a cada linea del contrato
            // como los numeros de tarjetas, numeros de telefonos, imeis, pons, etc.

            $("#aqui_los_servicios_y_productos").empty();

            var lineas_detalles_contrato =
                "<div class='row'>"+
                "<div class='col-lg-1 col-xs-2'><br><b>Info</b><br><br></div>" +
                "<div class='col-lg-4 col-xs-10'><br><b>Servicio</b><br><br></div>" +
                "<div class='col-lg-2 col-xs-10'><br><b>Alta / Porta</b><br><br></div>" +
                "<div class='col-lg-2 col-xs-10'><br><b>Numeraciones</b><br><br></div>" +
                "<div class='col-lg-3 col-xs-12'><br><b>Producto Asociado</b><br><br></div>"+
                "</div>";

            for (c=0; c<servicios_contratados.length; c++){
                var enpackono='outpack.png';

                if(servicios_contratados[c][5]=='p') enpackono = 'inpack.png';

                lineas_detalles_contrato = lineas_detalles_contrato +
                    "<div class='row' style='border-bottom: 1px solid #c9c9c9'>" +
                    "<div class='col-xs-2 col-lg-1'><img style='max-width:29px' src='img/"+ enpackono + "'><img  style='max-width:29px' src='img/serv"+ servicios_contratados[c][1] + ".png'></div>" +
                    "<div class='col-xs-10 col-lg-4'><i class='fa fa-question-circle'> </i> "+servicios_contratados[c][4] + "</div>";

                if(servicios_contratados[c][1]==2) {
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<div class='col-xs-10 col-lg-2'>" +
                        "<select class='x100 portaonuevo' id='portaonuevo-"+c+"' onchange='muestra_oculta_porta(this.value,"+c+",2)'>" +
                        "<option value=0>Portabilidad</option>" +
                        "<option value=1>Alta Nueva</option>" +
                        "</select>" +
                        "</div>" +
                        "<div class='col-xs-10 col-lg-2'>" +
                        "<select class='x100 selporta' id='porta-"+c+"' onchange='verifPorta(this,"+c+")'>";
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<option value='0'></option>";
                    $.each(aFijos, function (i) {
                        lineas_detalles_contrato = lineas_detalles_contrato +
                            "<option value='" + aFijos[i].num + "'>" + aFijos[i].num + "</option>";
                    });
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "</select>" +
                        "<select class='x100 selnuevo' style='display:none;width:100%' onchange='verifNuevo(this,"+c+")' id='nuevo-"+c+"'>";
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<option value='0'></option>";
                    $.each(aNuevos, function (i) {
                        lineas_detalles_contrato = lineas_detalles_contrato +
                            "<option value='" + aNuevos[i].num + "'>" + aNuevos[i].num + "</option>";
                    });
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "</select><span id='cuentaatras"+c+"'  style='display:inline'></span>" +
                        "<img src='img/refresh.png'  id='refresh"+c+"' style='height:25px; display: none'>" +
                        "</div>";

                } else if(servicios_contratados[c][1]==3) {
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<div class='col-xs-10 col-lg-2'>" +
                        "<select class='x100 portaonuevo' id='portaonuevo-"+c+"' onchange='muestra_oculta_porta(this.value,"+c+",3)'>" +
                        "<option value=0>Portabilidad</option>" +
                        "<option value=1>Alta Nueva</option>" +
                        "</select>" +
                        "</div>" +
                        "<div class='col-xs-10 col-lg-2'>" +
                        "<select class='x100 selporta' id='porta-"+c+"' onchange='verifPorta(this,"+c+")'>";
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<option value='0'></option>";
                    $.each(aMoviles, function (i) {
                        lineas_detalles_contrato = lineas_detalles_contrato +
                            "<option value='" + aMoviles[i].num + "'>" + aMoviles[i].num + "</option>";
                    });
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "</select>" +
                        "<select class='x100 selnuevo' style='display: none; width:100%' id='nuevo-"+c+"' onchange='verifNuevo(this,"+c+")'>";
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<option value='0'></option>";
                    $.each(aNuevos, function (i) {
                        lineas_detalles_contrato = lineas_detalles_contrato +
                            "<option value='" + aNuevos[i].num + "'>" + aNuevos[i].num + "</option>";
                    });
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "</select><span id='cuentaatras"+c+"' style='display:inline'></span>" +
                        "<img src='img/refresh.png'  id='refresh"+c+"' style='height:25px; display: none'>" +
                        "</div>";
                } else
                    lineas_detalles_contrato = lineas_detalles_contrato +
                        "<div class='col-xs-10 col-lg-2'></div><div class='col-xs-10 col-lg-2'></div>";

                lineas_detalles_contrato = lineas_detalles_contrato +
                    "<div class='col-xs-12 col-lg-3'>";

                lineas_detalles_contrato = lineas_detalles_contrato +
                    "<select class='x100 producto' id='prod-"+c+"' onchange='verifProds(this,"+c+")'>";
                lineas_detalles_contrato = lineas_detalles_contrato +
                    "<option data-id_servicio='"+c+"' value='0'></option>";
                $.each(aProductos, function (i) {
                    if (aProductos[i].tipo == servicios_contratados[c][1])
                        lineas_detalles_contrato = lineas_detalles_contrato +
                            "<option value='" + aProductos[i].id + "'>" + aProductos[i].serial + "</option>";
                });

                lineas_detalles_contrato = lineas_detalles_contrato + "</select></div></div>" ;
            }
            $("#aqui_los_servicios_y_productos").append(lineas_detalles_contrato);
        }


        if(paso==4) {

            var todos_ok=0;
            var portaonuevo=[];
            var portas=[];
            var nuevos=[];

            $('.producto').each(function (){

                if(this.value == null || this.value == '' || this.value == 0) {
                    $(this).css('border', 'solid 2px orange');
                    todos_ok++;
                } else {
                    $(this).css('border', 'solid 2px #1D9FC1');
                }
            });

            $('.portaonuevo').each(function (){
                var port = [this.value, this.id];
                portaonuevo.push(port);
            });

            $('.selporta').each(function (){
                var port = [this.value, this.id];
                portas.push(port);
            });

            $('.selnuevo').each(function (){
                var port = [this.value, this.id];
                nuevos.push(port);
            });

            for (n=0; n<portaonuevo.length; n++){
                if(portaonuevo[n][0]==0 && portas[n][0]==0){
                    $('#'+portas[n][1]).css('border', 'solid 2px orange');
                } else {
                    $('#'+portas[n][1]).css('border', 'solid 2px #1D9FC1');
                }

                if(portaonuevo[n][0]==1 && nuevos[n][0]==0){
                    $('#'+nuevos[n][1]).css('border', 'solid 2px orange');
                } else {
                    $('#'+nuevos[n][1]).css('border', 'solid 2px #1D9FC1');
                }


            }

            if (todos_ok>0 && sintodoslosproductos==false) {
                alerta("ATENCION","No ha asignado productos a todos los servicios, es importante hacer esto. ¿Desea continuar sin asignar los productos?",
                                    "warning","Regresar","Avanzar sin asignar","returnfalse","returntrue");
            } else
                return true;

        }

    }


    function add_service(objeto) {
        tot_extras=0;
        var id_servicio = jQuery(objeto).data("id_servicio");
        var nom_servicio = jQuery(objeto).data("servicio");
        var id_familia = jQuery(objeto).data("id_familia");
        var pvp_extra = parseFloat(jQuery(objeto).data("pvp"));
        var permanencias = parseFloat(jQuery(objeto).data("permanencia"));
        var cantidad = parseFloat(objeto.value) || 0;

        for (c=0; c<servicios_contratados.length; c++){
            if(servicios_contratados[c][0]==id_servicio && servicios_contratados[c][5]=='e') {
                servicios_contratados.splice(c, 1);
                c--;
            }

        }

        // con la e controlo que es un extra
        for (d=1; d<=cantidad; d++){
            var servicio = [id_servicio, id_familia, pvp_extra, 1, nom_servicio, 'e','0','','', permanencias];

            servicios_contratados.push(servicio);
        }
        console.log(servicios_contratados);

        for (c=0; c<servicios_contratados.length; c++){
            if(servicios_contratados[c][5]=='e')
                tot_extras=tot_extras+(parseFloat(servicios_contratados[c][2])*parseFloat(servicios_contratados[c][3]));
        }

        $("#pvp_extras").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + round(tot_extras, 2) + ' &euro;</p>');
        var dtos = parseFloat($("#dto").val()) || 0;

        $("#pvp_final").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

        $("#pvp_extras2").html('<p style="font-size:2.5em; font-weight:600; margin-top:-1px">' + round(tot_extras, 2) + ' &euro;</p>');
        var dtos = parseFloat($("#dto").val()) || 0;

        $("#pvp_final2").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');
        $("#pvp_final3").html('<p style="font-size:2.5em; font-weight:600; color: #1D9FC1; margin-top:-1px">' + round(parseFloat(precio + tot_extras) - ((dtos / 100) * parseFloat(precio + tot_extras)), 2) + ' &euro;</p>');

    }

    function guardar_cliente() {

        var nom = $("#nombre").val();
        var ape = $("#apellidos").val();
        var dni = $("#dni").val();
        var dir = $("#direccion").val();
        var reg = $("#regiones").val();
        var pro = $("#provincias").val();
        var loc = $("#localidades").val();
        var cp = $("#cp").val();
        var mail = $("#email").val();
        var tl1 = $("#tel1").val();
        var tl2 = $("#tel2").val();
        var notas = $("#notas").val();
        var tdoc = $("#tipodoc").val();
        var tcli = $("#tipocli").val();
        var fnac = $("#nacim").val();
        var lopd = $("#consentimiento").val();
        var banco = $("#banco").val();
        var iban = $("#iban").val();
        var swift = $("#swift").val();


        var clientes = {
            nombre: nom, apellidos: ape, dni: dni, dir: dir, cp: cp, region: reg, provincia: pro,
            localidad: loc, email: mail, tel1: tl1, tel2: tl2, notas: notas, alta: hoy(), tipodoc:tdoc,
            tipocli:tcli, nacimiento: fnac, lopd:lopd, banco:banco, iban:iban, swift:swift
        };

        var ok = false;
        $.ajax({
            url: 'php/guardar-cli.php',
            type: 'POST',
            cache: false,
            async: false,
            data: {
                action: 'clientes',
                clientes: clientes,
                is_ajax: true
            },
            success: function (data) {
                if (parseInt(data) > 0) {
                    ok = true;
                    id_cliente_seleccionado = data;
                }
                else {
                    alert("ERROR: Posible datos duplicados, revise los clientes actuales.");
                    $("#next1").css('display', 'block');
                    animating = false;
                }
            }
        });
        return ok;
    }

    $(".previous").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    // $(".submit").click(function () {
    //     return false;
    // });

    function validateEmail(email) {
        var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
        return re.test(email);
    }

    function round(value, decimals) {
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    }


    // Calcular si el cliente es mayo de edad

    var _MS_PER_DAY = 1000 * 60 * 60 * 24;


    function dateDiffInDays(a, b) {

        var utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
        var utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());

        return Math.floor((utc2 - utc1) / _MS_PER_DAY);
    }


    function hoy(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd = '0'+dd
        }

        if(mm<10) {
            mm = '0'+mm
        }

        today = new Date(yyyy + '-' + mm + '-' + dd);
        return today;
    }


    function sumarDias(dias){

            var date = new Date();
            var newdate = new Date(date);

            newdate.setDate(newdate.getDate() + parseInt(dias));

            var dd = newdate.getDate();
            var mm = newdate.getMonth() + 1;
            var y = newdate.getFullYear();
            if(dd<10) {
                dd = '0'+dd
            }

            if(mm<10) {
                mm = '0'+mm
            }

            var someFormattedDate = y + '-' + mm + '-' + dd;
            return someFormattedDate;

    }


    function ver_coste(){
        if(mostrandocoste){
            $(".oculta_coste").css("display","none");
            mostrandocoste=!mostrandocoste;
        } else {
            $(".oculta_coste").css("display","block");
            mostrandocoste=!mostrandocoste;
        }
    }


    function goToAnchor(anchor) {
        $("#signature-pad").css("visibility","visible");
        var loc = document.location.toString().split('#')[0];
        document.location = loc + '#' + anchor;
        return false;
    }

    function muestra_oculta_porta(a,b, c) {

        if(a==0) {
            // $("#porta-" + b).css('display', 'block');
            $("#nuevo-" + b).css('display', 'none');
        } else {
            $("#porta-" + b).css('display', 'none');
            if(c!=3)
                $("#nuevo-" + b).css('display', 'block');
        }
        servicios_contratados[b][6]=a;

        if(c==3)
            return;



        $("#nuevo-"+b).empty();

        var url='content/ventas/cargar-fijos-libres.php';

        if(c==3)
            url='content/ventas/cargar-moviles-libres.php';


        if(codigo_reserva==0) {

            $("#nuevo-" + b).append("<option value='-1' selected>Seleccione</option>");

            contenedordenumeros=b;
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                async: false,
                data: {
                    proveedor: id_cliente_seleccionado
                },
                success: function (data) {
                    $.each(data, function (i) {
                        if (i != 'codigo_reserva') {

                            if (c == 3) {
                                $($("#nuevo-" + b)).append("<option value='" + data[i] + "'>" + data[i] + "</option>");
                                numerosNuevos.push(data[i]);
                            } else if (data[i].selectable == true) {
                                $($("#nuevo-" + b)).append("<option value='" + data[i].num + "'>" + data[i].num + "</option>");
                                numerosNuevos.push(data[i].num);
                            } else
                                $($("#nuevo-" + b)).append("<option disabled value='" + data[i].num + "'>---- " + data[i].num + " ----</option>");
                        } else
                            codigo_reserva = data[i];

                    });

                }
            });

            if(c==3) {
                var seconds = 550;

                // Calcula la fecha de finalización del contador sumando
                // el número de segundos a la fecha actual
                var end = (new Date()).getTime() + seconds * 1000;
                displayCounter();
                var timeout = setInterval(displayCounter, 300);

                function displayCounter() {
                    // Calcula el número de segundos que faltan para llegar a la fecha de finalización
                    var counter = Math.floor((end - (new Date()).getTime()) / 1000);
                    if (counter < 0) counter = 0;

                    document.getElementById('cuentaatras' + b).innerHTML = 'Reserva Expira: ' +
                        Math.floor(counter / 60) + ':' +
                        ('00' + Math.floor(counter % 60)).slice(-2);
                    if (counter === 0) {
                        clearTimeout(timeout);
                        $(".next").attr('disabled',true);
                        $("#btn-finalizar").attr('disabled',true);
                        $("#avisofinreserva").text("Tiempo de reserva para numeros moviles agotado, regrese y recargue");

                        var loc = document.location.toString().split('#')[0];
                        document.location = loc + '#ultima_pantalla';

                        document.getElementById('cuentaatras' + b).innerHTML = "<span onclick='refrescar(" + b + ")' style='cursor:pointer; color:red; font-weight: 600'>Haga clic para recargar numeros</span>";
                        $("#next5").val('No puede Continuar');

                    }
                }
            }

        } else {
            $($("#nuevo-" + b)).append("<option value='-1'>Seleccione</option>");
            $($("#nuevo-" + b)).empty();

            $.each(numerosNuevos, function (i) {
                $($("#nuevo-" + b)).append("<option value='" + numerosNuevos[i] + "'>" + numerosNuevos[i] + "</option>");
            });

        }


    }

    function refrescar(id){
        codigo_reserva=0;
        $(".selnuevo option").remove();
        numerosNuevos=[];
        $(".next").removeAttr('disabled');
        $("#next5").val('Continuar');
        $("#btn-finalizar").removeAttr('disabled');
        $("#avisofinreserva").text("");
        muestra_oculta_porta(1,id, 3);
    }


    function verifNuevo(s,b){
        var texto = $(s).find('option:selected').text();

        $('.selnuevo').each(function (){
            $(this).css('border', 'solid 2px #E5E7E9');
            if(this.value == s.value && this.id!=s.id && s.value!=0) {
                alert("El valor " + texto + " ya ha sido seleccionado.");
                $(this).css('border', 'solid 2px #1D9FC1');
                $(s).css('border', 'solid 2px orange');
                s.selectedIndex = 0;
                s.focus();
                return false;
            }
        });

        servicios_contratados[b][7]=s.value;
        // console.log(servicios_contratados);

    }

    function verifPorta(s,b){
        var texto = $(s).find('option:selected').text();

        $('.selporta').each(function (){
            $(this).css('border', 'solid 2px #E5E7E9');
            if(this.value == s.value && this.id!=s.id && s.value!=0) {
                alert("El valor " + texto + " ya ha sido seleccionado.");
                $(this).css('border', 'solid 2px #1D9FC1');
                $(s).css('border', 'solid 2px orange');
                s.selectedIndex = 0;
                s.focus();
                return false;
            }
        });

        servicios_contratados[b][7]=s.value;
        // console.log(servicios_contratados);

    }


    function verifProds(s,b){
        var texto = $(s).find('option:selected').text();

        $('.producto').each(function (){
            $(this).css('border', 'solid 2px #E5E7E9');
            if(this.value == s.value && this.id!=s.id && s.value!=0) {
                alert("El valor " + texto + " ya ha sido seleccionado.");
                $(this).css('border', 'solid 2px #1D9FC1');
                $(s).css('border', 'solid 2px orange');
                s.selectedIndex = 0;
                s.focus();
                return false;
            }
        });
        servicios_contratados[b][8]=s.value;
        servicios_contratados[b][10]=s.options[s.selectedIndex].text;

        // console.log(servicios_contratados);
    }

    function guardar_contrato(firma){
        var permanencia = $("#permanencia").val();
        trabajando();
        trabajando(1);
        $.ajax({
            url: 'content/servicios/guardar-contrato.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                action: 'contrato',
                id_borrador: id_contrato_borrador,
                id_campana: $("#campanas").val(),
                dto:$("#dto").val(),
                dias:$("#dto_meses").val(),
                hasta:$("#dto_hasta").val(),
                pack: precio,
                extras: tot_extras,
                firma: firma,
                lineas: servicios_contratados,
                cliente: id_cliente_seleccionado,
                permanencia: permanencia,
                id_paquete: id_paquete_seleccionado,
                preciopaquete: precioPack,
                cif: $("#dni").val(),
                nombreapellidos:$("#nombre").val()+" " +$("#apellidos").val(),
                direccion: $("#direccion").val() + ", " + $("#localidades").val()+ ", " + $("#provincias").val()+ ", " + $("#cp").val() + ", " + $("#regiones").val() + ", " + $("#nacion").val(),
                email: $("#email").val()
            },
            success: function (data) {
                if(parseInt(data)>0){
                    $("#imprimir").modal();

                    $("#imprimir_img").attr('href','content/ventas/imprimirContrato.php?idContrato='+data);
                }
            }
        });
    }


    function returntrue(){

        avanzar=true;
        sintodoslosproductos=true;
        mostrar_resumen();
        $("#next5").click();
    }


    function returnfalse(){
        avanzar=false;
        return false;
    }



    function mostrar_resumen() {

        $("#aqui_el_resumen").empty();
        $("#aqui_el_resumen").append('<div class="row" style="overflow:auto;overflow-x: hidden">');
        $("#aqui_el_resumen").append('<div class="table-responsive" style="max-height: 160px;">' +
            '<table class="table table-condensed nomargin">' +
            '<thead class=""><tr><th>Tipo</th>' +
                                '<th>Tarifa</th>' +
                                '<th>En Pack</th>' +
                                '<th class="text-right">Precio</th>' +
                                '<th class="text-right">Número</th>' +
                                '<th class="text-right">Permanencia</th>' +
            '</thead><tbody id="aqui_las_lineas_resumen"></tbody></table>');

        $.each(servicios_contratados, function (i) {
            var packsino='NO';
            if(servicios_contratados[i][5]=='p') packsino ='SI';

            $("#aqui_las_lineas_resumen").append('<tr>' +
                '<td >'+tipos_servicios[parseInt(servicios_contratados[i][1])-1]+'</td>' +
                '<td>'+servicios_contratados[i][4]+
                '</td><td>'+packsino+ '</td>'+
                '</td><td class="text-right">'+parseFloat(servicios_contratados[i][2]).toFixed(2)+' &euro;</td>'+
                '</td><td class="text-right">'+servicios_contratados[i][7]+'</td>'+
                '</td><td  class="text-right">'+servicios_contratados[i][9]+' meses</td></tr>'
            );
        });
    }

</script>


</body>
</html>