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
                <li class="active">CREAR NUEVO MODELO</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>CREAR NUEVO MODELO</strong>
                        </div>

                        <div class="panel-body">

                            <!-- todo: ******************************************************************************* -->
                            <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                            <!-- todo: ******************************************************************************* -->


                            <form class="validate" action="guardar-modelo.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="modelos"/>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Proveedor:</label>
                                                <select name="modelo[proveedor]" id="proveedores" onchange="carga_tipos(this.value)"
                                                        class="form-control pointer ">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php
                                                    $util->carga_select('proveedores', 'id', 'nombre', 'nombre',"id_empresa=".(int)$_SESSION['REVENDEDOR']." AND ID_TIPO_PROVEEDOR=1"); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">

                                                <label>Tipo:</label>
                                                <select name="modelo[tipo]" id="tipos"
                                                        class="form-control pointer " onchange="carga_modelos(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>

                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Nombre modelo </label>
                                                <input type="text" name="modelo[nombre]" value=""
                                                       class="form-control ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit"
                                                    class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                                CREAR MODELO NUEVO
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
            <div class="panel panel-default">

                <div class="panel-body" id="listado">
                    <div id="panel-1" class="panel panel-default">
                        <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_MODELOS; ?></strong> <!-- panel title -->
							</span>

                            <!-- right options -->
                            <ul class="options pull-right list-inline">
                                <li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="Colapse" data-placement="bottom"></a></li>
                                <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="Fullscreen" data-placement="bottom"><i class="fa fa-expand"></i></a></li>
                                <li><a href="#" class="opt panel_close" data-confirm-title="Confirm" data-confirm-message="¿Deseas eleminar este panel?" data-toggle="tooltip" title="Close" data-placement="bottom"><i class="fa fa-times"></i></a></li>
                            </ul>
                            <!-- /right options -->

                        </div>

                        <!-- panel content -->
                        <div class="panel-body">

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE MODELO</th>
                                    <th>TIPO DE PRODUCTO</th>
                                    <th>OPCIONES</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $listado= $util->selectWhere3
                                ('productos_modelos,productos_tipos',
                                    array("productos_modelos.ID","productos_modelos.NOMBRE","productos_tipos.NOMBRE AS NS"),
                                    "productos_modelos.id_tipo=productos_tipos.id","productos_modelos.ID_TIPO,productos_modelos.NOMBRE");

                                for($i=0;$i<count($listado);$i++)
                                {

                                    $id=$listado[$i][0];
                                    $nombre=$listado[$i][1];
                                    $prov=$listado[$i][2];

                                    echo "<tr>";
                                    echo "<td>$id</td><td>$nombre</td><td>$prov</td>";

                                    ?>
                                    <td class="td-actions text-right">
                                        <a href="ficha-modelo.php?idModelo=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" >
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                        <button type="button" rel="tooltip" class="">
                                            <i class="fa  fa-trash" style="font-size:1em; color:green; cursor: pointer" onclick="borrar('<?php echo $id;?>');"></i>
                                        </button>

                                    </td>
                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>

                        </div>
                        <!-- /panel content -->

                        <!-- panel footer -->
                        <div class="panel-footer">


                        </div>
                        <!-- /panel footer -->

                    </div>


                </div>

            </div>
        </div>

    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>


<script>

    console.log("CARGa");
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
    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de querer borrar este modelo de producto?");

        if(respuesta)
        {

            jQuery.ajax({
                url: 'borrar-modelos.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_modelos',
                    p:id
                },
                success: function (data)
                {
                    if(data!="" && data!=1)
                        alert(data);
                    else
                        location.reload();
                }
            });
        }
    }
    function confirmar(text){

        return confirm(text);

    }


</script>



</body>
</html>