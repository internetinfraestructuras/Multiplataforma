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

//recojo los prefijos que vienen
$prefijos = $_POST['arv'];
$nombreGrupo= $_POST['nombregrupodestino'];
$cifSuperUsuario=$_SESSION['CIF'];


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

    <STYLE>

        UL     { cursor: hand; }
        UL LI                { display: none;font: 18pt;list-style: square; }
        UL.showList LI       { display: block; }
        .defaultStyles UL    { color: orange; }
        UL.defaultStyles LI  { display: none; }

        .div_que_centra{
            width: 650px;
            margin: 0 auto;
            text-align: left;
            border: none;
        }

    </STYLE>


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
                <li class="active">Confirmación creación paquete destino</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Creación paquete destinos</strong>
                        </div>

                        <div class="panel-body">

                            <?php

                            //PRIMERO DE TODO CREO EL GRUPO DE DESTINOS
                            try {
                                $res = $tel->addPaqueteDestino($cifSuperUsuario, $nombreGrupo);
                                if ($res == 1)
                                    $idPaqueteDestino=$tel->getPaqueteID($cifSuperUsuario, $nombreGrupo);
                                else {
                                    throw new Exception('Error creando paquete destinos');
                                    exit();
                                }
                            }
                            catch (Exception $e) {
                                echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
                                exit();
                            }

                            //echo "el id apqauete destino es $idPaqueteDestino<br>";
                            //conectamos a  MYSQL
                            $link = new mysqli(DB_TELEFONIA_SERVER, DB_TELEFONIA_USER, DB_TELEFONIA_PASSWORD,DB_TELEFONIA_DATABASENAME);

                            if (mysqli_connect_errno()) {
                                printf("Falló la conexión: %s\n", mysqli_connect_error());
                                exit();
                            }

                            // Seleccionamos la Base de datos
                            mysqli_select_db($link,$nombre_bd);

                            //echo "Prefijos-> ".$prefijos;
                            //echo "costes-> ".$costes_asociados;


                            //$vector_prefijos = explode(',',$prefijos);
                            //$vector_costes = explode(',',$costes_asociados);

                            //$vector_prefijos = unserialize( stripslashes($prefijos) );
                            $vector_prefijos = explode(',', $prefijos);
                            //var_dump($vector_prefijos);

                            $total_no_asignado=1;
                            $total_no_asignado=0;
                            //$vector_prefijos = $prefijos;
                            //$vector_costes = $costes_asociados;


                            echo "<table width='100%' class=\"table table-bordered table-hover\">";
                            echo "<thead><tr><td>Actividad</td></thead><tbody>";
                            //$colorfila=0;
                            for($i=0;$i<count($vector_prefijos);$i++)
                            {
                                $numero=0;
                                $prefix=$vector_prefijos[$i];
                                //el coste sera el mismo que el del proveedor
                                //en esta plataforma todo va en prepago
                                //$coste_actual=$vector_costes[$i];
                                $coste_actual="";

                               /* $sel="select * from tarifas_prueba where prefijo='$prefix' and usuario_troncal='$troncal'";
                                $result = mysqli_query($link,$sel);
                                $numero = mysqli_num_rows($link,$result);

                                //echo $numero."<br>";
                                if($numero==0)// => esa tarifa para ese usuario no existe => la asigno
                                {*/

                                    //$sel="insert into tarifas_prueba(usuario_troncal,grupo,descripcion,prefijo,coste) select '$troncal',grupo,descripcion,prefijo,'$coste_actual' from tablaprefijos_prueba where prefijo='$prefix'";
                                    $sel="insert into paquetesdestino_tarifas(paquetedestino_id,grupo,descripcion,prefijo,coste) select '$idPaqueteDestino',grupo,descripcion,prefijo,coste from tablaprefijos_prueba where prefijo='$prefix'";
                                    //echo $sel;
                                    $query = mysqli_query($link,$sel);
                                    //echo "<font color=\"green\" >El Prefijo: $prefix ha sido incluido en el paquete</font><br>";
                                    echo "<tr><td style='color: green'>El Prefijo: $prefix ha sido incluido en el paquete</td></tr>";
                                    $total_asignado=$total_asignado+1;

                               /* }
                                else
                                {
                                    echo "<font color=\"red\" >Prefijo: $prefix no fue asignado porque ya existia para edición simple o supresión acuda a ventana de tarifas</font><br>";
                                    $total_no_asignado=$total_no_asignado+1;
                                }*/

                            }

                            echo "</tbody></table>";
                            //resumenes
                            if($total_asignado>0)
                            {
                                echo "<br><font color=\"green\" >Un total de $total_asignado prefijos fueron asignados</font><br>";
                            }

                            if($total_no_asignado>0)
                            {
                                echo "<br><font color=\"red\" >Un total de $total_no_asignado prefijos no fueron asignados</font><br>";
                            }



                            mysqli_close($link);

                            ?>

                        </div>
                        
                        <form action="paquetesdestino.php">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                        VOLVER A PAQUETES DE DESTINO
                                    </button>
                                </div>
                            </div>

                        </form>

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

    var id = getUrlParam('update_success');

    if (id > "") {
        alerta2("Grupo Actualizado","Grupo de recarga actualizado con éxito","success","");
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






</body>
</html>