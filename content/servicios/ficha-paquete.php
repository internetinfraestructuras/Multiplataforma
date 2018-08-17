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

$listado= $util->selectWhere3('paquetes',
    array("ID","NOMBRE","PRECIO_COSTE","MARGEN","IMPUESTO","PVP"),
    "paquetes.id_empresa=".$_SESSION['REVENDEDOR']." and paquetes.id=".$_GET['idPaquete']);

$id=$listado[0][0];
$nombre=$listado[0][1];
$coste=$listado[0][2];
$margen=$listado[0][3];
$impuestos=$listado[0][4];
$pvp=$listado[0][5];



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
                <li><a href="#"><?php echo DEF_PAQUETES; ?></a></li>
                <li class="active">Ficha de Paquete</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_PAQUETES); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-paquete.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>
                                    <?php
                                    $readonly="";
                                    if(isset($_GET['idContrato']))
                                    {
                                    echo '<input type="hidden" name="idContrato" value='.$_GET['idContrato'].' id="id" class="form-control">';
                                    echo '<input type="hidden" name="idLinea" value='.$_GET['idLineaContrato'].' id="id" class="form-control">';
                                    $readonly="readonly";
                                    } ?>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">
                                                <label>ID</label>
                                                <input type="text" name="id" value="<?php echo $id;?>" id="nombre" class="form-control disabled" readonly>
                                            </div>
                                            <div class="col-md-10 col-sm-5">
                                                <label>Nombre:</label>
                                                <input type="text" name="nombre" id="apellidos"
                                                       class="form-control " value="<?php echo $nombre; ?>" <?php echo $readonly; ?>>
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
                                                <input type="text" name="margen" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $margen; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>IMPUESTOS</label>
                                                <input type="text" name="impuesto" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $impuestos; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>PVP</label>
                                                <input type="text" name="pvp" id="dni"
                                                       class="form-control " placeholder="99999999A" value=<?php echo $pvp; ?>>
                                            </div>

                                        </div>
                                    </div>
                                </fieldset>

                                <hr/>
                                <div class="panel-body">
                                    <?php
                                    //Si no tenemos el idContrato quiere decir que debemos de mostrar LA FICHA DEL PAQUETE GENERAL NO INSTANCIADO PARA UN CLIENTE
                                    if(isset($_GET['idContrato']))
                                    {?>
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>SERVICIO</th>
                                            <th>TIPO SERVICIO</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        if(!isset($_GET['idContrato']))
                                        {
                                            /*
                                             * SELECT servicios.NOMBRE,servicios_tipos.NOMBRE
FROM contratos_lineas,contratos_lineas_detalles,servicios,servicios_tipos
WHERE contratos_lineas.ID=contratos_lineas_detalles.ID_LINEA
AND contratos_lineas_detalles.ID_SERVICIO=servicios.id
AND servicios.ID_SERVICIO_TIPO=servicios_tipos.ID
AND contratos_lineas_detalles.ID_LINEA=250
                                             */
                                            $atributos= $util->selectWhere3('servicios,paquetes_servicios,paquetes,servicios_tipos',
                                                array("paquetes_servicios.id,servicios.nombre,servicios.precio_proveedor,servicios.beneficio,servicios.impuesto,servicios.pvp,servicios.id,servicios_tipos.nombre,servicios.id"),
                                                "paquetes.id=paquetes_servicios.id_paquete 
                                                AND servicios.id=paquetes_servicios.id_servicio 
                                                AND servicios.id_servicio_tipo=servicios_tipos.id 
                                                AND paquetes_servicios.id_paquete=".$_GET['idPaquete']);
                                        }
                                        else
                                        {
                                            $atributos= $util->selectWhere3('contratos_lineas,contratos_lineas_detalles,servicios,servicios_tipos',
                                                array('servicios.id,servicios.nombre,servicios_tipos.nombre,servicios.id_servicio_tipo'),
                                                'contratos_lineas.id=contratos_lineas_detalles.id_linea
                                                AND contratos_lineas_detalles.id_servicio=servicios.id
                                                AND servicios.id_servicio_tipo=servicios_tipos.id
                                                AND contratos_lineas_detalles.estado=1 
                                                AND contratos_lineas_detalles.id_linea='.$_GET['idLineaContrato']." GROUP BY contratos_lineas_detalles.ID_TIPO_SERVICIO");
                                        }


                                        for($i=0;$i<count($atributos);$i++)
                                        {
                                            $id=$atributos[$i][0];
                                            $nombre=$atributos[$i][1];
                                            $tipo=$atributos[$i][2];
                                            $servicioId=$atributos[$i][3];





                                            echo "<tr>";
                                            echo "<td>$id</td>
                                                  <td>$nombre</td>
                                                  <td>$tipo</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-servicio.php?idServicio=<?php echo $idServicio;?>">
                                                    <button type="button" rel="tooltip" >
                                                        <?php
                                                        echo "<a href='/mul/content/servicios/ficha-servicio-paquete.php?idServicio=".$id."&idContrato=".$_GET['idContrato']."&idLineaContrato=".$_GET['idLineaContrato']."&tipo=$servicioId''>";
                                                        echo ' <i class="fa fa-pencil"></i>';
                                                        echo "</a>";
                                                        ?>

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
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    <p>Descripción de los servicios incluidos en el paquete:</p>
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>SERVICIO</th>
                                            <th>TIPO SERVICIO</th>
                                            <th>COSTE</th>
                                            <th>BENEFICIO</th>
                                            <th>IMPUESTO</th>
                                            <th>PVP</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php


                                            $atributos= $util->selectWhere3('servicios,paquetes_servicios,paquetes,servicios_tipos',
                                            array("paquetes_servicios.id,servicios.nombre,servicios.precio_proveedor,servicios.beneficio,servicios.impuesto,servicios.pvp,servicios.id,servicios_tipos.nombre"),
                                            "paquetes.id=paquetes_servicios.id_paquete AND servicios.id=paquetes_servicios.id_servicio AND servicios.id_servicio_tipo=servicios_tipos.id 
                                                    AND paquetes_servicios.id_paquete=".$_GET['idPaquete']);

                                        for($i=0;$i<count($atributos);$i++)
                                        {

                                            $attr=$atributos[$i][0];
                                            $valor=$atributos[$i][1];
                                            $coste=$atributos[$i][2];
                                            $beneficio=$atributos[$i][3];
                                            $impuesto=$atributos[$i][4];
                                            $pvp=$atributos[$i][5];
                                            $idServicio=$atributos[$i][6];
                                            $servicio=$atributos[$i][7];



                                            echo "<tr>";
                                            echo "<td>$attr</td><td>$valor</td><td>$servicio</td><td>$coste</td><td>$beneficio</td><td>$impuesto</td><td>$pvp</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-servicio.php?idServicio=<?php echo $idServicio;?>">
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
                                    <?php
                                    }
                                    ?>
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
<script type="text/javascript">var plugin_path = 'assets/plugins/';</script>
<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<!--<script type="text/javascript" src="assets/js/app.js"></script>-->


<script>


    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Estas seguro de eliminar el servicio del paquete? Si elimina el servicio del paquete se cobrarán como servicios independientes los demás servicios");


        if(respuesta)
        {

            jQuery.ajax({
                url: 'cancelar-baja.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'cancelar-baja',
                    id:id,
                    idPaquete:<?php echo $_GET['idPaquete']; ?>,
                    idContrato:<?php echo $_GET['idContrato']; ?>,
                    idLineaContrato:<?php echo $_GET['idLineaContrato']; ?>,
                    productos:productos
                },
                success: function (data) {

                    console.log(data);
                    //  location.reload();
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