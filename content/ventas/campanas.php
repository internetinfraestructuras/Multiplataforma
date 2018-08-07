<?php
// todo: -------------------------------------------------------------
// funcion que muestra la ficha para poder agregar clientes al sistema
// estos clientes se asocian al revendedor al que esta asociado el usuario que lo crea
// todo: -------------------------------------------------------------



if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();

// solo los usuarios de nivel 3 a 0 pueden agregar clientes
check_session(3);
$root="../../";
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
                <li><a href="#"><?php echo DEF_CAMPANA;?></a></li>
                <li class="active">Agregar una campaña</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">
            <form class="validate" action="crear-campana.php" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="campanas"/>
                <div class="row">

                    <div class="col-lg-12">
                        <label>Nombre de la campaña: </label>
                        <input type="text" name="campana[nombre]"  id="nombre" class="form-control ">
                    </div>
                </div>
                <div class="row">

                <div class="col-md-6 col-sm-4">
                    <label>Fecha Inicio Activación:</label>
                    <input type="text" name="campana[inicio]" value="" class="form-control datepicker required" data-format="yyyy-mm-dd" data-lang="es" data-RTL="false">

                </div>
                <div class="col-md-6 col-sm-4">
                    <label>Fecha Fin activación:</label>
                    <input type="text" name="campana[fin]" value="" class="form-control datepicker required" data-format="yyyy-mm-dd" data-lang="es" data-RTL="false">
                </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-4">
                        <label>Duración (días):</label>
                        <input type="text" name="campana[duracion]" value="" class="form-control datepicker required" data-format="yyyy-mm-dd" data-lang="es" data-RTL="false">

                    </div>
                    <div class="col-md-6 col-sm-4">
                        <label>Descuento:</label>
                        <input type="number" name="campana[descuento]" value="" class="form-control required"  data-lang="es" data-RTL="false" step="0.1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <center>

                            <button type="submit" class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">CREAR CAMPAÑA</button>
                        </center>
                    </div>
                </div>


                <hr/>
            </form>


            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_CAMPANA; ?> ACTIVAS</strong> <!-- panel title -->
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
                                            <th>NOMBRE</th>
                                            <th>INICIO</th>
                                            <th>FIN</th>
                                            <th>DESCUENTO</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $listado= $util->selectWhere3('campanas',
                                            array("ID","NOMBRE","FECHA_INICIO","FECHA_FIN","DESCUENTO"),
                                            "campanas.id_empresa=".$_SESSION['REVENDEDOR']);


                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i][0];
                                            $nombre=$listado[$i][1];
                                            $inicio=$listado[$i][2];
                                            $fin=$listado[$i][3];
                                            $desc=$listado[$i][4];


                                            echo "<tr>";
                                            echo "<td>$id</td><td>$nombre</td><td>$inicio</td><td>$fin</td><td>$desc</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-paquete.php?idPaquete=<?php echo $id; ?>">
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

<!-- JAVASCRIPT FILES-->


<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>



<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../../assets/js/app.js"></script>

<script>

    // carga los modelos al combo correspondiente

    function carga_modelos(id)
    {
        var select = jQuery("#modelos");
        select.empty();
        select.append('<option value="">--- Seleccionar una ---</option>');
        jQuery.ajax({
            url: 'carga_modelos.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {
                $.each(data, function(i){
                    select.append('<option value="'+data[i].id+'">'+data[i].nombre+'</option>');
                });
            }
        });
    }
    function calcularPrecioFinal(val)
    {
        var precioProv=jQuery("#precio-coste").val();
        var pvp=((precioProv*val)/100);
        pvp=(parseFloat(precioProv)+parseFloat(pvp));

        var tax=parseFloat(jQuery("#precio-impuestos").val());

        pvp=(pvp*parseFloat((100+tax)/100));


        jQuery("#precio-pvp").val(redondearDecimales(pvp,2));
    }
    function resetearSelect(id)
    {

        jQuery("#"+id).val("");
        jQuery("#precio-"+id).val("");
        sumarTotales();
    }

    function calcularMargen(campo)
    {
        var pvp=jQuery(campo).val();
        var coste=jQuery("#precio-coste").val();
        var diferencia=pvp-coste;



    }
    function calcularPVP(val)
    {

        var precioCoste=parseFloat(jQuery(val).find(':selected').data("coste"));

        var nombreCampo="#precio-"+jQuery(val).attr("id");
        var val=jQuery(nombreCampo).val();

        //Si el campo es de línea moviles es multiselección y se puede acumular los precios
        if(val!=0 && nombreCampo=="#precio-movil")
            precioCoste+=parseFloat(val);

        jQuery(nombreCampo).val(precioCoste);
        sumarTotales();

    }
    function sumarTotales()
    {
        var internet=jQuery("#precio-internet").val();
        if(internet=="")
            internet=0;
        var fijo=jQuery("#precio-fijo").val();
        if(fijo=="")
            fijo=0;

        var movil=jQuery("#precio-movil").val();
        if(movil=="")
            movil=0;
        var tv=jQuery("#precio-tv").val();
        if(tv=="")
            tv=0;

        var costeTotal=parseFloat(internet)+parseFloat(fijo)+parseFloat(movil)+parseFloat(tv);
        jQuery("#precio-coste").val(costeTotal);
    }
    function redondearDecimales(numero, decimales) {
        numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}');   // Expresion regular para numeros con un cierto numero de decimales o mas
        if (numeroRegexp.test(numero)) {         // Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
            return Number(numero.toFixed(decimales));
        } else {
            return Number(numero.toFixed(decimales)) === 0 ? 0 : numero;  // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
        }
    }

    function borrar(id)
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
    function confirmar(text){

        return confirm(text);

    }
    /*
    jQuery(document).ready(function(){

        jQuery('select#movil[multiple] option').mousedown(function(){
            if ($(this).attr('selected')) $(this).removeAttr('selected');
            else $(this).attr('selected','selected');
            return false;
        });

    });*/
</script>



</body>
</html>