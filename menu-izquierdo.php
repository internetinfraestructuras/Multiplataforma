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
        <a class="dashboard" href="/index.php"><!-- warning - url used by default by ajax (if eneabled) -->
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
            <li><a href="/add-clie.php">Agregar</a></li>
            <li><a href="/edit-clie.php">Modificar</a></li>
            <li><a href="/list-clie.php">Listar</a></li>
            <li><a href="/del-clie.php">Borrar</a></li>
            <!--            <li><a href="/cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-shopping-cart"></i><span><?php echo MNU_ITEM_8; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/content/almacen/agregar.php">Alta Productos</a></li>
            <li><a href="/content/almacen/proveedores.php">Proveedores</a></li>
            <li><a href="/content/almacen/tipos.php">Tipos</a></li>
            <li><a href="/content/almacen/modelos.php">Modelos</a></li>

            <!--            <li><a href="/cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_10; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/content/servicios/internet.php">Internet</a></li>
            <li><a href="/content/servicios/telefonia-fija.php.php">Telefonía fija</a></li>
            <li><a href="/content/servicios/telefonia-movil.php">Telefonia Móvil</a></li>
            <li><a href="/content/servicios/television.php">Televisión</a></li>
            <li><a href="/content/servicios/otros.php">Otros</a></li>

            <!--            <li><a href="/cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_9; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/contratar.php">Alta contrato</a></li>
            <li><a href="/edit-contrato.php">Modificar contrato</a></li>
            <li><a href="/promos.php">Promociones</a></li>
            <li><a href="/.php">Solicitar Portabilidad</a></li>
            <li><a href="/edit-clie.php">Ver Estado Portabilidad</a></li>
            <li><a href="/del-clie.php">Borrar</a></li>
            <!--            <li><a href="/cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>



    <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>

        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon fa fa-usd"></i> <span><?php echo MNU_ITEM_2; ?></span>
            </a>
            <ul><!-- submenus -->
                <li><a href="/add-reve.php">Agregar</a></li>
                <li><a href="/edit-reve.php">Modificar</a></li>
                <li><a href="/list-reve.php">Listar</a></li>
<!--                <li><a href="/cons-reve.php">Consultar</a></li>-->
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
                <li><a href="/add-user.php">Agregar</a></li>
                <li><a href="/edit-user.php">Modificar</a></li>
                <li><a href="/list-user.php">Listar</a></li>
<!--                <li><a href="/cons-user.php">Consultar</a></li>-->
                <?php if (intval($_SESSION['USER_LEVEL'])==0){
                    echo '<li><a href="/suplantar.php">Suplantar</a></li>';
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
                <li><a href="/add-olts.php">Agregar</a></li>
<!--                <li><a href="/list-olts.php">Listar</a></li>-->
                <li><a href="/perfiles-olt.php">Gestionar</a></li>
<!--                <li><a href="/cons-olts.php">Consultar</a></li>-->
            </ul>
        </li>
    <?php } ?>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-hdd-o"></i> <span><?php echo MNU_ITEM_5; ?></span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/asignacion.php?c=1">Órdenes de trabajo</a></li>
            <li><a href="/asignacion.php">Dar Altas</a></li>
            <?php if (intval($_SESSION['USER_LEVEL'])<2) { ?>
                <li><a href="/bajas.php">Dar Bajas</a></li>
                <li><a href="/modificar.php">Modificar</a></li>
                <li><a href="/listados_altas.php">Listados y consultas</a></li>
            <?php } ?>


        </ul>
    </li>
    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-wrench"></i> <span><?php echo MNU_ITEM_7; ?></span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/ver_estado_ont.php">Comprobar Estado Ont</a></li>
            <li><a href="/ver_ip_serial.php">Obtener IP & SERIAL</a></li>
            <li><a href="/ver_estado_olt.php">Comprobar Estado Olt</a></li>
            <li><a href="/salvar_cabeceras.php">Salvar configuración cabecera</a></li>
            <?php if (intval($_SESSION['USER_LEVEL'])==0) { ?>
                <li><a href="/olt_commands.php">Comandos Especiales</a></li>

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
                    <li><a href="/tipos-usuarios.php">Tipos usuarios</a></li>
                    <li><a href="/niveles.php">Niveles acceso</a></li>
                <?php } ?>
                <li><a href="/config_pppoe.php">Servidor PPPoE</a></li>

            </ul>
        </li>


</ul>

<!-- SECOND MAIN LIST -->
<ul class="nav nav-list">
    <li class="active"><!-- dashboard -->
        <center><span style="color: #898989;">Útiles</span></center>
    </li>
    <li>
        <a href="/calendar.php">
            <i class="main-icon fa fa-calendar"></i>
            <span>Calendario</span>
        </a>
    </li>
    <li>
        <a href="/invoice.html">
            <i class="main-icon fa fa-inbox"></i>
            <span>Mensajes</span>
        </a>
    </li>
    <li>
        <a href="/ayuda.php">
            <i class="main-icon fa fa-question"></i>
            <span>Ayuda</span>
        </a>
    </li>
    <li>
        <a href="/novedades.php">
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
<!---->
<!--<script>-->
<!--    function salvarcabecera() {-->
<!--        trabajando();-->
<!--        trabajando(1);-->
<!--        $.ajax({-->
<!--            url: 'salvar_cabeceras.php',-->
<!--            type: 'POST',-->
<!--            cache: false,-->
<!--            async: true,-->
<!--            data: {-->
<!--                cabecera: id-->
<!--            },-->
<!--            success: function () {-->
<!--                trabajando(0);-->
<!--            }-->
<!--        });-->
<!--    }-->
<!--</script>-->


