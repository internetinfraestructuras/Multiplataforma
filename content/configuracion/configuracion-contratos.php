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
require_once('../../config/util.php');
$util = new util();
check_session(1);

$listado=$util->selectWhere3("textos_legales",array("texto,id_servicio"),"id_empresa=".$_SESSION['REVENDEDOR']."");

$textosGenerales="";
$textoInternet="";
$textoFijo="";
$textoMovil="";
$textoTV="";

for($i=0;$i<count($listado);$i++)
{
    if($listado[$i][1]==0)
        $textosGenerales=$listado[$i][0];
    if($listado[$i][1]==1)
        $textoInternet=$listado[$i][0];
    if($listado[$i][1]==2)
        $textoFijo=$listado[$i][0];
    if($listado[$i][1]==3)
        $textoMovil=$listado[$i][0];
    if($listado[$i][1]==4)
        $textoTV=$listado[$i][0];
}



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
                <li><a href="#"><?php echo DEF_CONTRATOS; ?></a></li>
                <li class="active">Configuración contratos</li>
            </ol>
        </header>
        <!-- /page title -->



            <div class="row">
                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <form class="validate" action="guardar-configuracion.php" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="action" value="configuracion"/>
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>CONFIGURACIÓN CONDICIONES GENERALES</strong> <!-- panel title -->
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
                                    <div class="col-md-12">
                                    <h3>Condiciones general</h3>
                                    <p>Texto de introducción al contrato con los siguientes parámetros opcionales: </p>
                                        <ul>{nombreEmpresa}</ul>
                                        <ul>{direccionEmpresa}</ul>
                                        <ul>{dniEmpresa}</ul>
                                        <ul>{nombreCliente}</ul>
                                        <ul>{direccionCliente}</ul>
                                        <ul>{dniCliente}</ul>
                                    <textarea id="froala-editor" class="condicion-general" name="condiciones[generales]" placeholder="Contrato legal">
                                       <?php echo $textosGenerales; ?>
                                    </textarea>
                                    </div>


                                </div>
                                <div class="row">

                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                    <h3>Condiciones servicio Internet</h3>

                                    <textarea id="froala-editor" class="condicion-internet" name="condiciones[internet]" placeholder="Contrato legal">
                                       <?php echo $textoInternet; ?>
                                    </textarea>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                    <h3>Condiciones servicio Telefonía Fija</h3>

                                    <textarea id="froala-editor" class="condicion-fijo" name="condiciones[fijo]" placeholder="Contrato legal">
                                        <?php echo $textoFijo; ?>
                                    </textarea>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                    <h3>Condiciones servicio Telefonía Móvil</h3>
                                    <textarea id="froala-editor" class="condicion-movil" name="condiciones[movil]" placeholder="Contrato legal">
                                       <?php echo $textoMovil; ?>
                                    </textarea>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                    <h3>Condiciones servicio Televisión</h3>
                                    <textarea id="froala-editor" class="condicion-tv" name="condiciones[tv]" placeholder="Contrato legal">
                                       <?php echo $textoTV; ?>
                                    </textarea>
                                    </div>
                                </div>

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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css">
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.4/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.4/css/froala_style.min.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.4/js/froala_editor.min.js'></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
<script>

    $(function()
    {
        $('textarea#froala-editor').froalaEditor(
            {

            }
        );
    });



</script>



</body>
</html>