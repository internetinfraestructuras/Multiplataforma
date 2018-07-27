<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar los datos de clientes ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(2);

$listado= $util->selectWhere3
('PRODUCTOS_MODELOS,PRODUCTOS_TIPOS',
    array("PRODUCTOS_MODELOS.ID","PRODUCTOS_MODELOS.NOMBRE","PRODUCTOS_TIPOS.ID"),
    "PRODUCTOS_MODELOS.id_tipo=productos_tipos.id","PRODUCTOS_MODELOS.ID_TIPO,PRODUCTOS_MODELOS.NOMBRE");

$id=$listado[0][0];
$nombre=$listado[0][1];
$idTipo=$listado[0][2];




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
    <script type="text/javascript" src="js/utiles.js"></script>

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
                <li><a href="#"><?php echo DEF_ALMACEN; ?></a></li>
                <li class="active">Ficha de Modelo</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_MODELOS); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-modelo.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>



                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">
                                                <label>ID</label>
                                                <input type="hidden" name="id" value="<?php echo $id;?>" id="id" class="form-control disabled" hidden>
                                                <input type="text" name="id" value="<?php echo $id;?>" id="id" class="form-control disabled" disabled>
                                            </div>
                                            <div class="col-md-6 col-sm-5">
                                                <label>Nombre:</label>
                                                <input type="text" name="nombre" id="apellidos"
                                                       class="form-control " value="<?php echo $nombre; ?>">
                                            </div>
                                            <div class="col-md-4 col-sm-3">
                                                <label>Tipo de producto: </label><br>
                                                <select name="tipo" id="tipo"
                                                        class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                    <option value="<?php echo $idProveedor;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('productos_tipos', 'id', 'nombre', 'nombre','','','',$idTipo); ?>
                                                </select>
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
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>


<script>


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



</script>



</body>
</html>