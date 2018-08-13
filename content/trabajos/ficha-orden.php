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
 * SELECT ordenes_lineas.ID,ordenes_tipos.NOMBRE,productos.NUMERO_SERIE,contratos_lineas_tipo.NOMBRE
FROM ordenes,ordenes_lineas,contratos_lineas_detalles,ordenes_tipos,productos,contratos_lineas,contratos_lineas_tipo
where ordenes.id=ordenes_lineas.ID_ORDEN
AND contratos_lineas_tipo.id=contratos_lineas.ID_TIPO
AND contratos_lineas.id=contratos_lineas_detalles.ID_LINEA
AND ordenes_lineas.ID_PRODUCTO=productos.ID
AND ordenes_tipos.id=ordenes_lineas.ID_TIPO_ORDEN
AND contratos_lineas_detalles.ID=ordenes_lineas.ID_LINEA_DETALLE_CONTRATO
 */
$listado= $util->selectWhere3('ordenes,ordenes_lineas,contratos_lineas_detalles,ordenes_tipos,productos,contratos_lineas,contratos_lineas_tipo,contratos',
    array("ordenes_lineas.id","ordenes_tipos.nombre","productos.numero_serie","contratos_lineas_tipo.nombre","contratos_lineas.id_tipo","contratos_lineas.id_asociado"),
    "ordenes.id=ordenes_lineas.id_orden AND contratos_lineas_tipo.id=contratos_lineas.id_tipo AND contratos_lineas.id=contratos_lineas_detalles.id_linea
    AND ordenes_lineas.id_producto=productos.id
    AND ordenes_tipos.id=ordenes_lineas.id_tipo_orden
    AND ordenes.id_contrato=contratos.id
    AND contratos_lineas_detalles.id=ordenes_lineas.id_linea_detalle_contrato AND ordenes_lineas.id_orden=".$_GET['idOrden'].' AND contratos.id_empresa='.$_SESSION['REVENDEDOR']);






?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_ORDENES; ?> / Altas</title>
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
                <li><a href="#"><?php echo DEF_ORDENES; ?></a></li>
                <li class="active">Listado de orden</li>
            </ol>
        </header>
        <!-- /page title -->

        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_ORDENES); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-paquete.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>

                                <div class="panel-body">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>ID LINEA</th>
                                                <th>TIPO DE ORDEN</th>
                                                <th>SERVICIO</th>
                                                <th>PRODUCTO</th>
                                                <th>TIPO PRODUCTO</th>

                                                <th>OPCIONES</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php



                                            for($i=0;$i<count($listado);$i++)
                                            {

                                                $idLinea=$listado[0][0];
                                                $tipoOrdenNombre=$listado[0][1];
                                                $producto=$listado[0][2];
                                                $tipoProducto=$listado[0][3];
                                                $idTipo=$listado[0][4];//Si es de tipo paquete o de tipo servicio
                                                $idAsociado=$listado[0][5];





                                                if($idTipo==1)
                                                    echo "ES PAQUETe";
                                                if($idTipo==2)
                                                {
                                                        $servicio= $util->selectWhere3('servicios',
                                                        array("servicios.nombre"),
                                                        "servicios.id_empresa=".$_SESSION['REVENDEDOR']." AND servicios.id=".$idAsociado);
                                                        $servicio=$servicio[0]['nombre'];
                                                }
                                                    echo "ES SERV";
                                                if($idTipo==3)
                                                    echo "ES PROD";



                                                echo "<tr>";
                                                echo "<td>$idLinea</td><td>$tipoOrdenNombre</td><td>$servicio</td><td>$producto</td><td>$tipoProducto</td>";

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
<!--<script type="text/javascript" src="assets/js/app.js"></script>-->
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