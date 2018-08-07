<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 15/02/2018
 * Time: 8:07
 */
require_once('config/define.php');
?>
<nav id="sideNav"><!-- MAIN MENU -->
<ul class="nav nav-list">
    <li class="active"><!-- dashboard -->
        <a class="dashboard" href="<?php echo $root;?>index.php"><!-- warning - url used by default by ajax (if eneabled) -->
            <center><span style="font-size: 2em; color: #898989;">AT Control</span></center>
        </a>
    </li>
    <li class="active"><!-- dashboard -->
            <center><span style="color: #898989;">Menú de Gestión</span></center>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-users"></i><span><?php echo MNU_ITEM_3; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>add-clie.php">Agregar</a></li>
            <li><a href="<?php echo $root;?>edit-clie.php">Modificar</a></li>
            <li><a href="<?php echo $root;?>list-clie.php">Listar</a></li>
            <li><a href="<?php echo $root;?>del-clie.php">Borrar</a></li>
            <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-shopping-cart"></i><span><?php echo MNU_ITEM_8; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>content/almacen/productos.php">Productos</a></li>
            <li><a href="<?php echo $root;?>content/almacen/proveedores.php">Proveedores</a></li>
            <li><a href="<?php echo $root;?>content/almacen/tipos.php">Tipos</a></li>
            <li><a href="<?php echo $root;?>content/almacen/modelos.php">Modelos</a></li>
            <li><a href="<?php echo $root;?>content/almacen/atributos.php">Atributos</a></li>

            <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_10; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>content/servicios/servicios.php">Servicios</a></li>
            <li><a href="<?php echo $root;?>content/servicios/proveedores.php">Proveedores</a></li>
            <li><a href="<?php echo $root;?>content/servicios/paquetes.php">Paquetes</a></li>


            <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_9; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>contratar.php">Alta contrato</a></li>
            <li><a href="<?php echo $root;?>edit-contrato.php">Modificar contrato</a></li>
            <li><a href="<?php echo $root;?>/content/ventas/campanas.php">Promociones</a></li>
            <li><a href="#">Portabilidad</a>
            <ul>
                <li><a href="<?php echo $root;?>/content/ventas/porta_movil.php">Solicitud Móvil</a></li>
                <li><a href="<?php echo $root;?>/content/ventas/porta_fijo.php">Solicitud Fijo</a></li>
                <li><a href="<?php echo $root;?>edit-clie.php">Ver Estado Portabilidad</a></li>
            </ul>
            </li>
            <li><a href="<?php echo $root;?>del-clie.php">Borrar</a></li>
            <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>



    <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>

        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon fa fa-usd"></i> <span><?php echo MNU_ITEM_2; ?></span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root;?>add-reve.php">Agregar</a></li>
                <li><a href="<?php echo $root;?>edit-reve.php">Modificar</a></li>
                <li><a href="<?php echo $root;?>list-reve.php">Listar</a></li>
<!--                <li><a href="<?php echo $root;?>cons-reve.php">Consultar</a></li>-->
            </ul>
        </li>
    <?php } ?>

    <?php if (intval($_SESSION['USER_LEVEL'])==0 || intval($_SESSION['USER_LEVEL'])==1) { ?>
        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon fa fa-user"></i> <span><?php echo MNU_ITEM_1; ?></span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root;?>add-user.php">Agregar</a></li>
                <li><a href="<?php echo $root;?>edit-user.php">Modificar</a></li>
                <li><a href="<?php echo $root;?>list-user.php">Listar</a></li>
<!--                <li><a href="<?php echo $root;?>cons-user.php">Consultar</a></li>-->
                <?php if (intval($_SESSION['USER_LEVEL'])==0){
                    echo '<li><a href="<?php echo $root;?>suplantar.php">Suplantar</a></li>';
                }?>
            </ul>
        </li>
    <?php } ?>
</ul>
<ul class="nav nav-list">
    <li class="active"><!-- dashboard -->
        <center><span style="color: #898989;">Menú Técnico</span></center>
    </li>

    <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>
        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon fa fa-server"></i> <span><?php echo MNU_ITEM_6; ?></span>
            </a>
            <ul><!-- submenus -->
                <li><a href="<?php echo $root;?>add-olts.php">Agregar</a></li>
<!--                <li><a href="<?php echo $root;?>list-olts.php">Listar</a></li>-->
                <li><a href="<?php echo $root;?>perfiles-olt.php">Gestionar</a></li>
<!--                <li><a href="<?php echo $root;?>cons-olts.php">Consultar</a></li>-->
            </ul>
        </li>
    <?php } ?>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-hdd-o"></i> <span><?php echo MNU_ITEM_5; ?></span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>asignacion.php?c=1">Órdenes de trabajo</a></li>
            <li><a href="<?php echo $root;?>asignacion.php">Dar Altas</a></li>
            <?php if (intval($_SESSION['USER_LEVEL'])<2) { ?>
                <li><a href="<?php echo $root;?>bajas.php">Dar Bajas</a></li>
                <li><a href="<?php echo $root;?>modificar.php">Modificar</a></li>
                <li><a href="<?php echo $root;?>listados_altas.php">Listados y consultas</a></li>
            <?php } ?>


        </ul>
    </li>
    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-wrench"></i> <span><?php echo MNU_ITEM_7; ?></span>
        </a>
        <ul><!-- submenus -->
            <li><a href="<?php echo $root;?>ver_estado_ont.php">Comprobar Estado Ont</a></li>
            <li><a href="<?php echo $root;?>ver_ip_serial.php">Obtener IP & SERIAL</a></li>
            <li><a href="<?php echo $root;?>ver_estado_olt.php">Comprobar Estado Olt</a></li>
            <li><a href="<?php echo $root;?>salvar_cabeceras.php">Salvar configuración cabecera</a></li>
            <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>
                <li><a href="<?php echo $root;?>olt_commands.php">Comandos Especiales</a></li>

            <?php } ?>
        </ul>
    </li>


        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon fa fa-cogs"></i> <span><?php echo MNU_ITEM_4; ?></span>
            </a>
            <ul><!-- submenus -->
                <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>
                    <li><a href="<?php echo $root;?>tipos-usuarios.php">Tipos usuarios</a></li>
                    <li><a href="<?php echo $root;?>niveles.php">Niveles acceso</a></li>
                <?php } ?>
                <li><a href="<?php echo $root;?>config_pppoe.php">Servidor PPPoE</a></li>

            </ul>
        </li>


</ul>

<!-- SECOND MAIN LIST -->
<ul class="nav nav-list">
    <li class="active"><!-- dashboard -->
        <center><span style="color: #898989;">Útiles</span></center>
    </li>
    <li>
        <a href="<?php echo $root;?>calendar.php">
            <i class="main-icon fa fa-calendar"></i>
            <span>Calendario</span>
        </a>
    </li>
    <li>
        <a href="<?php echo $root;?>invoice.html">
            <i class="main-icon fa fa-inbox"></i>
            <span>Mensajes</span>
        </a>
    </li>
    <li>
        <a href="<?php echo $root;?>ayuda.php">
            <i class="main-icon fa fa-question"></i>
            <span>Ayuda</span>
        </a>
    </li>
    <li>
        <a href="<?php echo $root;?>novedades.php">
            <i class="main-icon fa fa-newspaper-o blink"></i>
            <span class="blink">Novedades</span>
        </a>
    </li>
</ul>
    <center>
    <?php if($_SESSION['LOGO']!='') echo "<img style='max-width:200px' src='".$_SESSION['LOGO']."'>";?>
    </center>
    <br><br>
    <div id="aquiprocesando"></div>
</nav>

<script>
    function salvarcabecera() {
        trabajando();
        trabajando(1);
        $.ajax({
            url: 'salvar_cabeceras.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                cabecera: id
            },
            success: function () {
                trabajando(0);
            }
        });
    }
</script>


