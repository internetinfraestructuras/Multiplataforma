<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}

require_once('../../config/util.php');
require_once('../../clases/telefonia/classTelefonia.php');
$util = new util();
$tel = new Telefonia();

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
                <li class="active">Editar Grupo de Recarga</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Editar Grupo de Recarga</strong>
                        </div>

                        <div class="panel-body">

                            <!-- todo: ******************************************************************************* -->
                            <!-- los campos del formulario se pasan por POST a php/guardar-cli.php-->
                            <!-- todo: ******************************************************************************* -->

                            <?php

                            //recuperamos los datos del grupo de recarga

                            $var=$tel->getGrupodeRecarga($_GET['cif_super'],$_GET['grupoderecarga']);


                            while ($row = mysqli_fetch_array($var)) {
                                $grupo=$row[0];
                                $importe=$row[1];
                                $acumulable=$row[2];
                                $color=$row[3];
                            }

                            ?>

                            <form  action="crear-grupoderecarga.php" method="post"
                            >
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="action" value="edit-gruporecarga"/>

                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-3 col-sm-3">
                                                <label>Nombre Grupo: </label>
                                                <input type="text" name="gruporecarga[nombre]" value="<?php echo $grupo;?>"
                                                       class="form-control" required readonly="yes">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Importe: </label>
                                                <input type="text" name="gruporecarga[importe]" value="<?php echo $importe;?>"
                                                       class="form-control " required>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Acumulable</label>
                                                <select  name="gruporecarga[acumulable]" class="form-control ">
                                                    <?php
                                                    if($acumulable=="SI")
                                                        echo " <option value=\"SI\" selected='selected'>SI</option>
								                            <option value=\"NO\">NO</option>";
                                                    else if($acumulable=="NO")
                                                        echo " <option value=\"SI\" >SI</option>
								                            <option value=\"NO\" selected='selected'>NO</option>";
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label>Color</label>
                                                <input id="background-color" name="gruporecarga[background-color]" value="<?php echo $color;?>" type="color" class="form-control "/>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            NOTA: Acumulable=SI indica que la recarga se efectuará siempre añadiendo el saldo de la recarga
                                            mensual al ya existente para el cliente. Acumulable=NO indica que mensualmente el saldo del cliente
                                            será establecido al importe del grupo de recarga independientemente del ya existente en su cuenta
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit"
                                                    class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                                EDITAR GRUPO DE RECARGA
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



        </div>
    </section>
    <!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>

<script>
    function getUrlParam(name) {
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return (results && results[1]) || undefined;
    }

    function eliminar_parametros_get(){
        var query = window.location.search.substring(1)

        // is there anything there ?
        if(query.length) {
            // are the new history methods available ?
            if(window.history != undefined && window.history.pushState != undefined) {
                // if pushstate exists, add a new state the the history, this changes the url without reloading the page

                window.history.pushState({}, document.title, window.location.pathname);
            }
        }
    }

    var id = getUrlParam('alert_success');
    if (id > "") {
        alerta2("Grupo Creado","Grupo de recarga creado con éxito","success","");
        eliminar_parametros_get();


    } else {
        // there is no id value

    }


</script>

<script>

    var cifSuper = "";
    var nombreGrupo="";

    function borrar_grupo(cif,nbr)
    {
        //alert("viene");

        jQuery.ajax({
            url: 'borrar_gruporecarga.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                a: 'borrar_gruporecarga',
                c:cifSuper,
                n:nombreGrupo
            },
            success: function (data)
            {
                if(data!="")
                    alert(data);
                else{
                    alertaConReload("Eliminación completada","Grupo de recarga eliminado con éxito","success","Aceptar","refrescar");
                }

            }
        });
    }

    function nada(){}

    //EL PROBLEMA ES K NO SE PUEDEN PASAR PARAMETROS!!!! uso parametros globales


    function borrar_gruporecarga(cif,nbr){
        cifSuper=cif;
        nombreGrupo=nbr;

        alerta("Atencion","¿Confirma que desea eliminar este grupo de recarga?",'warning','Continuar','Cancelar',
            "borrar_grupo","nada");
    }


    function  refrescar() {
        location.reload();
    }
</script>


<script>


    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de querer borrar este producto?");

        if(respuesta)
        {

            jQuery.ajax({
                url: 'borrar_provedor.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_proveedor',
                    p:id
                },
                success: function (data)
                {
                    if(data!="")
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



</body>
</html>