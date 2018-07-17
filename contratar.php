<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar los datos de clientes ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('config/util.php');
$util = new util();
check_session(2);
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
    <script type="text/javascript" src="js/utiles.js"></script>

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


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_CLIENTES); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="php/guardar-cli.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                             <label>Clientes a modificar</label>
                                            <select id="id" name="id" onchange="seleccionado(this.value)" class="form-control required">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Nombre *</label>
                                                <input type="text" name="nombre" value="" id="nombre"
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-5 col-sm-5">
                                                <label>Apellidos </label>
                                                <input type="text" name="apellidos" id="apellidos"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Dni </label>
                                                <input type="text" name="dni" id="dni"
                                                       class="form-control " placeholder="99999999A">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-6 col-sm-6">
                                                <label>Dirección </label>
                                                <input type="text" name="direccion" id="direccion"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label>CP </label>
                                                <input type="number" min="0" max="99999" name="cp" id="cp"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Fecha Alta</label>
                                                <input type="text" name="alta" id="fechalta" class="form-control datepicker required" data-format="yyyy-mm-dd" data-lang="es" data-RTL="false">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Región</label>
                                                <select name="region" id="regiones"
                                                        class="form-control pointer " onchange="carga_provincias(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad'); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Provincia </label>
                                                <select name="provincia" id="provincias"
                                                        class="form-control pointer " onchange="carga_poblaciones(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Localidad </label>
                                                <select name="localidad" id="localidades"
                                                        class="form-control pointer ">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6">
                                                <label>Email </label>
                                                <input type="email" name="email" id="email"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel 1</label>
                                                <input type="tel" name="tel1" id="tel1"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel 2</label>
                                                <input type="tel" name="tel2" id="tel2" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Notas </label>
                                                <textarea name="notas" id="notas" rows="4"
                                                          class="form-control "></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
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

                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Información</h4>
                            <p><em>Por favor, completa toda la información requerida y revísala antes de proceder a realizar cambios.</em></p>
                            <hr/>
                            <p>

                            </p>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <a href="javascript:;" onclick=""
                               class="btn btn-info btn-xs">Ayuda</a>

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


<script>
    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_provincias(id,sel=0){
        var select = $("#provincias");
        select.empty();
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_prov.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data) {
                $.each(data, function(i){
                    if(sel>0 && sel==data[i].id)
                        select.append('<option value="'+data[i].id+'" selected>'+data[i].provincia+'</option>');
                    else
                        select.append('<option value="'+data[i].id+'">'+data[i].provincia+'</option>');

                });
            }
        });
    }
    // cargo las localidades por Ajax cada vez que se cambia de provincia
    function carga_poblaciones(id,sel=0){
        var select = $("#localidades");
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_pobla.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data) {
                $.each(data, function(i){
                    if(sel>0 && sel==data[i].id)
                        select.append('<option value="'+data[i].id+'" selected>'+data[i].municipio+'</option>');
                    else
                        select.append('<option value="'+data[i].id+'">'+data[i].municipio+'</option>');
                });
            }
        });
    }

    // cargo los clientes para que pueda seleccionarse y editarlo
    function carga_clientes(){
        var select = $("#id");
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        $.ajax({
            url: 'carga_cli.php',
            type: 'POST',
            cache: false,
            async:true,
            success: function(data) {
                $.each(data, function(i){

                    select.append('<option value="'+data[i].id+'">'+data[i].apellidos+" "+data[i].nombre+'</option>');
                });
            }
        });
    }

    $(document).ready(function () {
        carga_clientes(false);
    });


    // cuando se selecciona un cliente, recibo el id y lo cargo por ajax desde carga_cli que al pasarle una id
    // solo devuelve ese registro

    function seleccionado(id){
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

                carga_provincias(parseInt(data[0].region),parseInt(data[0].provincia0));
                $("#dni").val(data[0].dni);
                $("#nombre").val(data[0].nombre);
                $("#apellidos").val(data[0].apellidos);
                $("#direccion").val(data[0].direccion);
                $("#cp").val(data[0].cp);

                //$("#provincias").val(parseInt(data[0].provincia0)).change();
                carga_poblaciones(parseInt(data[0].provincia0),parseInt(data[0].localidad));


                $("#tel1").val(data[0].tel1);
                $("#tel2").val(data[0].tel2);
                $("#email").val(data[0].email);
                $("#notas").val(data[0].notas);
                $("#fechalta").val(data[0].alta);
                // $("#fechalta").attr('disabled','disabled');

                $("#hash").val(md5(id));
            }
        });
    }

</script>



</body>
</html>