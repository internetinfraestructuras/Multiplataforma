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
                <li><a href="#"><?php echo DEF_PAQUETES; ?></a></li>
                <li class="active">Agregar un paquete</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">
            <form class="validate" action="guardar-paquete.php" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="paquetes"/>
            <div class="row">
                <div class="col-lg-12">
                    <label>Nombre del paquete: </label>
                    <input type="text" name="paquete[nombre]"  id="impuestos" onchange="calcularPVP(this.value)" step=".01" class="form-control ">
                </div>
            </div>
            <br/>
            <br/>
            <div class="row">


                <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                    <center>
                        <label><strong>Internet</strong></label>
                    </center>
                    <a href="servicios.php" id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px"><i class="fa fa-edit"></i> Nueva </a>

                    <select id="internet" multiple="multiple" name="paquete[internet]" style="height:260px" class="form-control" onchange="calcularPVP(this)">
                        <?php
                        // carga el listado de cabeceras en el select
                        $cabeceras = $util->selectWhere('servicios', array('id', 'nombre','precio_proveedor'), 'id_empresa='.$_SESSION['REVENDEDOR'].' AND id_servicio_tipo=1', 'nombre');

                        $c = 0;

                        while ($row = mysqli_fetch_array($cabeceras)) {
                            if ($c == 0) {
                                $ultimo = $row;
                                $c = 1;
                            }
                            echo "<option data-coste='".$row['precio_proveedor']."' value='" . $row['id'] . "'>" . $row['nombre']. "</option>";
                        }
                        ?>
                    </select>
                    <a id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="resetearSelect('internet')"><i class="fa fa-recycle"></i> Deseleccionar </a>
                    <label>COSTE SERVICIO: </label>
                    <input type="text"  id="precio-internet" onchange="calcularPVP(this.value)" step=".01" class="form-control ">
                </div>
                <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                    <center>
                        <label><strong>Telefonía FIJA</strong></label>
                    </center>
                    <a href="servicios.php" id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px"><i class="fa fa-edit"></i> Nueva </a>

                    <select id="fijo" multiple="multiple" style="height:260px" class="form-control" name="paquete[fijo]" onchange="calcularPVP(this)" >
                        <?php
                        // carga el listado de cabeceras en el select
                        $cabeceras = $util->selectWhere('servicios', array('id', 'NOMBRE','PRECIO_PROVEEDOR'), 'ID_EMPRESA='.$_SESSION['REVENDEDOR'].' AND ID_SERVICIO_TIPO=2', 'NOMBRE');

                        $c = 0;

                        while ($row = mysqli_fetch_array($cabeceras)) {
                            if ($c == 0) {
                                $ultimo = $row;
                                $c = 1;
                            }
                            echo "<option data-coste='".$row['PRECIO_PROVEEDOR']."' value='" . $row['id'] . "'>" . $row['NOMBRE']. "</option>";
                        }
                        ?>
                    </select>
                    <a  id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="resetearSelect('fijo')"><i class="fa fa-recycle"></i> Deseleccionar </a>
                    <label>COSTE SERVICIO: </label>
                    <input type="text"  id="precio-fijo" onchange="calcularPVP(this.value)" step=".01" class="form-control ">


                </div>
                <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                    <center>
                        <label><strong>Telefonía MOVIL</strong></label>
                    </center>
                    <a href="servicios.php" id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px"><i class="fa fa-edit"></i> Nueva </a>

                    <select id="movil"  multiple="multiple" style="height:260px" class="form-control" name="paquete[movil][]" onchange="calcularPVP(this)">
                        <?php
                        // carga el listado de cabeceras en el select
                        $cabeceras = $util->selectWhere('servicios', array('id', 'NOMBRE','PRECIO_PROVEEDOR'), 'ID_EMPRESA='.$_SESSION['REVENDEDOR'].' AND ID_SERVICIO_TIPO=3', 'NOMBRE');

                        $c = 0;

                        while ($row = mysqli_fetch_array($cabeceras)) {
                            if ($c == 0) {
                                $ultimo = $row;
                                $c = 1;
                            }
                            echo "<option data-coste='".$row['PRECIO_PROVEEDOR']."' value='" . $row['id'] . "'>" . $row['NOMBRE']. "</option>";
                        }
                        ?>
                    </select>
                    <a  id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="anadirProducto('movil')"><i class="fa fa-recycle"></i> Deseleccionar </a>
                    <label>COSTE SERVICIO: </label>
                    <input type="text"  id="precio-movil" onchange="calcularPVP(this.value)" step=".01" class="form-control ">

                </div>
                <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                    <center>
                        <label><strong>TELEVISIÓN</strong></label>
                    </center>
                    <a href="servicios.php" id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px"><i class="fa fa-edit"></i> Nueva </a>

                    <select id="tv" multiple="multiple"  style="height:260px" class="form-control" name="paquete[tv]" onchange="calcularPVP(this)">
                        <?php
                        // carga el listado de cabeceras en el select
                        $cabeceras = $util->selectWhere('servicios', array('id', 'NOMBRE','PRECIO_PROVEEDOR'), 'ID_EMPRESA='.$_SESSION['REVENDEDOR'].' AND ID_SERVICIO_TIPO=4', 'NOMBRE');

                        $c = 0;

                        while ($row = mysqli_fetch_array($cabeceras)) {
                            if ($c == 0) {
                                $ultimo = $row;
                                $c = 1;
                            }
                            echo "<option data-coste='".$row['PRECIO_PROVEEDOR']."' value='" . $row['id'] . "'>" . $row['NOMBRE']. "</option>";
                        }
                        ?>
                    </select>
                    <a id="" class="btn btn-3d btn-teal" style="width:100%;margin-bottom:15px" onclick="resetearSelect('tv')"><i class="fa fa-recycle"></i> Deseleccionar </a>
                    <label>COSTE SERVICIO: </label>
                    <input type="text"  id="precio-tv" onchange="calcularPVP(this.value)" step=".01" class="form-control ">
                </div>

            </div>
            <hr/>
                <h2>Contenido Paquete:</h2>
                <table id="contenido-paquete" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID SERVICIO</th>
                        <th>NOMBRE</th>
                        <th>PRECIO COSTE</th>
                        <th>IMPUESTOS</th>
                        <th>MARGEN</th>
                        <th>PVP</th>
                    </tr>
                    </thead>

                    </tbody>

                </table>
            <h2>Precios del paquete:</h2>
            <div class="row">
                <div class="col-lg-3">
                    <label>Precio coste: </label>
                    <input type="text" name="paquete[precio-coste]"  id="precio-coste"  step=".01" class="form-control" onchange="calcularPrecioFinal(this.value)">
                </div>
                <div class="col-lg-3">
                    <label>Margen %: </label>
                    <input type="text" name="paquete[precio-beneficio]"  id="precio-margen"  step=".01" class="form-control " onchange="calcularPrecioFinal(this.value)">
                </div>
                <div class="col-lg-3">
                    <label>Impuestos : </label>
                    <input type="text" name="paquete[precio-impuestos]"  id="precio-impuestos"  step=".01" class="form-control " value="21" onchange="calcularPrecioFinal(this.value)">
                </div>
                <div class="col-lg-3">
                    <label>PVP: </label>
                    <input type="text" name="paquete[precio-pvp]"  id="precio-pvp"  step=".01" class="form-control" onchange="calcularMargen(this)">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <center>

                            <button type="submit" class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">CREAR PAQUETE</button>
                    </center>
                </div>
            </div>
            </form>


            <div class="row">

                <div class="col-md-12">

                    <!-- ------ -->
                    <div class="panel panel-default">

                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
							<span class="title elipsis">
								<strong>LISTADO DE <?php echo DEF_SERVICIOS; ?></strong> <!-- panel title -->
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

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PAQUETE</th>
                                            <th>PVP</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $listado= $util->selectWhere3('paquetes',
                                            array("ID","NOMBRE","PVP"),
                                            "paquetes.id_empresa=".$_SESSION['REVENDEDOR']);


                                        for($i=0;$i<count($listado);$i++)
                                        {

                                            $id=$listado[$i][0];
                                            $nombre=$listado[$i][1];
                                            $pvp=$listado[$i][2];


                                            echo "<tr>";
                                            echo "<td>$id</td><td>$nombre</td><td>$pvp</td>";

                                            ?>
                                            <td class="td-actions text-right">
                                                <a href="ficha-paquete.php?idPaquete=<?php echo $id; ?>">
                                                    <button type="button" rel="tooltip" >
                                                        <i class="fa fa-edit"></i>
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

    function anadirProducto(id)
    {
        var val=jQuery("#"+id).val("");
            console.log(val);
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