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

$producto= $util->selectWhere3('productos,productos_tipos,productos_modelos,almacenes,proveedores',
    array("productos.id",
        "productos.numero_serie",
        "productos_tipos.nombre as Tipo",
        "productos_modelos.nombre as Modelo,proveedores.nombre as proveedor,
        productos.precio_prov,
        productos.margen,
        productos.pvp,
        productos.impuestos,proveedores.id
       ,productos.id_tipo_producto as idTipo, productos.id_modelo_producto as idModelo"),
    "productos.id_tipo_producto=productos_tipos.id
                                                    AND productos.id_modelo_producto=productos_modelos.id 
                                                    AND almacenes.id=productos.id_almacen 
                                                    AND productos.id_proveedor=proveedores.id
                                                    AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND productos.id=".$_GET['idProducto']."");

$id=$producto[0][0];
$numeroSerie=$producto[0][1];
$tipo=$producto[0][2];
$modelo=$producto[0][3];
$proveedor=$producto[0][4];
$precioProv=$producto[0][5];
$margen=$producto[0][6];
$pvp=$producto[0][7];
$impuestos=$producto[0][8];
$idProveedor=$producto[0][9];
$idTipo=$producto[0][10];
$idModelo=$producto[0][11];



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
                <li class="active">Fiche de Producto</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_ALMACEN); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-producto.php" method="post"
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
                                            <div class="col-md-10 col-sm-5">
                                                <label>Número de Serie:</label>
                                                <input type="text" name="numeroSerie" id="apellidos"
                                                       class="form-control " value="<?php echo $numeroSerie; ?>">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-4 col-sm-3">
                                                <label>Proveedor </label><br>
                                                <select name="proveedor" id="proveedores"
                                                        class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                <option value="<?php echo $idProveedor;?>">--- Seleccionar una ---</option>
                                                <?php $util->carga_select('proveedores', 'id', 'nombre', 'nombre','id_tipo_proveedor=1','','',$idProveedor); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4 col-sm-6">
                                                <label>Tipo </label><br>
                                                <select name="tipo" id="tipos"
                                                        class="form-control pointer " onchange="carga_modelos(this.value)">
                                                    <option value="<?php echo $idTipo; ?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('productos_tipos', 'id', 'nombre', 'nombre','','','',$idTipo); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <label>Modelo</label>
                                                <select name="modelo" id="modelos"
                                                        class="form-control pointer " onchange="carga_atributos(this.value)">
                                                    <option value="<?php echo $idModelo;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('productos_modelos', 'id', 'nombre', 'nombre','id_tipo='.$idTipo,'','',$idModelo); ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-4">
                                                <label>Precio Proveedor</label>
                                                <input type="text" name="coste" id="dni"
                                                       class="form-control " placeholder="99999999A"  value=<?php echo $precioProv; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Margen</label>
                                                <input type="text" name="margen" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $margen; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>PVP</label>
                                                <input type="text" name="pvp" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $pvp; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>IMPUESTOS</label>
                                                <input type="text" name="impuesto" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $impuestos; ?>>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <hr/>
                                <div class="panel-body datos-atributos">

                                    <table id="example2 tabla-atributos" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ATRIBUTO</th>
                                            <th>VALOR</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                            $atributos= $util->selectWhere3('productos_atributos,productos_modelos_atributos',
                                            array("productos_modelos_atributos.NOMBRE,productos_atributos.VALOR,productos_atributos.id"),
                                            " productos_atributos.ID_PRODUCTO=".$_GET['idProducto']."
                                            AND productos_atributos.ID_ATRIBUTO=productos_modelos_atributos.ID");


                                        for($i=0;$i<count($atributos);$i++)
                                        {

                                            $attr=$atributos[$i][0];
                                            $valor=$atributos[$i][1];
                                            $id=$atributos[$i][2];



                                            echo "<tr>";
                                            echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$attr' class='form-control' disabled />
                                            </td><td><input name='atributo[valor][]' value='$valor' class='form-control' /></td>";

                                            ?>

                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>

                                    </table>


                                </div>
                                <div id="atributos-nuevos"></div>
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
        console.log("ATRUIBUTS");
        var div = jQuery("#atributos-nuevos");
        div.empty();
        jQuery.ajax({
            url: 'cargar_atributos.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {
                $(".datos-atributos").css("display","none");
                $.each(data, function(i)
                {
                    div.append('<div class="col-md-1 col-sm-1"><label>'+data[i].NOMBRE+'</label>' +
                        '<input type="text" name="atributo-nuevo[id][]"  class="form-control " /><input type="text" value="'+data[i].ID+'" hidden name="atributo-nuevo[valor][]"/></div>');
                });
            }
        });


    }



</script>



</body>
</html>