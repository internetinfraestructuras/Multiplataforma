<?php
//**************************************************************************************
// Interfaz que permite a los usuarios root dar de alta a nuevos revendedores, los cuales
// pasan a ser usuario de la plataforma
//**************************************************************************************


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
    <title><?php echo OWNER; ?> <?php echo DEF_REVENDEDORES; ?> / Altas</title>
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
                <li><a href="#"><?php echo DEF_REVENDEDORES; ?></a></li>
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
                            <strong>ALTAS <?php echo strtoupper(DEF_REVENDEDORES); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="php/guardar-rev.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="revendedores"/>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Nombre *</label>
                                                <input type="text" name="revende[nombre]" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-5 col-sm-5">
                                                <label>Apellidos *</label>
                                                <input type="text" name="revende[apellidos]" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Dni / Cif</label>
                                                <input type="text" name="revende[dni]" value=""
                                                       class="form-control " placeholder="99999999A">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Empresa</label>
                                                <input type="text" name="revende[empresa]" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <label>Dirección</label>
                                                <input type="text" name="revende[dir]" value=""
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label>CP</label>
                                                <input type="number" min="0" max="99999" name="revende[cp]" value=""
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Región</label>
                                                <select name="revende[region]" id="regiones"
                                                        class="form-control pointer" onchange="carga_provincias(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad'); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Provincia </label>
                                                <select name="revende[provincia]" id="provincias"
                                                        class="form-control pointer" onchange="carga_poblaciones(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Localidad </label>
                                                <select name="revende[localidad]" id="localidades"
                                                        class="form-control pointer">
                                                    <option value="">--- Seleccionar una ---</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6">
                                                <label>Email *</label>
                                                <input type="email" name="revende[email]" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <label>
                                                    Web
                                                    <small class="text-muted">- opcional</small>
                                                </label>
                                                <input type="url" name="revende[website]" placeholder="http://"
                                                       class="form-control">
                                            </div>


                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel 1 *</label>
                                                <input type="tel" name="revende[tel1]" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel 2</label>
                                                <input type="tel" name="revende[tel2]" value="" class="form-control">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Usuario *</label>
                                                <input type="text" name="revende[usuario]" id="pass1" value=""
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Clave *</label>
                                                <input type="password" name="revende[pass1]" id="pass1" value=""
                                                       class="form-control required">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Notas </label>
                                                <textarea name="revende[notas]" rows="4"
                                                          class="form-control "></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>
                                                    Logo Panel
                                                    <small class="text-muted">Logotipo para mostrar en panel</small>
                                                </label>

                                                <!-- custom file upload -->
                                                <div class="fancy-file-upload fancy-file-primary">
                                                    <i class="fa fa-upload"></i>
                                                    <input type="file" class="form-control" name="revende[logo]"
                                                           onchange="jQuery(this).next('input').val(this.value);"/>
                                                    <input type="text" class="form-control"
                                                           placeholder="no file selected" readonly=""/>
                                                    <span class="button">Cargar fichero</span>
                                                </div>
                                                <small class="text-muted block">Max tamaño: 10Mb (jpg/png/gif)
                                                </small>

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

    function carga_provincias(id){
        var select = $("#provincias");
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
                    select.append('<option value="'+data[i].id+'">'+data[i].provincia+'</option>');
                });
            }
        });
    }
    function carga_poblaciones(id){
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
                    select.append('<option value="'+data[i].id+'">'+data[i].municipio+'</option>');
                });
            }
        });
    }


</script>



</body>
</html>