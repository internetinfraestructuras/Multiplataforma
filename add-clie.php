<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_CLIENTES; ?> / Altas</title>
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

    <style>
        .alerta{
            font-size: 1.3em;
            font-weight: 400;
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

        <?php require_once ('menu-superior.php'); ?>

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
                <li><a href="#"><?php echo DEF_CLIENTES; ?></a></li>
                <li class="active">Agregar</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-10">

            <div class="row">

                <div class="col-md-7">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Datos Personales</strong>
                        </div>

                        <div class="panel-body">

                            <!-- todo: ******************************************************************************* -->
                            <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                            <!-- todo: ******************************************************************************* -->


                            <form class="validate" action="" method="post" style="margin-bottom:0px" enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="clientes"/>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-4 col-xs-12">
                                                <label>Tipo de Cliente</label>
                                                <select name="tipocli" id="tipocli"  class="form-control pointer">
                                                    <option value="-1">--- Seleccionar uno ---</option>
                                                    <?php $util->carga_select('clientes_tipos', 'ID', 'NOMBRE', 'ID'); ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <label>Tipo de Documento</label>
                                                <select name="tipodoc" id="tipodoc" onchange="cambia_tipo_cliente(this.value)" class="form-control pointer">
                                                    <option value="-1">--- Seleccionar uno ---</option>
                                                    <?php $tiposdoc=$util->carga_select('tipos_documentos', 'ID', 'NOMBRE', 'ID'); ?>
                                                </select>
                                            </div>


                                            <div class="col-xs-12 col-sm-4">
                                                <label id="tipodocumento">Dni</label>
                                                <input type="text" name="dni" id="dni"
                                                       class="form-control datoscli " placeholder="99999999A">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-5">
                                                <label>Nombre *</label>
                                                <input type="text" name="clientes[nombre]" id="nombre"
                                                       class="form-control required">
                                            </div>
                                            <div class="col-xs-12 col-sm-7">
                                                <label>Apellidos </label>
                                                <input type="text" name="clientes[apellidos]" id="apellidos"
                                                       class="form-control ">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6">
                                                <label>Email </label>
                                                <input type="email" name="clientes[email]" id="mail"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <label>Tel Fijo</label>
                                                <input type="tel" name="clientes[tel1]" id="tel1"
                                                       class="form-control">
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <label>Tel Móvil</label>
                                                <input type="tel" name="clientes[tel2]" id="tel2" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-3">
                                                <label>Fecha Nacimiento</label>
                                                <input type="date" name="nacim" id="nacim" class="form-control datoscli">
                                            </div>
                                            <div class="col-md-4 col-xs-6">
                                                <label>Nacionalidad </label>
                                                <select name="nacion" id="nacion"
                                                        class="form-control pointer"  onchange="carga_comunidades(this.value)">
                                                    <option value="-1">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('pais', 'id', 'paisnombre', 'paisnombre','','','',28); ?>

                                                </select>
                                            </div>
                                            <div class="col-md-4 col-xs-6">
                                                <label>Región</label>
                                                <select name="clientes[region]" id="regiones"
                                                        class="form-control pointer " onchange="carga_provincias(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad'); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-4 col-xs-12">
                                                <label>Provincia </label>
                                                <select name="clientes[provincia]" id="provincias"
                                                        class="form-control pointer " onchange="carga_poblaciones(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5 col-xs-12">
                                                <label>Localidad </label>
                                                <select name="clientes[localidad]" id="localidades"
                                                        class="form-control pointer ">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <label>CP </label>
                                                <input type="number" min="0" max="99999" name="clientes[cp]" id="cp"
                                                       class="form-control ">
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-xs-12 col-lg-5">
                                                <label>Dirección </label>
                                                <input type="text" name="clientes[dir]" id="direccion" class="form-control">
                                            </div>
                                            <div class="col-lg-7 col-xs-12">
                                                <label>Notas </label>
                                                <input type="text" name="clientes[notas]" id="notas" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">

                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Datos comerciales</strong>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label>Nombre Banco </label>
                                        <input type="text" name="clientes[banco]" id="banco" class="form-control ">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Dirección Banco </label>
                                        <input type="text" name="clientes[banco]" id="dirbanco"  class="form-control ">
                                    </div>

                                    <div class="col-md-8">
                                        <br>
                                        <label>IBAN</label>
                                        <input type="tel" name="clientes[iban]" id="iban" placeholder="ES4621001111222233334444"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <br>
                                        <label>SWIFT</label>
                                        <input type="tel" name="clientes[swift]" id="swift"  placeholder="CAIXESBBXXX"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Consentimientos LOPD</strong>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Seleccionar una opción <a href="lopd.html" target="_blank"><i class="fa fa-question-circle"></i></a> </label>
                                        <br>
                                        <select name="consentimiento" id="consentimiento"
                                                class="form-control pointer">
                                            <option value="-1">--- --- ---</option>
                                            <?php $util->carga_select('clientes_consentimientos', 'ID', 'NOMBRE', 'NOMBRE'); ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-body">


                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" onclick="verificaryGuardar();"
                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                        VALIDAR Y GUARDAR
                                        <span class="block font-lato">verifique que toda la información es correcta</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

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
<script type="text/javascript" src="assets/js/validar.js"></script>


<script>
    var volver=false;

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


    function verificaryGuardar(){

        volver = false;

        var nom = $("#nombre").val();
        var ape = $("#apellidos").val();
        var dni = $("#dni").val();
        var dir = $("#direccion").val();
        var reg = $("#regiones").val();
        var pro = $("#provincias").val();
        var loc = $("#localidades").val();
        var cp = $("#cp").val();
        var mail = $("#mail").val();
        var tl1 = $("#tel1").val();
        var tl2 = $("#tel2").val();
        var notas = $("#notas").val();
        var tdoc = $("#tipodoc").val();
        var tcli = $("#tipocli").val();
        var fnac = $("#nacim").val();
        var lopd = $("#consentimiento").val();
        var banco = $("#banco").val();
        var dirbanco = $("#dirbanco").val();
        var iban = $("#iban").val();
        var swift = $("#swift").val();
        var nacion = $("#nacion").val();
        var region = $("#regiones").val();
        var provincia = $("#provincias").val();
        var localidad = $("#localidades").val();

        var texto ='';

        
        if (tcli == -1) {
            texto  = texto +'<span class="alerta"> Debe seleccionar el tipo de cliente</span><br>';
            volver=true;
        }

        if (tdoc == -1) {
            texto = texto +'<span class="alerta"> Debe seleccionar el tipo de documento del cliente</span><br>';
            volver=true;
        }
        if (dni == '') {
            texto = texto +'<span class="alerta"> Debe teclear el documento del cliente</span><br>';
            volver=true;
        }
        if(tdoc==1 || tdoc==2) {
            if (!validadDNI(dni)) {
                texto = texto + '<span class="alerta"> El documento de identidad no es correcto</span><br>';
                volver = true;
            }
        } else if(tdoc==3) {
            if (!validarCIF(dni)) {
                texto = texto + '<span class="alerta"> El CIF no es correcto</span><br>';
                volver = true;
            }
        }
        if (nom == '') {
            texto = texto +'<span class="alerta"> Debe teclear el nombre del cliente</span><br>';
            volver=true;
        }

        if (ape == '') {
            texto = texto +'<span class="alerta"> Debe teclear los apellidos del cliente</span><br>';
            volver=true;
        }
        
        if (mail == '' || !validateEmail(mail)) {
            texto = texto +'<span class="alerta"> Debe teclear el email válido</span><br>';
            volver=true;
        }

        if (tl1 == '' && tl2 == '') {
            texto = texto +'<span class="alerta"> Debe teclear al menos un número de teléfono</span><br>';
            volver=true;
        }

        if (fnac == '') {
            texto = texto +'<span class="alerta"> Debe teclear la fecha de nacimiento del cliente</span><br>';
        }

        if (dir == '') {
            texto = texto +'<span class="alerta"> Debe teclear la dirección del cliente</span><br>';
            volver=true;
        }
        
        if (region == -'') {
            texto = texto +'<span class="alerta"> Debe seleccionar la región del cliente</span><br>';
            volver=true;
        }
        
        if (provincia == -'') {
            texto = texto +'<span class="alerta"> Debe seleccionar la provincia del cliente</span><br>';
            volver=true;
        }
        
        if (localidad == -'') {
            texto = texto +'<span class="alerta"> Debe seleccionar la localidad del cliente</span><br>';
            volver=true;
        }
        
        if (cp == '') {
            texto = texto +'<span class="alerta"> Debe teclear el código postal del cliente</span><br>';
            volver=true;
        }


        if (iban != '') {
            if(!validarIBAN(iban))
                texto = texto +'<span class="alerta"> El número IBAN no es correcto</span><br>';
        }

        // si la diferencia entre la fecha de hoy y la de nacimiento no es superior a dias es que es menor de edad

        if (difference = dateDiffInDays(new Date(fnac), new Date(hoy()))<6570) {
            texto = texto +'<span class="alerta"> El Cliente no parece ser mayor de edad</span><br>';
            // return;
        }
        
        if (lopd == -1) {
            texto = texto +'<span class="alerta"> Debe seleccionar un tipo de consentimiento para la LOPD</span><br>';
            volver=true;
        }

        if(texto!=''){

            alerta("Atención", texto, 'warning','Continuar','Volver','confirmarGuardar','cancelarguardar');

        } else confirmarGuardar();

   }


    function cancelarguardar() {


    }

   function confirmarGuardar(){

       if(volver)
           return;

       var nom = $("#nombre").val();
       var ape = $("#apellidos").val();
       var dni = $("#dni").val();
       var dir = $("#direccion").val();
       var reg = $("#regiones").val();
       var pro = $("#provincias").val();
       var loc = $("#localidades").val();
       var cp = $("#cp").val();
       var mail = $("#mail").val();
       var tl1 = $("#tel1").val();
       var tl2 = $("#tel2").val();
       var notas = $("#notas").val();
       var tdoc = $("#tipodoc").val();
       var tcli = $("#tipocli").val();
       var fnac = $("#nacim").val();
       var lopd = $("#consentimiento").val();
       var banco = $("#banco").val();
       var dirbanco = $("#dirbanco").val();
       var iban = $("#iban").val();
       var swift = $("#swift").val();
       var nacion = $("#nacion").val();
       var region = $("#regiones").val();
       var provincia = $("#provincias").val();
       var localidad = $("#localidades").val();


       var clientes = {
           nombre: nom, apellidos: ape, dni: dni, dir: dir, cp: cp, region: reg, provincia: pro,
           localidad: loc, email: mail, tel1: tl1, tel2: tl2, notas: notas, tipodoc:tdoc,
           tipocli:tcli, nacimiento: fnac, lopd:lopd, banco:banco, iban:iban, swift:swift, dirbanco:dirbanco, nacion:nacion
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
                   alert("Cliente guardado correctamente");
                   location.reload();
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

</script>



</body>
</html>