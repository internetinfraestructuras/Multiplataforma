<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite la configuracion de contratos ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION))
{
    @session_start();
}
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


require_once('../../config/util.php');
require_once ('../../clases/Servicio.php');
require_once ('../../clases/Empresa.php');
require_once ('../../clases/airenetwork/clases/Tarifa.php');

$util = new util();
check_session(1);

$conf=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);
$listado=Servicio::getServiciosAireNetworks($_SESSION['REVENDEDOR']);


$usuario=$conf[0][1];
$pass=$conf[0][2];
$url=$conf[0][3];

$apiAire=new Tarifa("","","");
$tarifas=$apiAire->getTarifasStatus('ON');




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
    <script type="text/javascript" src="../../js/utiles.js"></script>



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
                <li><a href="#"><?php echo DEF_MODULO_AIRE; ?></a></li>
                <li class="active">Configuración </li>
            </ol>
        </header>
        <!-- /page title -->



            <div class="row">
                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <form class="validate" action="guardar-configuracion-aire.php" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="action" value="configuracion"/>
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>CONFIGURACIÓN AIRENETWORK´S</strong> <!-- panel title -->
							</span>

                                    <!-- right options -->
                                    <ul class="options pull-right list-inline">
                                        <li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="Colapse" data-placement="bottom"></a></li>
                                        <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="Fullscreen" data-placement="bottom"><i class="fas fa-expand"></i></a></li>
                                        <li><a href="#" class="opt panel_close" data-confirm-title="Confirm" data-confirm-message="¿Deseas eleminar este panel?" data-toggle="tooltip" title="Close" data-placement="bottom"><i class="fas fa-times"></i></a></li>
                                    </ul>
                                    <!-- /right options -->

                                </div>

                                <!-- panel content -->
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <p><strong>Nota:</strong> Para obtener la configuración de su módulo de AireNetwork´s </p>
                                            <div class="col-md-6 col-sm-6">
                                                <label>Nombre de Usuario: </label>
                                                <input type="text" name="configuracion[usuario]" value="<?php echo $usuario;?>" id="usuario"
                                                       class="form-control ">
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <label>Password:</label>
                                                <input type="text" name="configuracion[pass]" value="<?php echo $pass;?>" id="pass"
                                                       class="form-control " onchange="calcularPVP(this.value)">
                                            </div>
                                            <div class="col-md-9 col-sm-6">
                                                <label>URL:</label>
                                                <input type="text" name="configuracion[url]" value="<?php echo $url;?>" id="url"
                                                       class="form-control " onchange="calcularPVP(this.value)">

                                            </div>

                                            <div class="col-md-3 col-sm-6" style="margin-top: 15px;">
                                                <a onclick="pingAire()" class="btn btn-warning btn-xlg">TEST CONEXIÓN CON AIRENETWORK´S <i class="fas fa-phone-square"></i></a>

                                            </div>




                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="panel-1" class="panel panel-default">
                                            <div class="panel-heading">
                                                <span class="title elipsis">
                                                    <strong>TARIFAS ASOCIADAS CON AIRENETWORKS</strong> <!-- panel title -->
                                                </span>
                                             </div>
                                        </div>
                                        <p><u>Los cambios de vinculación de tarifas creadas con las de AireNetwork´s sólo se aplicarán a las nuevas altas una vez aplicados los cambios.</u></p>
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>ID SERVICIO</th>
                                                <th>NOMBRE PROPIO</th>
                                                <th>TARIFA AIRENETWORKS</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            for($i=0;$i<count($listado);$i++)
                                            {

                                                $idServicio=$listado[$i][0];
                                                $nombre=$listado[$i][1];
                                                $nombreAPI=$listado[$i][2];



                                                echo "<tr>";
                                                echo '<td><div class="col-md-4 col-sm-6"><input readonly type="text" name="tarifas[internas][]" value='.$idServicio.' class="form-control" ></div></td><td>'.$nombre.'</td>';

                                                echo'<td><select id="tarifas"  name="tarifas[aire][]" class="form-control">';


                                                echo "<option data-id='".$id."' value='0'>Sin Asignar</option>";
                                                for($j=0;$j<count($tarifas);$j++)
                                                {

                                                    $nombreTarifa=$tarifas[$j]['nombre_tarifa'];
                                                    $siglas=$tarifas[$j]['siglas'];



                                                    if($nombreAPI==$siglas)
                                                        $selected="selected";
                                                    else
                                                        $selected="";


                                                    echo "<option $selected data-id='".$siglas."' value='" .$siglas. "'>".$nombreTarifa."</option>";
                                                }
                                                echo '</select></td>';


                                                ?>
                                                </tr>

                                                <?php
                                            }
                                            ?>
                                            </tbody>

                                        </table>

                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                        VALIDAR Y GUARDAR
                                        <span class="block font-lato">verifique que toda la información es correcta</span>
                                    </button>
                                </div>
                            </form>
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
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>


<script>


    function pingAire()
    {

        jQuery.ajax({
            url: 'aire/ping.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{usuario:$("#usuario").val(),pass:$("#pass").val(),url:$("#url").val()},
            success: function(data)
            {
                alert(data);
            }
        });
    }


</script>


</body>
</html>