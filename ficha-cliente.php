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

$producto= $util->selectWhere3('clientes',
    array("clientes.id","clientes.nombre","clientes.apellidos","clientes.dni","clientes.tipo_documento","clientes.direccion"
            ,"clientes.localidad","clientes.provincia","clientes.comunidad","clientes.iban","clientes.swift","clientes.cp","clientes.fijo",
        "clientes.movil","clientes.email","clientes.fecha_alta","clientes.notas","clientes.baja","clientes.banco","clientes.id_consentimiento"
    ,"clientes.id_tipo_cliente","clientes.fecha_nacimiento"),
    " clientes.id_empresa=".$_SESSION['REVENDEDOR']." AND clientes.id=".$_GET['idCliente']."");


$id=$producto[0][0];
$nombre=$producto[0][1];
$apellidos=$producto[0][2];
$dni=$producto[0][3];
$tipo_documento=$producto[0][4];
$direccion=$producto[0][5];
$localidad=$producto[0][6];
$provincia=$producto[0][7];
$comunidad=$producto[0][8];
$iban=$producto[0][9];
$swift=$producto[0][10];
$cp=$producto[0][11];
$fijo=$producto[0][12];
$movil=$producto[0][13];
$email=$producto[0][14];
$fecha=$producto[0][15];
$notas=$producto[0][16];
$baja=$producto[0][17];
$banco=$producto[0][18];
$cons=$producto[0][19];
$tipo=$producto[0][20];
$fechaNac=$producto[0][21];





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
                <li><a href="#"><?php echo DEF_USUARIOS; ?></a></li>
                <li class="active">Datos de cliente</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_USUARIOS); ?></strong>
                        </div>

                        <div class="panel-body">


                            <form class="validate" action="php/guardar-cli.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="clientes"/>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Nombre *</label>
                                                <input type="text" name="clientes[nombre]" value="<?php echo $nombre; ?> "
                                                       class="form-control required">
                                            </div>
                                            <div class="col-md-5 col-sm-5">
                                                <label>Apellidos </label>
                                                <input type="text" name="clientes[apellidos]" value="<?php echo $apellidos; ?>"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Dni </label>
                                                <input type="text" name="clientes[dni]" value="<?php echo $dni; ?>"
                                                       class="form-control " placeholder="99999999A">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-6 col-sm-6">
                                                <label>Dirección </label>
                                                <input type="text" name="clientes[dir]" value="<?php echo $direccion; ?>"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label>CP </label>
                                                <input type="number" min="0" max="99999" name="clientes[cp]" value="<?php echo $cp; ?>"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Fecha Alta</label>
                                                <input type="text" name="clientes[alta]" value="<?php echo $fecha; ?>" class="form-control datepicker required" data-format="yyyy-mm-dd" data-lang="es" data-RTL="false">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4">
                                                <label>Región</label>
                                                <select name="clientes[region]" id="regiones"
                                                        class="form-control pointer " onchange="carga_provincias(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('comunidades', 'id', 'comunidad', 'comunidad','','','',$comunidad); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Provincia </label>
                                                <select name="clientes[provincia]" id="provincias"
                                                        class="form-control pointer " onchange="carga_poblaciones(this.value)">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('provincias', 'id', 'provincia', 'provincia','','','',$provincia); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <label>Localidad </label>
                                                <select name="clientes[localidad]" id="localidades"
                                                        class="form-control pointer ">
                                                    <option value="">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('municipios', 'id', 'municipio', 'municipio','','','',$localidad); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6">
                                                <label>Email </label>
                                                <input type="email" name="clientes[email]" value="<?php echo $email; ?>"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel Fijo</label>
                                                <input type="tel" name="clientes[tel1]" value="<?php echo $fijo; ?>"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Tel Móvil</label>
                                                <input type="tel" name="clientes[tel2]" value="<?php echo $movil; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Notas </label>
                                                <textarea name="clientes[notas]" rows="4"
                                                          class="form-control "><?php echo $notas; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>



                            </form>

                        </div>

                    </div>
                    <!-- /----- -->

                </div>

                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Datos comerciales</strong>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Nombre Banco </label>
                                        <input type="text" name="clientes[banco]" value="<?php echo $banco;?>"
                                               class="form-control ">
                                    </div>


                                    <div class="col-md-12">
                                        <br>
                                        <label>IBAN</label>
                                        <input type="tel" name="clientes[iban]" value="<?php echo $iban;?>" placeholder="ES46 2100 1111 2222 3333 4444"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <label>SWIFT</label>
                                        <input type="tel" name="clientes[iban]" value="<?php echo $swift;?>" placeholder="CAIXESBBXXX"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">


                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit"
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
            <div class="row">
                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_CONTRATOS; ?></strong> <!-- panel title -->
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
                                            <th>FECHA_INICIO</th>
                                            <th>FECHA_FIN</th>
                                            <th>ESTADO</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $listado= $util->selectWhere3('contratos,estados_contratos',
                                            array("contratos.ID","contratos.FECHA_INICIO","contratos.FECHA_FIN","estados_contratos.nombre"),
                                            "contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos.id_cliente=".$_GET['idCliente']." and contratos.estado=estados_contratos.id");


                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i][0];
                                            $inicio=$listado[$i][1];
                                            $fin=$listado[$i][2];
                                            $estado=$listado[$i][3];

                                            if($estado=="ALTA")
                                            {
                                                $bColor="green";
                                                $color="white";
                                            }

                                            else
                                            {
                                                $bColor="Yellow";
                                                $color="black";
                                            }


                                            echo "<tr>";
                                            echo "<td>$id</td><td>$inicio</td><td>$fin</td><td style='background-color:$bColor;color:$color;'>$estado</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="content/ventas/imprimirContrato.php?idContrato=<?php echo $id;?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-print"></i>
                                                    </button>
                                                </a>
                                                <a href="content/ventas/ficha-contrato.php?idContrato=<?php echo $id;?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </a>
                                                <a href="content/ventas/anexos.php?idContrato=<?php echo $id;?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-tasks"></i>
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
                    <!-- /----- -->

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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
<script>

    $(function () {
        $('#example1').DataTable()
        $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar : ",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        })
    });
</script>

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