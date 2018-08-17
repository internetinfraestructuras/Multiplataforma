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
            <li><a href="<?php echo $root;?>content/ventas/solicitudes-cambios.php">Solicitudes cambios</a></li>
            <li><a href="<?php echo $root;?>content/ventas/contratos.php">Contratos</a></li>
            <li><a href="<?php echo $root;?>content/ventas/campanas.php">Promociones</a></li>
            <li><a href="<?php echo $root;?>.php">Solicitar Portabilidad</a></li>
            <li><a href="<?php echo $root;?>edit-clie.php">Ver Estado Portabilidad</a></li>
            <li><a href="<?php echo $root;?>del-clie.php">Borrar</a></li>
            <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
        </ul>
    </li>

    <li>
        <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_11; ?> </span>
        </a>
        <ul><!-- submenus -->
            <li><a href="/mmm/content/trabajos/ordenes-hoy.php">Ordenes de hoy</a></li>
            <li><a href="/mmm/content/trabajos/ordenes.php">Ordenes de trabajo</a></li>


        </ul>
    </li>


>>>>>>> 111c2c8e58a05b525731fd3d4b8f983ce55773b4

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
                <li><a href="<?php echo $root;?>content/ventas/solicitudes-cambios.php">Solicitudes cambios</a></li>
                <li><a href="<?php echo $root;?>content/ventas/contratos.php">Contratos</a></li>
                <li><a href="<?php echo $root;?>content/ventas/campanas.php">Promociones</a></li>
                <li><a href="<?php echo $root;?>.php">Solicitar Portabilidad</a></li>
                <li><a href="<?php echo $root;?>edit-clie.php">Ver Estado Portabilidad</a></li>
                <li><a href="<?php echo $root;?>del-clie.php">Borrar</a></li>
                <!--            <li><a href="<?php echo $root;?>cons-clie.php">Consultar</a></li>-->
            </ul>
        </li>

        <li>
            <a href="#">
                <i class="fa fa-menu-arrow pull-right"></i>
                <i class="main-icon"><img src="/img/tasks-solid.svg" style="color:#C1C8C8;height:22px;"></i><span><?php echo MNU_ITEM_11; ?> </span>
            </a>
            <ul><!-- submenus -->
                <li><a href="/mmm/content/trabajos/ordenes-hoy.php">Ordenes de hoy</a></li>
                <li><a href="/mmm/content/trabajos/ordenes.php">Ordenes de trabajo</a></li>


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


