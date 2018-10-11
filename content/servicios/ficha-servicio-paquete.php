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
require_once ('../../clases/Producto.php');
require_once ('../../clases/Contrato.php');

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
        array("servicios.id","servicios.nombre","contratos_lineas.pvp","contratos_lineas.precio_proveedor","contratos_lineas.impuesto","contratos_lineas.beneficio","servicios.id_servicio_tipo","contratos_lineas.permanencia","contratos_lineas.estado","servicios.id_proveedor"),
        "servicios.id_empresa=".$_SESSION['REVENDEDOR']."
                                                     AND servicios.id_servicio_tipo=servicios_tipos.id AND contratos.id=".$_GET['idContrato']."
                                                      AND contratos.id_empresa=".$_SESSION['REVENDEDOR']." 
                                                      AND contratos.id=contratos_lineas.id_contrato AND servicios.id=".$_GET['idServicio']." AND contratos_lineas.id=".$_GET['idLineaContrato']."");
}

$id=$listado[0][0];
$nombre=$listado[0][1];
$pvp=$listado[0][2];
$coste=$listado[0][3];
$impuesto=$listado[0][4];
$beneficio=$listado[0][5];
$idTipoServicio=$listado[0][6];
$permanencia=$listado[0][7];
$estado=$listado[0][8];
$idProveedor=$listado[0][9];
$readonly="";





$actual = date ("Y-m-d");



?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo OWNER; ?> <?php echo DEF_PAQUETES; ?> /Servicios Clientes</title>
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
                <li><a href="#"><?php echo DEF_PAQUETES; ?>   /   <?php echo DEF_SERVICIOS; ?></a></li>
                <li class="active"> Cliente</li>
            </ol>
        </header>
        <!-- /page title -->


        <div id="content" class="padding-20">

            <div class="row">

                <div class="col-md-7">

                    <!-- ------ -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-transparent">
                            <strong>EDITAR<?php echo strtoupper(DEF_SERVICIOS);?></strong>
                        </div>

                        <div class="panel-body">

                            <form class="validate" action="guardar-servicio-paquete.php" method="post"
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
                                                    echo '<input type="hidden" name="idLineaDetalle" value='.$_GET['idLineaDetalle'].' id="id" class="form-control">';
                                                }

                                                ?>
                                                <input type="text" name="id" value="<?php echo $id;?>"  class="form-control disabled" readonly>

                                            </div>
                                            <div class="col-md-3 col-sm-4">
                                                <label>Tipo de Servicio:</label>
                                                <select name="tipo" id="tipo" readonly
                                                        class="form-control pointer "  >
                                                    <option value="<?php echo $idTipoServicio;?>">--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios_tipos', 'id', 'nombre', 'nombre','','','',$idTipoServicio); ?>
                                                </select>
                                            </div>


                                            <div class="col-md-4 col-sm-5">
                                                <label>Nombre:</label>
                                                <select name="servicio" id="servicio"
                                                        class="form-control pointer " name="nombre"  onchange="carga_detalles_servicio(this.value);">
                                                    <option>--- Seleccionar una ---</option>
                                                    <?php $util->carga_select('servicios', 'id', 'nombre', 'nombre','servicios.id_servicio_tipo='.$idTipoServicio,'','',$id); ?>
                                                </select>
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
                                $msidn="";

                               $numero= $util->selectWhere3('servicios_tipos_atributos',
                                    array("count(id)"),
                                    "servicios_tipos_atributos.id_servicio=$idTipoServicio AND servicios_tipos_atributos.id_tipo=2");
                                $numero=$numero[0][0];



                                $idLineaDetalle=$_GET['idLineaDetalle'];
                                /*
                                $atributos= $util->selectWhere3('contratos_lineas,contratos_lineas_detalles,servicios_tipos_atributos',
                                    array("distinct(servicios_tipos_atributos.id),servicios_tipos_atributos.nombre,contratos_lineas_detalles.valor"),
                                    "servicios_tipos_atributos.id=contratos_lineas_detalles.id_atributo_servicio AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']
                                    ." AND contratos_lineas.id=contratos_lineas_detalles.id_linea AND contratos_lineas_detalles.id_servicio=".$_GET['idServicio']."
                                        AND contratos_lineas_detalles.estado!=5 ");*/

                                $numeroMax=$idLineaDetalle+($numero-1);

                                $atributos= $util->selectWhere3('contratos_lineas,contratos_lineas_detalles,servicios_tipos_atributos',
                                        array("distinct(servicios_tipos_atributos.id),servicios_tipos_atributos.nombre,contratos_lineas_detalles.valor"),
                                        "servicios_tipos_atributos.id=contratos_lineas_detalles.id_atributo_servicio AND contratos_lineas_detalles.id_linea=".$_GET['idLineaContrato']
                                        ." AND contratos_lineas.id=contratos_lineas_detalles.id_linea AND contratos_lineas_detalles.id_servicio=".$_GET['idServicio']."
                                        AND contratos_lineas_detalles.estado!=5 AND contratos_lineas_detalles.id>=$idLineaDetalle AND contratos_lineas_detalles.id<=$numeroMax");

                                for($i=0;$i<$numero;$i++)
                                {

                                    $id=$atributos[$i][0];
                                    $attr=$atributos[$i][1];

                                    if(isset($_GET['idContrato']))
                                        $valor=$atributos[$i][2];
                                    else
                                        $valor=$atributos[$i][3];

                                    if($id==ID_NUMERO_MOVIL)
                                        $msidn=$valor;



                                    echo "<tr>";

                                    echo "<tr>";
                                    echo "<td><input name='atributo[id][]' value='$id' class='form-control' type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$attr' class='form-control' disabled />
                                            </td><td><input name='atributo[valor][]' value='$valor' class='form-control' id='atributo-$id' /></td>";

                                    ?>

                                    </tr>



                                    </tr>

                                    <?php
                                }

                                $estadoLinea="";
                                if($idProveedor==ID_PROVEEDOR_MASMOVIL )
                                {
                                    require_once ('../../clases/masmovil/MasMovilAPI.php');

                                    $res=Contrato::getClienteDatos($_GET['idContrato'],$_SESSION['REVENDEDOR']);

                                    $apiMasMovil=new MasMovilAPI();

                                    $resultado=$apiMasMovil->getListadoClientes($res[0][3],$msidn);

                                    $refClienteAPI=$resultado->Client[0]->refCustomerId;
                                    $resultado=$apiMasMovil->getLineasMsisdnsIccids($refClienteAPI,$msidn,"","");

                                    $iccid=$resultado->msisdnList->Msisdn->Iccid;
                                    $tipoSim=$resultado->msisdnList->Msisdn->typeIccid;

                                    $puk=$resultado->msisdnList->Msisdn->Puk;

                                    $estadoLinea=$resultado->msisdnList->Msisdn->status;

                                    $riesgos=$apiMasMovil->getPeticionRiesgo($refClienteAPI,$msidn,"");

                                /*    $establecerRiesgo=$apiMasMovil->setModificarRiesgo($refClienteAPI,$msidn,"L","50");
                                    var_dump($establecerRiesgo);*/

                                }
                                else if($idProveedor==ID_PROVEEDOR_AIRENETWORKS)
                                {

                                    require_once ('../../clases/airenetwork/clases/Linea.php');
                                    require_once('../../clases/Empresa.php');

                                    $confAire=Empresa::getConfiguracionAireNetworks($_SESSION['REVENDEDOR']);

                                    $lineaAire=new Linea($confAire[0][3],$confAire[0][1],$confAire[0][2]);


                                    $r=$lineaAire->getLineaNumero($msidn);



                                    $impagosArray=$r[0]['historico_estado_impago'];

                                        if($impagosArray!=NULL)
                                        {
                                            $estadoImpago=$r[0]['historico_estado_impago'][0]['estado_impago'];
                                        }




                                    if($r!=NULL)
                                      $estadoLinea=$r[0]['activo'];

                                    if($r[0]['consumo_maximo'])
                                    {
                                        $consumoMaximo=$r[0]['consumo_maximo'];
                                    }

                                    $alertaFacturacion=$r[0]['alerta_facturacion'];


                                    $puk1=$r[0]['sims'][0]['puk'];
                                    $puk2=$r[0]['sims'][0]['puk2'];
                                    $pin2=$r[0]['sims'][0]['pin2'];

                                }
                                //SI ES DE TIPO MOVIL Y DE MASMOVIL SE PUEDEN MOSTRAR DATOS DE LA TARJETA COMO PUK Y DEMAS
                                if($_GET['tipo']==3 && $idProveedor==ID_PROVEEDOR_MASMOVIL )
                                {
                                echo "<tr>";
                                echo "<td colspan='2'><input  value='CODIGO PUK' class='form-control' disabled />";
                                echo "<td><input  value='$puk' class='form-control' disabled />";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td colspan='2'><input  value='ICC' class='form-control' disabled />";
                                echo "<td><input  value='$iccid' class='form-control' disabled />";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td colspan='2'><input  value='Tipo Tarjeta' class='form-control' disabled />";
                                echo "<td><input  value='$tipoSim' class='form-control' disabled />";
                                echo "</tr>";

                                }
                                if($_GET['tipo']==3 && $idProveedor==ID_PROVEEDOR_AIRENETWORKS)
                                {
                                    echo "<tr>";
                                    echo "<td colspan='2'><input  value='CODIGO PUK' class='form-control' disabled />";
                                    echo "<td><input  value='$puk1' class='form-control' disabled />";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<td colspan='2'><input  value='CODIGO PUK 2' class='form-control' disabled />";
                                    echo "<td><input  value='$puk2' class='form-control' disabled />";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<td colspan='2'><input  value='PIN' class='form-control' disabled />";
                                    echo "<td><input  value='$pin2' class='form-control' disabled />";
                                    echo "</tr>";

                                }?>



                                </tbody>

                            </table>

                            <label>¿Qué día será efectivo el cambio?: </label>
                            <input type="date" name="fecha-baja" id="fecha-cambio" value="<?php echo date('Y-m-d'); ?>"><br>
                            <input type="checkbox" name="romper-paquete"  placeholder="0" > Deseo romper el paquete y cobrar al cliente por los servicios independientes.
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-4">
                                <?php
                                if(!isset($_GET['idContrato'])) {
                                    echo '<input type="checkbox" name="cascada-precio"  placeholder="0" > ¿Realizar una actualización de precios a todos los clientes con dicho servicio contratado?   El precio no incluye cambios en los precios de los paquetes.';
                                    //   echo '<input type="checkbox" name="cascada-tecnico"  placeholder="0" > ¿Realizar una actualización de la velocidad para todos los clientes?Si no se selecciona se respetará la velocidad a todos los clientes con este servicio contratado.';
                                }
                                ?>

                            </div>

                        </div>
                    <?php if($estado==CONTRATO_ALTA)
                    {?>


                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit"
                                        class="btn btn-3d btn-teal btn-xlg btn-block margin-top-30">
                                    VALIDAR Y GUARDAR
                                    <span class="block font-lato">verifique que toda la información es correcta</span>
                                </button>
                            </div>
                        </div>
                    <?php
                        }
                        if($estado==4 || $estado==6 || $estado==7 || $estado==8)
                        {?>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit"
                                        class="btn btn-3d btn-teal btn-yellow btn-xlg btn-block margin-top-30">
                                    CANCELAR
                                    <span class="block font-lato">verifique que toda la información es correcta</span>
                                </button>
                            </div>
                        </div>
                        <?php
                        }?>

                        </form>

                    </div>

                </div>

                <div class="col-md-5">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Productos asociados al servicio</h4>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="20%">ID PRODUCTO</th>
                                    <th width="40%">NÚMERO DE SERIE</th>
                                    <th width="20%">ESTADO</th>
                                    <th width="20%" class="text-center">OPCIONES</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($_GET['idContrato']))
                                {
                                    $atributos= $util->selectWhere3('contratos_lineas_productos,productos,contratos,contratos_lineas',
                                        array("servicios_atributos.id,servicios_tipos_atributos.nombre,servicios_tipos_atributos.id,servicios_atributos.valor"),
                                        "contratos_lineas_productos.id_producto=productos.id AND contratos.id=contratos_lineas.id_contrato AND contratos_lineas.id=".$_GET['idLineaContrato']);
                                }
                                else
                                {

                                    $atributos= $util->selectWhere3('contratos,contratos_lineas,contratos_lineas_detalles,contratos_lineas_productos,productos,productos_estados',
                                        array("productos.id,productos.numero_serie,productos_estados.nombre"),
                                        "contratos.id=contratos_lineas.id_contrato AND productos.estado=productos_estados.id 
                                                AND contratos_lineas.id=contratos_lineas_detalles.id_linea
                                                AND contratos_lineas_detalles.id=contratos_lineas_productos.id_linea
                                                AND contratos_lineas_productos.id_producto=productos.id 
                                                AND contratos_lineas_productos.id_linea=".$_GET['idLineaDetalle']." 
                                                AND contratos_lineas_detalles.id_tipo_servicio=".$_GET['tipo']);
                                }


                                for($i=0;$i<count($atributos);$i++)
                                {

                                    $id=$atributos[$i][0];
                                    $ssid=$atributos[$i][1];
                                    $estado=$atributos[$i][2];
                                    if(isset($_GET['idContrato']))
                                        $valor=$atributos[$i][2];
                                    else
                                        $valor=$atributos[$i][3];




                                    echo "<tr>";

                                    echo "<tr>";

                                    echo "<td><input name='atributo[id][]' value='$id' class='form-control'  type='hidden' />
                                            <input name='atributo[id][]' value='$id' class='form-control'  disabled/></td>
                                            <td><input  value='$ssid' class='form-control' disabled />
                                            <td><input  value='$estado' class='form-control' disabled />
                                            </td>";

                                    ?>
                                    <td class="text-center">
                                        <a href="../almacen/ficha-producto.php?idProducto=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip" class="em2">
                                                <i class="fa fa-eye em2"></i>
                                            </button>
                                        </a>
                                        <a onclick="abrirModal(<?php echo $id;?>)">
                                            <button type="button" rel="tooltip"  class="em2">
                                                <i class="fa fa-recycle em2"></i>
                                            </button>
                                        </a>
                                        <a href="../almacen/ficha-producto.php?idProducto=<?php echo $id; ?>">
                                            <button type="button" rel="tooltip"  class="em2">
                                                <i class="fa fa-trash em2"></i>
                                            </button>
                                        </a>
                                    </td>
                                    </tr>



                                    </tr>

                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>

                        </div>
                    </div>
                <?php

                if($idTipoServicio==3 && $idProveedor==ID_PROVEEDOR_MASMOVIL)
                {
                    ?>


                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2>Operaciones sobre la línea</h2>
                            <hr>

                            <?php if($estadoLinea==LINEA_ACTIVA_MASMOVIL)
                            {

                                ?>
                                <a href="javascript:;" onclick="establecerRoaming('A')"
                                   class="btn btn-info btn-xs">ACTIVAR ROAMING <i class="fas fa-globe-asia"></i></a></br>
                                <br/>
                                <a href="javascript:;" onclick="establecerRoaming('D')"
                                   class="btn btn-info btn-xs">DESACTIVAR ROAMING <i class="fas fa-globe-asia"></i></a></br>
                                <br/>
                            <a href="javascript:;" onclick="estadosLineas('S')"
                               class="btn btn-danger btn-xs">SUSPENSION TEMPORAL LINEA <i
                                        class="fas fa-phone"></i></a></br><br/>
                            <?php
                            }?>
                            <?php if($estadoLinea==LINEA_SUSPENDIDA_MASMOVIL)
                            {?>
                            <a href="javascript:;" onclick="estadosLineas('A')"
                               class="btn btn-danger btn-xs">REACTIVACIÓN DE LÍNEA <i class="fas fa-phone"></i></a></br>
                            <br/>
                                <?php
                            }?>

                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">SOLICITAR AMPLIACION RIESGOS  <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">CAMBIO RIESGO MENSUAL  <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">BLOQUEAR SI SUPERA RIESGO MENSUAL  <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">NO BLOQUEAR SI SUPERA RIESGO MENSUAL <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">DESBLOQUEO AUTOMÁTICO DIA 1<i class="fas fa-phone"></i></a></br>
                            <br/>
                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-danger btn-xs">NO DESBLOQUEO AUTOMÁTICO DIA 1<i class="fas fa-phone"></i></a></br>
                            <br/>

                            <a href="../servicios/cdr-actual.php?numero=<?php echo $msidn;?>"
                               class="btn btn-blue btn-xs">CONSULTAR CDR   <i class="fas fa-phone"></i></a></br>
                            <br/>
                        </div>
                    </div>
                    <?php
                    }

                    if($idTipoServicio==3 && $idProveedor==ID_PROVEEDOR_AIRENETWORKS)
                    {
                    ?>


                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2>Operaciones sobre la línea</h2>
                            <hr>

                            <?php

                            if($estadoLinea=="SI" && $estadoImpago=='CAN' ||$estadoImpago==NULL)
                            {
                            ?>
                            <a href="javascript:;" onclick="corteImpago('S')"
                               class="btn btn-danger btn-xs">SOLICITAR CORTE IMPAGO  <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <?php
                            }
                            if($estadoLinea=="SI" && $estadoImpago=='SOL') {
                                ?>
                                <a href="javascript:;" onclick="corteImpago('C')"
                                   class="btn btn-warning btn-xs">CANCELAR SOLICITUD DE CORTE <i
                                            class="fas fa-phone"></i></a></br>
                                <br/>
                                <?php
                            }
                            if($estadoImpago=='COR') {
                            ?>

                            <a href="javascript:;" onclick="corteImpago('R')"
                               class="btn btn-warning btn-xs">REESTABLECER CORTE POR IMPAGO  <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <?php
                            }
                            if(isset($consumoMaximo))
                            {?>
                                <a href="javascript:;" onclick="establecerConsumo('<?php echo $consumoMaximo; ?>')"
                                   class="btn btn-warning btn-xs">MODIFICAR CONSUMO MÁXIMO: <?php echo $consumoMaximo; ?> &euro; <i class="fas fa-phone"></i></a>
                                <a href="javascript:;" onclick="establecerConsumo('0')"
                                   class="btn btn-danger btn-xs">ELIMINAR CONSUMO MAXIMO <i class="fas fa-phone"></i></a></br>
                                <br/>
                            <?php }
                            else
                            {
                                ?>
                                <a href="javascript:;" onclick="establecerConsumo(1)"
                               class="btn btn-warning btn-xs">ESTABLECER CONSUMO MÁXIMO <i class="fas fa-phone"></i></a></br>
                            <br/>
                           <?php }

                           if($alertaFacturacion==0)
                           {?>
                            <a href="javascript:;" onclick="establecerAlerta('A')"
                               class="btn btn-warning btn-xs">ESTABLECER ALERTA FACTURACIÓN <i class="fas fa-phone"></i></a></br>
                            <br/>
                            <?php
                            }
                            else
                            {?>

                                <a href="javascript:;" onclick="establecerAlerta('A')" class="btn btn-warning btn-xs">MODIFICAR ALERTA FACTURACIÓN: <?php echo $alertaFacturacion;?> &euro;  <i class="fas fa-phone"></i></a>
                                <a href="javascript:;" onclick="establecerAlerta('D')" class="btn btn-danger btn-xs">ELIMINAR ALERTA<i class="fas fa-phone"></i></a><br><br>
                            <?php
                            }?>
                            <a href="../servicios/cdr-actual-aire.php?numero=<?php echo $msidn;?>"
                               class="btn btn-blue btn-xs">CONSULTAR CDR   <i class="fas fa-phone"></i></a></br>
                            <br/>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                </div>



            </div>



        </div>

</div>

</section>
<!----------------------------------------------------------------------
------------------------------------------------------------------------
-------------------------- VENTANA MODAL -------------------------------
------------------------------------------------------------------------
----------------------------------------------------------------------->
<div class="modal fade" id="modal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="padding:5%">
            <div class="text-center">
                <h3 class="text-aqua ">Cambio de producto asociado</h3>
                <i class="fas fa-recycle"></i>
                <img class="img-responsive" id="img-provision2">
                <br><br>
            </div>
            <?php

            $prodcs=Producto::getProductosServicio($_SESSION['REVENDEDOR'],$_GET['tipo']);

            if($idProveedor!=ID_PROVEEDOR_AIRENETWORKS)
            {
                    if($prodcs!=NULL )
                    {
                    ?>

                    <div class="row">
                        <form action="" method="post">
                            <input  value='' id="producto-original" class='form-control' disabled />
                        <div class="col-lg-4 col-xs-5" id="text_cliente"><b>Número de Serie:</b></div>
                        <select name="servicio" id="producto-cambio"
                                class="form-control pointer " name="nombre"  onchange="carga_detalles_servicio(this.value)">
                        <?php

                        if($_GET['tipo']!=3)
                        {
                            $refClienteAPI="NULL";
                            $msidn="NULL";
                        }

                            for($i=0;$i<count($prodcs);$i++)
                            {
                                $id=$prodcs[$i][0];
                                $nombre=$prodcs[$i][1];
                                echo "<option value='$id' id='producto'>$nombre</option>";
                            }
                         ?>
                        </select>
                    </div>
                    <?php if($idTipoServicio==ID_SER_MOVIL && $idProveedor==ID_PROVEEDOR_MASMOVIL) {
                        ?>


                        <div class="row">
                            <div class="col-lg-4 col-xs-5"><b>Motivo cambio:</b></div>
                            <select name="servicio" id="motivo-cambio"
                                    class="form-control pointer " name="nombre" id="motivo"
                                    onchange="carga_detalles_servicio(this.value)">
                                <option value="PER">Perdida de tarjeta</option>
                                <option value="ROT">Rotura o deterioro</option>
                                <option value="ROB">Robo</option>
                                <option value="R4G">Remplazo tarjeta a 4G</option>
                                <option value="OTH">Otros motivos</option>
                            </select>

                        </div>
                        <?php
                    }
                    }
                    else
                        echo "No se puede hacer el cambio de SIM a este terminal, porque no hay tarjetas SIM en Stock";
            }
            else
                echo "AIRENETWORKS sólo puede efectuar cambios de SIMS desde su plataforma GECKOS.";
            $refClienteAPI="NULL";

            ?>


            <div class="row">
                <br><br>
                <div class="col-lg-6 col-xs-12"></div>
                <div class="col-lg-3 col-xs-6 text-right">
                    <a href="#" id="" data-dismiss="modal" style="margin-top:25px" class="btn btn-danger">
                        <span>Cancelar</span>

                    </a>
                </div>
                <?php
                if($prodcs!=NULL && $idProveedor!=ID_PROVEEDOR_AIRENETWORKS) {
                    ?>
                    <div class="col-lg-3 col-xs-6 text-right">
                        <a href="#" id="btn-enviar" onclick="enviar('<?php echo $idTipoServicio;?>');" style="margin-top:25px" class="btn btn-success">
                            <span>Activar</span>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div id="trabajando" style="display:none">
                <span id="texto_trabajando">Realizando operaciones en los servidores, esto puede tardar.<br>Por
                favor espera.<br><br></span>
                <div class="progress skill-bar ">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="99"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <span class="skill">Progreso: <i class="val"></i></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <br>
                <center>
                    <span style="font-size:1em; color:red" id="msg_error"></span>
                </center>
            </div>
        </div>
    </div>

</div>


<!-- /MIDDLE -->

</div>

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '../../assets/plugins/';</script>
<script type="text/javascript" src="../../assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../assets/js/prompt.js"></script>

<!--<script type="text/javascript" src="../../assets/js/app.js"></script>-->


<script>
    function abrirModal(id)
    {
       $("#producto-original").val(id);



        $("#modal").modal();
    }
    function enviar(tipoServicio)
    {
        var idProducto=$("#producto-cambio").val();
        var motivo=$("#motivo-cambio").val();
        var idProductoOriginal=$("#producto-original").val();


        jQuery.ajax({
            url: 'guardar-cambio-producto.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{idProducto:idProducto,motivo:motivo,idProductoOriginal:idProductoOriginal,servicio:<?php echo $_GET['idServicio'];?>,tipo:<?php echo $_GET['tipo'];?>,contrato:<?php echo $_GET['idContrato'];?>,numeroMovil:$("#atributo-<?php echo ID_NUMERO_MOVIL;?>").val()},
            success: function(data)
            {
               location.reload();
            }
        });


    }
    function establecerRoaming(valor)
    {


        jQuery.ajax({
            url: 'masmovil/establecer-roaming.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{refCliente:<?php echo $refClienteAPI?>,numero:<?php echo $msidn;?>,valor:valor},
            success: function(data)
            {
                alert(data);
            }
        });
    }
    function establecerConsumo(maximo)
    {
        if(maximo!=0)
        {
            bootbox.prompt({
                title: "INTRODUCIR MÁXIMO EN LA LINEA",
                inputType: 'number',
                callback: function (result)
                {
                    jQuery.ajax({
                        url: 'airenetworks/establecer-consumo.php',
                        type: 'POST',
                        cache: false,
                        async:true,
                        data:{numero:<?php echo $msidn;?>,valor:result},
                        success: function(data)
                        {
                            alert(data);
                        }
                    });
                }
            });
        }
        else
        {
            jQuery.ajax({
                url: 'airenetworks/establecer-consumo.php',
                type: 'POST',
                cache: false,
                async:true,
                data:{numero:<?php echo $msidn;?>,valor:maximo},
                success: function(data)
                {
                    alert(data);
                }
            });
        }
    }
    function establecerAlerta(valor)
    {
        if(valor!='D')
        {
        bootbox.prompt({
            title: "INTRODUCIR AVISO DE FACTURACIÓN",
            inputType: 'number',
            callback: function (result)
            {
                jQuery.ajax({
                    url: 'airenetworks/establecer-alerta.php',
                    type: 'POST',
                    cache: false,
                    async:true,
                    data:{numero:<?php echo $msidn;?>,valor:result},
                    success: function(data)
                    {
                        alert(data);
                    }
                });
            }
        });
        }
        else
        {
            jQuery.ajax({
                url: 'airenetworks/establecer-alerta.php',
                type: 'POST',
                cache: false,
                async:true,
                data:{numero:<?php echo $msidn;?>,valor:0},
                success: function(data)
                {
                    alert(data);
                }
            });
        }

    }
    function estadosLineas (valor)
    {

        jQuery.ajax({
            url: 'masmovil/bloqueo-lineas.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{refCliente:<?php echo $refClienteAPI?>,numero:<?php echo $msidn;?>,valor:valor},
            success: function(data)
            {
                alert(data);
            }
        });
    }

    function carga_detalles_servicio(id)
    {


        jQuery.ajax({
            url: 'cargar_atributos_servicios.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{id:id},
            success: function(data)
            {

                $.each(data, function(i)
                {
                    var id=data[i].id_tipo_atributo;

                    $('#atributo-'+id).val(data[i].valor);



                });
            }
        });
    }
    function corteImpago(valor)
    {


        jQuery.ajax({
            url: 'airenetworks/corte-impago.php',
            type: 'POST',
            cache: false,
            async:true,
            data:{valor:valor,numero:<?php echo $msidn;?>,
                idContrato:<?php echo $_GET['idContrato'];?>,
                idLineaDetalle:<?php echo $_GET['idLineaDetalle'];?>,
                tipo:<?php echo $_GET['tipo'];?>},
            success: function(data) {

                alert("La solicitud de corte ha devuelto:"+data);
            }
        });
    }


</script>



</body>
</html>