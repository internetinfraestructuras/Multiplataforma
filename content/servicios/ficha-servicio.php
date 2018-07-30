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

if(!isset($_GET['idContrato']))
{
$listado= $util->selectWhere3('servicios,servicios_tipos',
    array("servicios.id","servicios.nombre","servicios.pvp","servicios.precio_proveedor","servicios.impuesto","servicios.beneficio","servicios.id_servicio_tipo"),
    "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND servicios.id=".$_GET['idServicio']);
}
else
{
    $listado= $util->selectWhere3('servicios,servicios_tipos,contratos,contratos_lineas',
        array("servicios.id","servicios.nombre","contratos_lineas.pvp","contratos_lineas.precio_proveedor","contratos_lineas.impuesto","contratos_lineas.beneficio","servicios.id_servicio_tipo"),
        "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND contratos.id=".$_GET['idContrato']."
                                                      AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
                                                      AND contratos.id=contratos_lineas.id_contrato AND servicios.id=".$_GET['idServicio']);
}

$id=$listado[0][0];
$nombre=$listado[0][1];
$pvp=$listado[0][2];
$coste=$listado[0][3];
$impuesto=$listado[0][4];
$beneficio=$listado[0][5];
$idTipoServicio=$listado[0][6];



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
                <li><a href="#"><?php echo DEF_SERVICIOS; ?></a></li>
                <li class="active">Ficha de Servicios</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_SERVICIOS); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-servicio.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">
                                                <label>ID</label>
                                                <input type="text"  value="<?php echo $id;?>"  class="form-control disabled" disabled>
                                                <input type="hidden" name="id" value="<?php echo $id;?>" id="id" class="form-control">
                                            </div>
                                            <div class="col-md-6 col-sm-5">
                                                <label>Nombre:</label>
                                                <input type="text" name="apellidos" id="nombre"
                                                       class="form-control " value="<?php echo $nombre; ?>">
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Tipo de Servicio:</label>
                                                <select name="tipo" id="tipo"
                                                        class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                    <option value="<?php echo $idProveedor;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios_tipos', 'id', 'nombre', 'nombre','','','',$idTipoServicio); ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-4">
                                                <label>Precio Proveedor</label>
                                                <input type="text" name="coste" id="dni"
                                                       class="form-control " placeholder="99999999A"  value=<?php echo $coste; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Margen</label>
                                                <input type="text" name="beneficio" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $beneficio; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>PVP</label>
                                                <input type="text" name="impuesto" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $impuesto; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>IMPUESTOS</label>
                                                <input type="text" name="pvp" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $pvp; ?>>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <hr/>
                                <div class="panel-body">

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ATRIBUTO</th>
                                            <th>VALOR</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                            $atributos= $util->selectWhere3('servicios_atributos,servicios_tipos_atributos',
                                            array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                            "servicios_atributos.id_servicio=".$_GET['idServicio']." and servicios_atributos.id_tipo_atributo=servicios_tipos_atributos.id");

                                        for($i=0;$i<count($atributos);$i++)
                                        {
                                            $id=$atributos[$i][0];
                                            $attr=$atributos[$i][1];
                                            $valor=$atributos[$i][3];



                                            echo "<tr>";

                                            echo "<tr>";
                                            echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$attr' class='form-control' disabled />
                                            </td><td><input name='atributo[valor][]' value='$valor' class='form-control' /></td>";

                                            ?>

                                            </tr>



                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>

                                    </table>

                                </div>
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

<!--<script type="text/javascript" src="../../assets/js/app.js"></script>-->


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