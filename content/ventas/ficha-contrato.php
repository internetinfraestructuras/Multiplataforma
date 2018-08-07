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
/*
     * SELECT contratos_lineas.id,contratos_lineas_tipo.nombre as Nombre,contratos.
    FROM contratos_lineas,contratos,contratos_lineas_tipo
    WHERE contratos_lineas.ID_CONTRATO=CONTRATOS.ID
    AND contratos_lineas_tipo.ID=contratos_lineas.ID_TIPO
    AND CONTRATOS.ID_EMPRESA=1 and CONTRATOS.ID_CLIENTE=26
 */
$lineas= $util->selectWhere3('contratos_lineas,contratos_lineas_tipo,contratos,estados_contratos',
    array("contratos_lineas.id","contratos_lineas_tipo.nombre as Tipo","contratos_lineas_tipo.id","contratos_lineas.id_asociado","contratos_lineas.pvp","estados_contratos.nombre as nombrecontrato","estados_contratos.id as idestado"),
    "contratos.id=contratos_lineas.id_contrato 
            and contratos_lineas_tipo.id=contratos_lineas.id_tipo
            AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos.id=".$_GET['idContrato']." AND contratos_lineas.estado!=2 AND estados_contratos.id=contratos_lineas.estado");







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

        <?php require_once('../../menu-izquierdo.php');
        ?>

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

<?php


?>
        <!-- page title -->
        <header id="page-header">
            <h1>Usted esta en</h1>
            <ol class="breadcrumb">
                <li><a href="#"><?php echo DEF_CONTRATOS; ?></a></li>
                <li class="active">Detalles del contrato número:<?php echo $_GET['idContrato'];?></li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="panel-body">
            <p>Detalles del contrato número: <strong><?php echo $_GET['idContrato'];?></strong></p>
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>TIPO</th>
                        <th>PVP</th>
                        <th>ESTADO</th>
                        <th>OPCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                 /*   $listado= $util->selectWhere3('CONTRATOS',
                        array("ID","FECHA_INICIO","FECHA_FIN","ESTADO"),
                        "contratos.id_empresa=".$_SESSION['REVENDEDOR']." AND contratos.id_cliente="-$_GET['idCliente']);*/

$totalContrato=0;
                    for($i=0;$i<count($lineas);$i++)
                    {
                        $id=$lineas[$i][0];
                        $tipo=$lineas[$i][1];
                        $idTipo=$lineas[$i][2];
                        $idAsociado=$lineas[$i][3];
                        $pvp=$lineas[$i][4];
                        $estado=$lineas[$i][5];
                        $idEstado=$lineas[$i][6];
                        $totalContrato+=$pvp;


                        if($idEstado==1)
                        {
                            $bColor="green";
                            $color="white";
                        }
                        if($idEstado==2)
                        {
                            $bColor="red";
                            $color="white";
                        }
                        if($idEstado==3)
                        {
                            $bColor="green";
                            $color="white";
                        }
                        if($idEstado==4)
                        {
                            $bColor="orange";
                            $color="white";
                        }

                        $tipo=strtolower($tipo);

                        if($idTipo!=3)
                            $listado= $util->selectWhere3($tipo, array("ID","nombre","id_servicio_tipo"),  $tipo.".id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);
                        else
                            $listado= $util->selectWhere3($tipo.",almacenes", array("productos.ID","numero_serie"),  "almacenes.id=productos.id_almacen AND almacenes.id_empresa=".$_SESSION['REVENDEDOR']." AND ".$tipo.".id=".$idAsociado);

                        $idServicioTipo=$listado[0][2];
                        
                        $nombre=$listado[0][1];


                        echo "<tr>";
                        echo "<td>$id</td><td>$nombre</td><td>$tipo</td><td>$pvp</td><td style='color:$color;background-color:$bColor;'>$estado</td>";

                        ?>
                        <td class="td-actions text-right">
                            <?php
                            if($idTipo==1)
                                echo "<a href='/mul/content/servicios/ficha-paquete.php?idPaquete=".$idAsociado."&idContrato=".$_GET['idContrato']."&idLineaContrato=".$id."''>";
                            else if($idTipo==2)
                                echo "<a href='/mul/content/servicios/ficha-servicio.php?idServicio=".$idAsociado."&idContrato=".$_GET['idContrato']."&idLineaContrato=".$id."&tipo=$idServicioTipo''>";
                            if($idTipo==3)
                                echo '<a href="/mul/content/almacen/ficha-producto.php?idProducto='.$idAsociado.'">';
                            ?>

                                <button type="button" rel="tooltip" >
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </a>
                            <button type="button" rel="tooltip" class="">
                                <i class="fa  fa-trash" style="font-size:1em; color:green; cursor: pointer" onclick="baja('<?php echo $id;?>');"></i>
                            </button>

                        </td>
                        </tr>

                        <?php
                    }

                    ?>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Total: </strong><?php echo $totalContrato; ?>€</td>
                        <td>
                            <a href="facturar.php?idContrato="<?php echo $id;?>>
                            <button type="button" rel="tooltip" class="">
                                <i class="fa  fa-file-archive-o" style="font-size:1em; color:green; cursor: pointer" onclick="facturar(<?php echo $id;?>)"></i>
                            </button></a>
                        </td>
                    </tr>
                    </tbody>


                </table>
                <hr/>


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




    function facturar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de querer borrar este producto?");

        if(respuesta)
        {

            jQuery.ajax({
                url: 'borrar_producto.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'borrar_producto',
                    p:id
                },
                success: function (data) {

                    location.reload();
                }
            });
        }
    }

    function baja(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de que quieres dar de baja la línea de contrato?");

        if(respuesta)
        {

            jQuery.ajax({
                url: 'baja-servicio.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'baja-servicio',
                    p:id
                },
                success: function (data) {

                    location.reload();
                }
            });
        }
    }
    function confirmar(text){

        return confirm(text);

    }


    // cuando se selecciona un cliente, recibo el id y lo cargo por ajax desde carga_cli que al pasarle una id
    // solo devuelve ese registro


</script>



</body>
</html>