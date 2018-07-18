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
    <title><?php echo OWNER; ?> <?php echo DEF_CLIENTES; ?> / Altas</title>
    <meta name="description" content=""/>
    <meta name="Author" content="<?php echo AUTOR; ?>" />

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme"/>

</head>
<!--
    .boxed = boxed version
-->
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

        <?php require_once ('../../menu-superior.php');


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
                <li><a href="#"><?php echo DEF_ALMACEN; ?></a></li>
                <li class="active">CREAR NUEVOS ATRIBUTOS</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">
                <div class="col-md-12">
                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>CREAR NUEVO ATRIBUTO</strong>
                        </div>

                        <div class="panel-body">

                            <!-- todo: ******************************************************************************* -->
                            <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                            <!-- todo: ******************************************************************************* -->


                            <form class="validate" action="guardar-atributo.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="clientes"/>
                                    <div class="row">

                                        <div class="col-md-12">

                                            <!-- ------ -->
                                            <div class="panel panel-default">
                                                <div class="panel-heading panel-heading-transparent">
                                                    <strong>Nuevo atributo</strong>
                                                </div>
                                                <div class="panel-body">

                                                    <!-- todo: ******************************************************************************* -->
                                                    <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                                                    <!-- todo: ******************************************************************************* -->


                                                    <form class="validate" action="guardar-producto.php" method="post"
                                                          enctype="multipart/form-data">
                                                        <fieldset>
                                                            <!-- required [php action request] -->
                                                            <input type="hidden" name="action" value="atributos"/>

                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-md-4 col-sm-4">
                                                                        <label>Proveedor:</label>
                                                                        <select name="atributo[proveedor]" id="proveedores" onchange="carga_tipos(this.value)"
                                                                                class="form-control pointer ">
                                                                            <option value="">--- Seleccionar una ---</option>
                                                                            <?php
                                                                            $util->carga_select('proveedores', 'id', 'nombre', 'nombre',"id_empresa=".(int)$_SESSION['REVENDEDOR']); ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-4">

                                                                        <label>Tipo:</label>
                                                                        <select name="atributo[tipo]" id="tipos"
                                                                                class="form-control pointer " onchange="carga_modelos(this.value)">
                                                                            <option value="">--- Seleccionar una ---</option>

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-4">
                                                                        <label>Modelos </label>
                                                                        <select name="atributo[modelo]" id="modelos"  onchange="carga_atributos(this.value)"
                                                                                class="form-control pointer ">
                                                                            <option value="">--- Seleccionar una ---</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" >
                                                                <div class="form-group" id="atributos">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group">

                                                                    <div class="col-md-12 col-sm-6">
                                                                        <label>Nombre del atributo: </label>
                                                                        <input type="text" name="atributo[nombre]" value=""
                                                                               class="form-control ">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="submit"
                                                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                                                        CREAR ATRIBUTO
                                                                    </button>
                                                                </div>
                                                            </div>



                                                        </fieldset>



                                                    </form>

                                                </div>

                                            </div>
                                            <!-- /----- -->

                                        </div>



                                    </div>





                                </fieldset>



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
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>




<script>

    // carga los modelos al combo correspondiente

    function carga_modelos(id)
    {
        var select = jQuery("#modelos");
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        jQuery.ajax({
            url: 'carga_modelos.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {
                $.each(data, function(i){
                    select.append('<option value="'+data[i].id+'">'+data[i].nombre+'</option>');
                });
            }
        });
    }
    function carga_tipos(id)
    {
        var select = jQuery("#tipos");
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        jQuery.ajax({
            url: 'cargar_tipos.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {
                $.each(data, function(i){
                    select.append('<option value="'+data[i].id+'">'+data[i].nombre+'</option>');
                });
            }
        });
    }
    function carga_atributos(id)
    {
        var div = jQuery("#atributos");
        div.empty();
        jQuery.ajax({
            url: 'cargar_atributos.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {
                div.append('<H3>LISTA DE ATRIBUTOS ASOCIADOS A ESTE PRODUCTO</H3><hr/>' +
                    '         <ul>');
                $.each(data, function(i)
                {
                    div.append('<li><label>'+data[i].NOMBRE+'</label></li>');
                });
                div.append('</ul>');
            }
        });
    }


</script>



</body>
</html>