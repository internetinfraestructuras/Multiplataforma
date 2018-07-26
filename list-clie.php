    <?php

if (!isset($_SESSION)) {
    @session_start();
}

    /*
        ╔════════════════════════════════════════════════════════════╗
        ║ Interfaz que muestra el listado de clientes      ║
        ╚════════════════════════════════════════════════════════════╝
    */

require_once('config/util.php');
$util = new util();
check_session(2);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> - <?php echo DEF_CLIENTES; ?> /Listados</title>
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
    <link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <!-- JQGRID TABLE -->
    <link href="assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

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
                <li><a href="#"><?php echo DEF_CLIENTES; ?></a></li>
                <li class="active">Listar</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_CLIENTES; ?></strong> <!-- panel title -->
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

                                    <table id="jqgrid"></table>
                                    <div id="pager_jqgrid"></div>

                                    <br />

                                    <a href="javascript:;" class="btn btn-default select_unselect_row">Seleccionar/Deseleccionar por ID</a>
                                    <a href="ficha-cliente.php?idCliente=26";" class="btn btn-default get_selected_ids">Mostrar datos completos</a>
                                    <a href="javascript:;" class="btn btn-default delete_row">Gestionar equipos</a>

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


<!-- PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    loadScript(plugin_path + "jqgrid/js/jquery.jqGrid.js", function(){
        loadScript(plugin_path + "jqgrid/js/i18n/grid.locale-es.js", function(){
            loadScript(plugin_path + "bootstrap.datepicker/js/bootstrap-datepicker.min.js", function(){

                // tira de carga_cli.php y llena un j query grid

                $.ajax({
                    url: 'carga_cli.php',
                    type: 'POST',
                    cache: false,
                    async:true,
                    success: function(jqgrid_data) {
                        // ----------------------------------------------------------------------------------------------------
                        jQuery("#jqgrid").jqGrid({
                            data : jqgrid_data,
                            datatype : "local",
                            height : '400',
                            colNames : ['Dni','Nombre','Apellidos','Dirección','Localdidad','Provincia',
                                'Cod.Post','Teléfono','Teléfono','Email','Notas','Fecha Alta','Última Modificación'],
                            colModel : [
                                { name : 'dni', index : 'dni', editable : true ,sortable:true},
                                { name : 'nombre', index : 'nombre', align : "left", editable : true, sortable:true },
                                { name : 'apellidos', index : 'apellidos', align : "left", editable : true,sortable:true },
                                { name : 'direccion', index : 'direccion', align : "left", editable : true,sortable:true },
                                { name : 'municipio', index : 'municipio', align : "left", editable : false,sortable:true },
                                { name : 'provincia', index : 'provincia', align : "left", editable : false,sortable:true },
                                { name : 'cp', index : 'cp', align : "right", editable : true ,sortable:true},
                                { name : 'tel1', index : 'tel1', align : "right", editable : true ,sortable:true},
                                { name : 'tel2', index : 'tel1', align : "right", editable : true,sortable:true },
                                { name : 'email', index : 'email', align : "left", editable : true,sortable:true },
                                { name : 'notas', index : 'notas', sortable : false, editable : true },
                                { name : 'alta', index : 'alta', sortable : false, editable : false },
                                { name : 'modifica', index : 'modifica', sortable : false, editable : false }],

                        rowNum : 10,
                            rowList : [10, 50, 100],
                            pager : '#pager_jqgrid',
                            sortname : 'empresa',
                            toolbarfilter: true,
                            viewrecords : true,
                            sortorder : "asc",
                            gridComplete: function(){
                                var ids = jQuery("#jqgrid").jqGrid('getDataIDs');

                            },
                            editurl : "php/guardar-cli.php",
                            caption : "Para cambiar el orden, clic en la columna",
                            multiselect : true,
                            autowidth:true, shrinkToFit:false

                        });
                        // ----------------------------------------------------------------------------------------------------

                    }
                });



                //enable datepicker
                function pickDate( cellvalue, options, cell ) {
                    setTimeout(function(){
                        jQuery(cell) .find('input[type=text]')
                            .datepicker({format:'yyyy-mm-dd' , autoclose:true});
                    }, 0);
                }

                jQuery("#jqgrid").jqGrid('navGrid', "#pager_jqgrid", {
                    edit : false,
                    add : false,
                    del : true
                });

                jQuery("#jqgrid").jqGrid('inlineNav', "#pager_jqgrid");

                // Get Selected ID's
                jQuery("a.get_selected_ids").bind("click", function() {
                    s = jQuery("#jqgrid").jqGrid('getGridParam', 'selarrrow');

                });

                // Select/Unselect specific Row by id
                jQuery("a.select_unselect_row").bind("click", function() {
                    jQuery("#jqgrid").jqGrid('setSelection', "13");
                });

                // Select/Unselect specific Row by id
                jQuery("a.delete_row").bind("click", function() {
                    var su=jQuery("#jqgrid").jqGrid('delRowData',1);
                    if(su) alert("HECHO. Escribir código personalizado para eliminar fila del servidor"); else alert("Ya estaba borrado");
                });


                // On Resize
                jQuery(window).resize(function() {

                    if(window.afterResize) {
                        clearTimeout(window.afterResize);
                    }

                    window.afterResize = setTimeout(function() {


                        jQuery("#jqgrid").jqGrid('setGridWidth', jQuery("#middle").width() - 32);

                    }, 500);

                });

                // ----------------------------------------------------------------------------------------------------

                /**
                 @STYLING
                 **/
                jQuery(".ui-jqgrid").removeClass("ui-widget ui-widget-content");
                jQuery(".ui-jqgrid-view").children().removeClass("ui-widget-header ui-state-default");
                jQuery(".ui-jqgrid-labels, .ui-search-toolbar").children().removeClass("ui-state-default ui-th-column ui-th-ltr");
                jQuery(".ui-jqgrid-pager").removeClass("ui-state-default");
                jQuery(".ui-jqgrid").removeClass("ui-widget-content");

                jQuery(".ui-jqgrid-htable").addClass("table table-bordered table-hover");
                jQuery(".ui-pg-div").removeClass().addClass("btn btn-sm btn-primary");
                jQuery(".ui-icon.ui-icon-plus").removeClass().addClass("fa fa-plus");
                jQuery(".ui-icon.ui-icon-pencil").removeClass().addClass("fa fa-pencil");
                jQuery(".ui-icon.ui-icon-trash").removeClass().addClass("fa fa-trash-o");
                jQuery(".ui-icon.ui-icon-search").removeClass().addClass("fa fa-search");
                jQuery(".ui-icon.ui-icon-refresh").removeClass().addClass("fa fa-refresh");
                jQuery(".ui-icon.ui-icon-disk").removeClass().addClass("fa fa-save").parent(".btn-primary").removeClass("btn-primary").addClass("btn-success");
                jQuery(".ui-icon.ui-icon-cancel").removeClass().addClass("fa fa-times").parent(".btn-primary").removeClass("btn-primary").addClass("btn-danger");

                jQuery( ".ui-icon.ui-icon-seek-prev" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
                jQuery(".ui-icon.ui-icon-seek-prev").removeClass().addClass("fa fa-backward");

                jQuery( ".ui-icon.ui-icon-seek-first" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
                jQuery(".ui-icon.ui-icon-seek-first").removeClass().addClass("fa fa-fast-backward");

                jQuery( ".ui-icon.ui-icon-seek-next" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
                jQuery(".ui-icon.ui-icon-seek-next").removeClass().addClass("fa fa-forward");

                jQuery( ".ui-icon.ui-icon-seek-end" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
                jQuery(".ui-icon.ui-icon-seek-end").removeClass().addClass("fa fa-fast-forward");

            });
        });
    });

    $( document ).ready(function() {
        var hash = window.location.hash.substr(1);

    });

</script>



</body>
</html>