<?php
/*
    ╔════════════════════════════════════════════════════════════╗
    ║ Interfaz que permite editar la ficha de servicios ║
    ╚════════════════════════════════════════════════════════════╝
*/
if (!isset($_SESSION)) {
    @session_start();
}
require_once('../../config/util.php');
$util = new util();
check_session(2);

if(!isset($_GET['idContrato']))
{
$listado= $util->selectWhere3('servicios,servicios_tipos',
    array("servicios.id","servicios.nombre","servicios.pvp","servicios.precio_proveedor","servicios.impuesto","servicios.beneficio","servicios.id_servicio_tipo"),
    "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND servicios.id=".$_GET['idServicio']);
}
else
{
    $listado= $util->selectWhere3('servicios,servicios_tipos,contratos,contratos_lineas',
        array("servicios.id","servicios.nombre","contratos_lineas.pvp","contratos_lineas.precio_proveedor","contratos_lineas.impuesto","contratos_lineas.beneficio","servicios.id_servicio_tipo","contratos_lineas.permanencia","contratos_lineas.estado"),
        "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND contratos.id=".$_GET['idContrato']."
                                                      AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
                                                      AND contratos.id=contratos_lineas.id_contrato AND servicios.id=".$_GET['idServicio']." AND contratos_lineas.id=".$_GET['idLineaContrato']);
}

$id=$listado[0][0];
$nombre=$listado[0][1];
$pvp=$listado[0][2];
$coste=$listado[0][3];
$impuesto=$listado[0][4];
$beneficio=$listado[0][5];
$idTipoServicio=$listado[0][6];
$permanencia=$listado[0][7];
$idEstado=$listado[0][8];

$readonly="";

$actual = date ("Y-m-d");




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
                <li><a href="#"><?php echo DEF_SERVICIOS; ?></a></li>
                <li class="active">Ficha de Servicios de cliente</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-8">

                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR <?php echo strtoupper(DEF_SERVICIOS); ?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-servicio.php" method="post"
                                  enctype="multipart/form-data">
                                <fieldset>
                                    <!-- required [php action request] -->
                                    <input type="hidden" name="oper" value="edit"/>
                                    <input type="hidden" name="hash" id="hash" value=""/>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-2">

                                                <label>ID</label>
                                                <?php

                                                if(isset($_GET['idContrato']))
                                                {
                                                    echo '<input type="hidden" name="idContrato" value='.$_GET['idContrato'].' id="id" class="form-control">';
                                                    echo '<input type="hidden" name="idLinea" value='.$_GET['idLineaContrato'].' id="id" class="form-control">';
                                                }

                                                ?>
                                                <input type="text" name="id" value="<?php echo $id;?>"  class="form-control disabled" readonly>

                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Tipo de Servicio:</label>
                                                <select name="tipo" id="tipo" readonly
                                                        class="form-control pointer "  onchange="carga_tipos(this.value)">
                                                    <option value="<?php echo $idTipoServicio;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios_tipos', 'id', 'nombre', 'nombre','','','',$idTipoServicio); ?>
                                                </select>
                                            </div>

                                                <?php
                                                if(isset($_GET['idContrato']))
                                                {?>

                                                <div class="col-md-4 col-sm-5">
                                                    <label>Nombre:</label>
                                                    <select name="servicio" id="servicio"
                                                            class="form-control pointer " name="nombre"  onchange="carga_datos_servicio(this.value)">
                                                        <option>--- Seleccionar una ---</option>
                                                        <?php $util->carga_select('servicios', 'id', 'nombre', 'nombre','servicios.id_servicio_tipo='.$idTipoServicio,'','',$id); ?>
                                                    </select>
                                                 </div>
                                                    <div class="col-md-3 col-sm-5">
                                                        <label>Permanencia:</label>
                                                        <input type="date" name="permanencia" value="<?php echo $permanencia;?>"  class="form-control disabled" readonly>

                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                else
                                                {?>
                                                    <div class="col-md-7 col-sm-5">
                                                        <label>Nombre:</label>
                                                        <input type="text" name="nombre" id="servicio" value="<?php echo $nombre; ?>"   class="form-control" >
                                                    </div>


                                                <?php }
                                                ?>
</div>



                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-4">
                                                <label>Precio Proveedor</label>
                                                <input type="text" name="coste" id="coste"
                                                       class="form-control " placeholder="0.00"  value=<?php echo $coste; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Margen</label>
                                                <input type="text" name="beneficio" id="beneficio"
                                                "  class="form-control  placeholder="0" value=<?php echo $beneficio; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>IMPUESTOS</label>
                                                <input type="text" name="impuesto" id="impuestos"
                                                       class="form-control " placeholder="0" value=<?php echo $impuesto; ?>>
                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>PVP</label>
                                                <input type="text" name="pvp" id="pvp"
                                                       class="form-control " placeholder="0" value=<?php echo $pvp; ?>>
                                            </div>
                                        </div>
                                    </div>

                                    </div>


                                </fieldset>

                                <hr/>
                                <div class="panel-body">

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ATRIBUTO</th>
                                            <th>VALOR</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!isset($_GET['idContrato']))
                                        {
                                            $atributos= $util->selectWhere3('servicios_atributos,servicios_tipos_atributos',
                                                array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                                "servicios_atributos.id_servicio=".$_GET['idServicio']." and servicios_atributos.id_tipo_atributo=servicios_tipos_atributos.id");
                                        }
                                        else
                                        {

                                            $atributos= $util->selectWhere3('contratos_lineas,contratos_lineas_detalles,servicios_tipos_atributos',
                                                array("servicios_tipos_atributos.id,servicios_tipos_atributos.nombre,contratos_lineas_detalles.valor"),
                                                "servicios_tipos_atributos.id=contratos_lineas_detalles.id_atributo_servicio AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']
                                            ." AND contratos_lineas.id=contratos_lineas_detalles.id_linea");
                                        }


                                        for($i=0;$i<count($atributos);$i++)
                                        {

                                            $id=$atributos[$i][0];
                                            $attr=$atributos[$i][1];

                                            if(isset($_GET['idContrato']))
                                                $valor=$atributos[$i][2];
                                            else
                                                $valor=$atributos[$i][3];




                                            echo "<tr>";

                                            echo "<tr>";
                                            echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$attr' class='form-control' disabled />
                                            </td><td><input name='atributo[valor][]' value='$valor' class='form-control' /></td>";

                                            ?>

                                            </tr>



                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>

                                    </table>

                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-4">

                                            <input type="checkbox" name="cascada-precio"  placeholder="0" > ¿Realizar una actualización de precios a todos los clientes con dicho servicio contratado?   El precio no incluye cambios en los precios de los paquetes.


                                    </div>
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





    </section>
    <!-- /MIDDLE -->

</div>


<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>

<!--<script type="text/javascript" src="../../assets/js/app.js"></script>-->


<script>
    // cargo las provincias por Ajax, cada vez que se cambia la comunidad
    function carga_datos_servicio(id)
    {


        jQuery.ajax({
            url: 'carga_servicios.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {

                id=data[0].idservicio;
                tipo=data[0].id_tipo;
                coste=data[0].coste;
                impuesto=data[0].impuesto;
                beneficio=data[0].beneficio;
                pvp=data[0].pvp;
                tipo=data[0].tipo;

                $("#coste").val(coste);
                $("#margen").val(beneficio);
                $("#impuestos").val(impuesto);
                $("#pvp").val(pvp);



            },
            error:function (xhr,error)
            {
            console.log(error);
            }
        });
    }

    function cargarProductos()
    {

        var array=new Array(new Array());

        $(".check-productos").each(function(i)
        {

           var valor=$(this).prop('checked');
            array[i][0]=$(this).val();

           if (valor===true)
               array[i][1]='on';
           else
               array[i][1]='off';


        });

        return array;
    }
    function bajaServicio()
    {
        $("#row-baja").css("display","initial");


    }
    function borrar(id)
    {
        // var hash = md5(id);
        var respuesta = confirmar("¿Seguro/a de querer dar de baja al servicio seleccionado?");
        if(respuesta)
            var respuesta2 = confirmar("¿Seguro/a de querer dar de baja al servicio seleccionado?");
        if(respuesta2)
        {

            var productos=cargarProductos();
            console.log(productos);


            var fecha=$("#fecha-baja").val();

            alert(fecha);

            jQuery.ajax({
                url: 'baja-servicio.php',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    a: 'baja_servicio',
                    id:id,
                    idContrato:<?php echo $_GET['idContrato']; ?>,
                    idLinea:<?php echo $_GET['idLineaContrato']; ?>,
                    idServicio:<?php echo $_GET['idServicio']; ?>,

                    fecha:fecha,
                    productos:productos
                },
                success: function (data) {
                console.log(data);
                   // location.reload();
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