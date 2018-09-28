<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║Interfaz para ver estados de portabilidades móviles║
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
    <title><?php echo OWNER; ?> <?php echo DEF_PORTA_CONF; ?> / Estados</title>
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
                <li><a href="#"><?php echo DEF_PORTA_CONF; ?></a></li>
                <li class="active">Estados</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>Estados <?php echo strtoupper(DEF_PORTA_CONF); ?> móviles</strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-paquete.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>



                                    <div class="row">
                                        <div class="col-md-3 col-sm-4">
                                            <label>Proveedor:</label>

                                            <select name="tipo" id="tipo" class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                <option value="<?php echo $idTipoServicio;?>">--- Seleccionar una ---</option>
                                                <?php $util->carga_select('proveedores', 'id', 'nombre', 'nombre','id_tipo_proveedor=2 AND id='.ID_PROVEEDOR_AIRENETWORKS." OR id=".ID_PROVEEDOR_MASMOVIL,'','',$idTipoServicio); ?>
                                            </select>
                                        </div>
                                    </div>



                                </fieldset>
                                <hr/>
                                <div class="panel-body">

                                    <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>CODIGO LINEA</th>
                                            <th>TIPO LINEA</th>
                                            <th>TELEFONO</th>
                                            <th>CLIENTE</th>
                                            <th>TARIFA</th>
                                            <th>FECHA SOLICITUD</th>
                                            <th>CODIGO PORTABILIDAD</th>
                                            <th>ESTADO</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                    if($_GET['proveedor']==ID_PROVEEDOR_AIRENETWORKS) {
                                        require_once("../../clases/airenetwork/clases/Linea.php");
                                        require_once('../../clases/Empresa.php');


                                        $configuracion = Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);

                                        $lineaAire = new Linea($configuracion[0][3], $configuracion[0][1], $configuracion[0][2]);

                                        $rs = $lineaAire->getSolicitudesLineas($filtro);

                                        for ($i = 0; $i < count($rs); $i++) {
                                            $cod = $rs[$i]['cod'];
                                            $tipoLinea = $rs[$i]['tipo'];
                                            $telefono = $rs[$i]['telefono'];
                                            $cliente = $rs[$i]['cliente_final'];
                                            $tarifa = $rs[$i]['tarifa'];
                                            $fechaPeticion = $rs[$i]['fecha_insercion'];
                                            $codPortabilidad = $rs[$i]['cod_port'];
                                            $estado = $rs[$i]['estado'];

                                            echo "<tr>";
                                            echo "<td>$cod</td><td>$tipoLinea</td><td>$telefono</td><td>$cliente</td><td>$tarifa</td><td>$fechaPeticion</td><td>$codPortabilidad</td><td>$estado</td>";
echo ' <td class="td-actions text-right">';
                                            if($estado==LINEA_AIRE_NO_PROCESADA)
                                            {

                                                ?>

                                                    <a onclick="cancelarSolicitud(<?php echo $cod; ?>)" ">
                                                    <button type="button" rel="tooltip">
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                    </a>
                                                <a onclick="obtenerDocumento(<?php echo $cod; ?>,'PORTABILIDAD')" ">
                                                <button type="button" rel="tooltip">
                                                    <i class="fa fa-pagelines"></i>
                                                </button>
                                                </a>


                                                <?php
                                            }?>
                                            </td>



                                            </tr>
                                            <?php
                                        }
                                    }?>

                                        </tbody>

                                    </table>


                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-4">

                                        <input type="checkbox" name="cascada-precio"  placeholder="0" > ¿Realizar una actualización de precios a todos los clientes con dicho paquete contratado?


                                    </div>
                                </div>


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
<script type="text/javascript" src="assets/js/app.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
<script>
    function confirmar(text){

        return confirm(text);

    }
function cancelarSolicitud(numeroSolicitud)
{
    var respuesta = confirmar("¿Seguro/a de querer cancelar esta solicitud de portabilidad?");
    if(respuesta)
    {
        jQuery.ajax({
            url: 'airenetworks/cancelar-solicitud-linea.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{codSolicitud:numeroSolicitud},
            success: function(data)
            {

              alert(data);
            }
        });
    }
}
    function obtenerDocumento(numeroSolicitud,tipo)
    {

            jQuery.ajax({
                url: 'airenetworks/obtener-documento.php',
                type: 'POST',
                cache: false,
                async:true,
                data:{codSolicitud:numeroSolicitud,tipo:tipo},
                success: function(data)
                {

                    alert(data);
                }
            });

    }
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