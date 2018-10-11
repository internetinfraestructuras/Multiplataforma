<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER ." / ". DEF_T_FIJO; ?> / Solicitud</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
<!--    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>-->

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>
    <link rel="stylesheet" href="../firma/css/signature-pad.css">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="../../assets/css/portabilidades.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css" />
</head>

<body>


<!-- WRAPPER -->
<div id="wrapper">

    <aside id="aside" style="position:fixed;left:0">

        <?php require_once('../../menu-izquierdo.php'); ?>

        <span id="asidebg"><!-- aside fixed background --></span>
    </aside>
    <!-- /ASIDE -->


    <!-- HEADER -->
    <header id="header">

        <?php require_once ('../../menu-superior.php'); ?>

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
                <li><a href="#"><?php echo DEF_T_MOVIL; ?></a></li>
                <li class="active">Solicitar Portabilidad</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="">
            <form id="msform" class="validate">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Cliente</li>
                    <li>Donante</li>
                    <li>Líneas</li>
                    <li>Documentos</li>
                    <li>Activar</li>
                </ul>
                <div class="spinner" id="animacion1">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>

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
                                <label id="">Dni</label>
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
                                    <?php $util->carga_select('clientes_consentimientos', 'ID', 'NOMBRE', 'NOMBRE'); ?>

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
                    <div class="row text-center">
                        <label><h3><b>Seleccione el operador donante</b></h3></label><br><br>
                    </div>
                    <div class="row">
                        <?php
                            $util = new util();
                            $donantes = $util->selectWhere('operadores_telefonia', array('ID', 'NOMBRE','LOGO'), ' TIPO=1 OR TIPO=3 ', 'ID');
                            while ($row = mysqli_fetch_array($donantes)) {
                                echo '
                                <div class="col-xs-3 col-sm-3 text-center">
                                    <label class="image-radio">
                                        <img class="img-responsive" src="../../'.$row[2].'" style="border-radius:5px; width:120px; height:120px"/>
                                        <input type="radio" id="donante" name="donante" value="'.$row[0].'" />
                                        <i class="glyphicon glyphicon-ok hidden"></i>
                                    </label>
                                    <br>
                                    '.$row[1].'
                                </div>
                                ';
                            }

                        ?>
                    </div>

                    <input  type="button" name="previous" class="previous action-button-previous"
                           value="Paso Anterior"/>
                    <input  type="button" name="next" id="next3" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja">
                    <div class="row text-center">
                        <label><h3><b>Datos de las linea a portar</b></h3></label><br><br><br>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label>Tarifa que desea contratar</label>
                            <select name="tarifa" id="tarifa"  class="form-control pointer">
                                <option value="-1">--- Seleccionar uno ---</option>
                                <?php $util->carga_select('servicios', 'ID', 'NOMBRE,PVP', 'ID', 'ID_SERVICIO_TIPO=3',2); ?>
                            </select>
                            <br><br><br><br>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <br>
                            <button type="button" class="btn btn-default">Ver tarifas</button>
                            <br><br><br>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <br>
                            <button type="button" class="btn btn-primary">Gestionar tarifas</button>
                            <br><br><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <label>Número tarjeta SIM (Nueva)</label>
                            <input  type="number" name="icc" id="icc" class="form-control datoscli">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label>Digito de control SIM* (Nueva)</label>
                            <input  type="number" name="dc" id="dc"   class="form-control datoscli" style="width:60px">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label>Número tarjeta SIM (Antigua)</label>
                            <input  type="number" name="iccorigen" id="iccorigen" class="form-control datoscli">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label>Digito de control SIM* (Antigua)</label>
                            <input  type="number" name="dcorigen" id="dcorigen"   class="form-control datoscli" style="width:60px">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label>Número Teléfono a portar</label>
                            <input  type="number" name="telportar" id="telportar"  class="form-control datoscli" style="width:150px">
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <label>Modalidad proveedor actual</label>
                            <select id="modalidad" class="form-control datoscli" style="width:150px">
                                <option value="0" selected disabled>Seleccionar una</option>
                                <option value="prepago">Prepago</option>
                                <option value="postpago">Contrato</option>
                            </select>
                        </div>
                        <br>

                    </div>
                    <br><br>
                    <h6><b><i>*Disponible en algunas sim</i></b></h6>
                    <input  type="button" name="previous" class="previous action-button-previous"
                           value="Paso Anterior"/>
                    <input  type="button" name="next" id="next4" class="next action-button" value="Continuar"/>
                </fieldset>

                <fieldset class="caja">


                    <div class="row">
                        <div class="col-xs-12 col-sm-4 text-center" style="border-right: #0f0f0f 2px dotted ">
                            <label><b>Descargue este documento</b></label><br>
                            <p>Haga clic sobre el icono de PDF para comenzar la descarga.</p><br>
                            <img src="../../img/descargue.png" class="img-responsive"><br>
                            <img src="../../img/pdf.png" class="img-responsive"  onclick="imprimir(numero_contrato)" style="cursor:pointer;margin-top:100px"><br><br><br>
                        </div>
                        <div class="col-xs-12 col-sm-4  text-center" style="border-right: #0f0f0f 2px dotted ">
                            <label><b>Firme el documento</b></label><br>
                            <p>El cliente debe firmar este documento para poder tramitar la portabilidad.</p><br>
                            <img src="../../img/firme.png" class="img-responsive" style="margin-top:10px">
                            <br><br><br><br>
                            <label><b>Escanne el documento</b></label><br>
                            <p>Utilice una resolución de al menos 100ppp en formato PDF.</p>
                            <img src="../../img/scanner.png" class="img-responsive" style="margin-top:10px">
                        </div>
                        <div class="col-xs-12 col-sm-4 text-center">
                            <label><b>Cargue el documento</b></label><br>
                            <p>Una vez firmado y escaneado, arrastrelo hasta la zona de lineas inclinada.</p><br>

                            <img src="../../img/cargue.png" class="img-responsive">
                            <div action="upload.php" method="post" class="dropzone" id="my-dropzone" style="width:98%;height:80%;min-height:250px;margin:1%;"></div>
                            <br>


                        </div>
                    </div>
                    <input  type="button" name="previous" class="previous action-button-previous" value="Paso Anterior"/>
                    <input  type="button" name="next" id="next4" class="next action-button" value="Continuar"/>

                </fieldset>

                <fieldset class="caja">

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
                    <input  type="button" name="previous" class="previous action-button-previous" value="Paso Anterior"/>
                </fieldset>
            </form>
        </div>


        <div class="container">
            <!-- Modal -->
            <div class="modal fade" id="imprimir" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Proceso completado correctamente</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <p>La solicitud de portabilidad ha sido generada correctamente. Por favor imprima el contrato</p>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-3">
                                <br><br>
                                <a href="/index.php"><img src="../../img/exit.png"></a>
                            </div>
                            <div class="col-lg-3">
                                <br><br>
                                <a href="" id="imprimir_img" target="_blank"><img src="../../img/printer.png"></a>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.href='porta_fijo.php';">Cerrar</button>
                    </div>
                </div>

            </div>
            </div>
        </div>

        <div id="signature-pad" class="signature-pad" style="position: relative; margin-top:900px;">
            <div class="signature-pad--body">
                <canvas></canvas>
            </div>
            <div class="signature-pad--footer">
                <div class="description">Firme y finalice</div>

                <div class="signature-pad--actions">
                    <div>
                        <br><br><br><br>
                        <button type="button" class="btn btn-default button clear" data-action="clear">Borrar</button>
                        <br><br><br><br>
                    </div>
                    <div>
                        <br><br><br><br>
                        <button type="button" class="btn btn-success button save" data-action="save-svg">Finalizar</button>
                        <br><br><br><br>
                    </div>
                </div>
            </div>
        </div>

</div>

<!-- JAVASCRIPT FILES -->

<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="../firma/js/signature_pad.umd.js"></script>
<script src="../firma/js/app.js"></script>
<script>
    var nuevo = 0;
    var id_cliente_seleccionado = 0;
    var numero_contrato=0;


    $(document).ready(function(){
        // add/remove checked class
        $(".image-radio").each(function(){
            if($(this).find('input[type="radio"]').first().attr("checked")){
                $(this).addClass('image-radio-checked');
            }else{
                $(this).removeClass('image-radio-checked');
            }
        });
        // sync the input state
        $(".image-radio").on("click", function(e){
            $(".image-radio").removeClass('image-radio-checked');
            $(this).addClass('image-radio-checked');
            var $radio = $(this).find('input[type="radio"]');
            $radio.prop("checked",!$radio.prop("checked"));

            e.preventDefault();
        });

        carga_clientes();
        $("#atras1").css('display', 'none');
        $("#next1").css('display', 'none');


    });

    loadScript(plugin_path + 'dropzone/dropzone.js', function() {

        Dropzone.options.myDropzone = {
            init: function() {
                this.on("addedfile", function(file) {
                    // Create the remove button
                    var removeButton = Dropzone.createElement("<button class='btn btn-sm btn-default fullwidth margin-top-10'>Remove file</button>");

                    // Capture the Dropzone instance as closure.
                    var _this = this;

                    // Listen to the click event
                    removeButton.addEventListener("click", function(e) {
                        // Make sure the button click doesn't submit the form:
                        e.preventDefault();
                        e.stopPropagation();

                        // Remove the file preview.
                        _this.removeFile(file);
                        // If you want to the delete the file on the server as well,
                        // you can do the AJAX request here.
                    });

                    // Add the button to the file preview element.
                    file.previewElement.appendChild(removeButton);
                });
            }
        }

    });
    function goToAnchor(anchor) {
        var loc = document.location.toString().split('#')[0];
        document.location = loc + '#' + anchor;
        return false;
    }


    // carga las provincias en el combo correspondiente
    // se llama cada vez que selecciona una comunidad autonoma

    // cargo las regiones por Ajax, cada vez que se cambia el pais
    function carga_comunidades(id, sel = 0) {
        var select = $("#regiones");
        var select2= $("#tit-regiones");
        select.empty();
        select2.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        select2.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: '../../carga_regiones.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id) {
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].region + '</option>');
                        select2.append('<option value="' + data[i].id + '" selected>' + data[i].region + '</option>');
                    }else {
                        select.append('<option value="' + data[i].id + '">' + data[i].region + '</option>');
                        select2.append('<option value="' + data[i].id + '">' + data[i].region + '</option>');
                    }

                });
            }
        });
    }



    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_provincias(id, sel = 0) {
        var select = $("#provincias");
        var select2 = $("#tit-provincias");
        select.empty();
        select2.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        select2.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: '../../carga_prov.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id){
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].provincia + '</option>');
                        select2.append('<option value="' + data[i].id + '" selected>' + data[i].provincia + '</option>');
                    }else {
                        select.append('<option value="' + data[i].id + '">' + data[i].provincia + '</option>');
                        select2.append('<option value="' + data[i].id + '">' + data[i].provincia + '</option>');
                    }
                });
            }
        });
    }

    // cargo las localidades por Ajax cada vez que se cambia de provincia
    function carga_poblaciones(id, sel = 0) {
        var select = $("#localidades");
        var select2 = $("#tit-localidades");
        select.empty();
        select2.empty();
        select.append('<option value="-1">--- Seleccionar una ---</option>');
        select2.append('<option value="-1">--- Seleccionar una ---</option>');
        $.ajax({
            url: '../../carga_pobla.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {id: id},
            success: function (data) {
                $.each(data, function (i) {
                    if (sel > 0 && sel == data[i].id){
                        select.append('<option value="' + data[i].id + '" selected>' + data[i].municipio + '</option>');
                        select2.append('<option value="' + data[i].id + '" selected>' + data[i].municipio + '</option>');
                    }else {
                        select.append('<option value="' + data[i].id + '">' + data[i].municipio + '</option>');
                        select2.append('<option value="' + data[i].id + '">' + data[i].municipio + '</option>');
                    }
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
            url: '../../carga_cli.php',
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




    // cuando se selecciona un cliente, recibo el id y lo cargo por ajax desde carga_cli que al pasarle una id
    // solo devuelve ese registro

    function seleccionado(id) {

        nuevo = 0;

        $.ajax({
            url: '../../carga_cli.php',
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

                $(".ocultar").css('display', 'block');
            }
        });

        id_cliente_seleccionado = id;
        $("#next1").css('display', 'block');

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

        var avanzar = true;

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

        }

        if (this.id == 'next3') {
            // var donante = $("input[name=donante]:checked").val();
            //
            // if (donante == undefined){
            //     alert("Debe seleccionar el operador donante");
            //     return;
            // }
        }

        if (this.id == 'next4') {
            var modalidad = $("#modalidad").val();

            if (modalidad == 0){
                alertaOk("ATENCIÓN","Debe seleccionar el tipo de linea a portar","warning");
                return;
            }

            var num_porta = $("#telportar").val();
            if (num_porta == ""){
                alertaOk("ATENCIÓN","Debe especificar el / los numero/s a portar");
                return;
            }

            var icc = $("#icc").val();
            if (icc == ""){
                alertaOk("ATENCIÓN","Debe especificar número icc de la sim NUEVA");
                return;
            }

            var iccO = $("#iccorigen").val();
            if (iccO == ""){
                alertaOk("ATENCIÓN","Debe especificar número icc de la sim ANTIGUA");
                return;
            }

            guardar_contrato();

        }


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

    function guardar_contrato(firma) {

        // paso 1 quiere decir que se ha seleccionado o creado el cliente,
        // entonces creamos el contrato en borrador y guardamos el id del borrador

        
        var donante = $("#donante").val();
        var tarifa = $("#tarifa").val();
        var icc = $("#icc").val();
        var dc = $("#dc").val();
        var iccO = $("#iccorigen").val();
        var dcO = $("#dcorigen").val();
        var telportar = $("#telportar").val();
        var modalidad = $("#modalidad").val();
        var tipo_cli = $("#tipocli").val();
        var tipo_doc = $("#tipodoc").val();

        $.ajax({
            url: 'guardar-porta.php',
            type: 'POST',
            cache: false,
            async: false,
            data: {
                action: 'porta',
                id_cliente: id_cliente_seleccionado,
                donante :donante,
                tarifa  :tarifa,
                icc :icc,
                dc :dc,
                iccorigen :iccO,
                dcorigen :dcO,
                num_porta :telportar,
                modalidad :modalidad,
                firma : firma,
                tipo:3,
                tipo_cli:tipo_cli,
                tipo_doc:tipo_doc
            },
            success: function (data) {

                if(parseInt(data)>0){
                    // alerta("RESPUESTA SERVIDOR","La solicitud de portabilidad se ha generado correctamente, ahora puede imprimir el documento si lo desea",
                    //     "success","Cerrar","Imprimir Documento","home", "imprimir", "",data);
                    numero_contrato = data;
                    if(parseInt(data)<50){
                        alertaOk('ERROR OPERACIÓN','El servidor ha devuelto el siguiente error: ' + errores[data], 'warning','Entendido','' );
                    }
                }


            }
        });


    }
    function imprimir(solicitud){
        window.open("../servicios/airenetworks/obtener-documento.php?codSolicitud="+solicitud+"&tipo=PORTABILIDAD","_blank");
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



</script>


</body>
</html>